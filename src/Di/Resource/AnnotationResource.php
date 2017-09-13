<?php

namespace Swoft\Di\Resource;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Swoft\Di\Parser\AbstractParser;
use Swoft\Di\Wrapper\IWrapper;

/**
 * 注释解析
 *
 * @uses      AnnotationResource
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class AnnotationResource extends AbstractResource
{
    /**
     * 自动扫描命令空间
     *
     * @var array
     */
    private $scanNamespaces
        = [
            'Swoft' => BASE_PATH . "/src"
        ];

    /**
     * 已解析的bean定义
     *
     * @var array
     * <pre>
     * [
     *     'beanName' => ObjectDefinition,
     *      ...
     * ]
     * </pre>
     */
    private $definitions = [];


    private $annotations = [];

    public function __construct(array $properties)
    {
        $this->properties = $properties;

    }

    /**
     * 获取已解析的配置beans
     *
     * @return array
     * <pre>
     * [
     *     'beanName' => ObjectDefinition,
     *      ...
     * ]
     * </pre>
     */
    public function getDefinitions()
    {
        // 获取扫描的PHP文件
        $classNames = $this->registerLoaderAndScanBean();
        foreach ($classNames as $className) {
            $this->parseBeanAnnotations($className);
        }

        $this->parseAnnotationsData();

        return $this->definitions;
    }

    public function parseBeanAnnotations(string $className)
    {
        if (!class_exists($className)) {
            return null;
        }

        // 注解解析器
        $reader = new AnnotationReader();
        $reflectionClass = new \ReflectionClass($className);
        $classAnnotations = $reader->getClassAnnotations($reflectionClass);

        foreach ($classAnnotations as $classAnnotation){
            $this->annotations[$className]['class'][get_class($classAnnotation)] = $classAnnotation;
        }

        // 解析属性
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }
            $propertyName = $property->getName();
            $propertyAnnotations = $reader->getPropertyAnnotations($property);


            foreach ($propertyAnnotations as $propertyAnnotation){
                $this->annotations[$className]['property'][$propertyName][get_class($propertyAnnotation)] = $propertyAnnotation;
            }
        }

        // 解析方法
        $publicMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($publicMethods as $method) {
            if ($method->isStatic()) {
                continue;
            }

            $methodName = $method->getName();

            // 解析方法注解
            $methodAnnotations = $reader->getMethodAnnotations($method);

            foreach ($methodAnnotations as $methodAnnotation){
                $this->annotations[$className]['method'][$methodName][get_class($methodAnnotation)] = $methodAnnotation;
            }
        }
    }

    public function parseAnnotationsData(){
        foreach ($this->annotations as $className => $annotation){
            $classAnnotations = $annotation['class'];
            foreach ($classAnnotations as $classAnnotation){
                $annotationClassName = get_class($classAnnotation);
                $classNameTmp = str_replace('\\', '/', $annotationClassName);
                $classFileName = basename($classNameTmp);

                $annotationParserClassName = "Swoft\\Di\Wrapper\\" . $classFileName . "Wrapper";
                if (!class_exists($annotationParserClassName)) {
                    continue;
                }

                /* @var IWrapper $wrapper*/
                $wrapper = new $annotationParserClassName($this);
                $objectDefinitionAry = $wrapper->doWrapper($className, $annotation);
                if($objectDefinitionAry != null){
                    list($beanName, $objectDefinition) = $objectDefinitionAry;
                    $this->definitions[$beanName] = $objectDefinition;
                }
            }
        }
    }

    /**
     * 注册加载器和扫描PHP文件
     *
     * @return array
     */
    private function registerLoaderAndScanBean()
    {
        $phpClass = [];
        foreach ($this->scanNamespaces as $namespace => $dir) {
            AnnotationRegistry::registerLoader('class_exists');
            $scanClass = $this->scanPhpFile($dir, $namespace);
            $phpClass = array_merge($phpClass, $scanClass);
        }
        return $phpClass;
    }

    /**
     * 添加扫描namespace
     *
     * @param array $namespaces
     */
    public function addScanNamespaces(array $namespaces)
    {
        foreach ($namespaces as $namespace) {
            $nsPath = str_replace("\\", "/", $namespace);
            $nsPath = str_replace('App/', 'app/', $nsPath);
            $this->scanNamespaces[$namespace] = BASE_PATH . "/" . $nsPath;
        }
    }


    /**
     * 扫描目录下PHP文件
     *
     * @param string $dir
     * @param string $namespace
     *
     * @return array
     */
    private function scanPhpFile(string $dir, string $namespace)
    {
        $iterator = new \RecursiveDirectoryIterator($dir);
        $files = new \RecursiveIteratorIterator($iterator);

        $phpFiles = [];
        foreach ($files as $file) {
            $fileType = pathinfo($file, PATHINFO_EXTENSION);
            if ($fileType != 'php') {
                continue;
            }

            $replaces = ["", '\\', "", ""];
            $searchs = [$dir, '/', '.php', '.PHP'];

            $file = str_replace($searchs, $replaces, $file);
            $phpFiles[] = $namespace . $file;
        }

        return $phpFiles;
    }
}