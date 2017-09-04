<?php

namespace Swoft\Di\Resource;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Swoft\Di\Annotation\Scope;
use Swoft\Di\ObjectDefinition;
use Swoft\Di\Parser\AbstractParser;
use Swoft\Di\Parser\MethodWithoutAnnotationParser;
use Swoft\Di\ResourceDataProxy;

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

    /**
     * @var ResourceDataProxy
     */
    private $resourceDataProxy;

    public function __construct(ResourceDataProxy $resourceDataProxy)
    {
        $this->resourceDataProxy = $resourceDataProxy;
        $this->properties = $resourceDataProxy->getProperties();
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
        return $this->definitions;
    }

    /**
     *
     * 解析bean注解
     *
     * @param string $className
     */
    public function parseBeanAnnotations(string $className)
    {
        if (!class_exists($className)) {
            return null;
        }

        // 注解解析器
        $reader = new AnnotationReader();
        $reflectionClass = new \ReflectionClass($className);
        $classAnnotations = $reader->getClassAnnotations($reflectionClass);

        // 解析类级别的注解
        list($beanName, $scope) = $this->parseClassAnnotations($className, $classAnnotations);

        // 没配置注入bean注解
        if (empty($beanName)) {
            return;
        }

        // 初始化对象
        $objectDefinition = new ObjectDefinition();
        $objectDefinition->setName($beanName);
        $objectDefinition->setClassName($className);
        $objectDefinition->setScope($scope);


        // 解析属性
        $properties = $reflectionClass->getProperties();
        $propertyInjections = $this->parseProperties($reader, $properties, $className);
        $objectDefinition->setPropertyInjections($propertyInjections);

        // 解析方法
        $publicMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        $this->parseMethods($reader, $className, $publicMethods);

        $this->definitions[$beanName] = $objectDefinition;
    }

    /**
     * 解析类注释
     *
     * @param string $className
     * @param array  $classAnnotations
     *
     * @return array
     */
    private function parseClassAnnotations(string $className, array $classAnnotations)
    {
        $beanName = '';
        $scope = Scope::SINGLETON;

        // 类注解解析
        foreach ($classAnnotations as $classAnnotation) {
            if (!is_object($classAnnotation)) {
                continue;
            }

            $annotationParser = $this->getAnnotationParser($classAnnotation);
            list($beanName, $scope) = $annotationParser->parser($className, $classAnnotation);
        }
        return [$beanName, $scope];
    }

    /**
     * @param $objectAnnotation
     *
     * @return AbstractParser
     */
    private function getAnnotationParser($objectAnnotation)
    {
        $annotationClassName = get_class($objectAnnotation);
        $classNameTmp = str_replace('\\', '/', $annotationClassName);
        $className = basename($classNameTmp);

        $annotationParserClassName = "Swoft\\Di\Parser\\" . $className . "Parser";
        if (!class_exists($annotationParserClassName)) {
            return null;
        }

        $annotationParser = new $annotationParserClassName($this, $this->resourceDataProxy);
        return $annotationParser;
    }

    /**
     * 解析注释属性
     *
     * @param AnnotationReader $reader
     * @param array            $properties
     * @param string           $className
     *
     * @return array
     */
    private function parseProperties(AnnotationReader $reader, array $properties, string $className)
    {
        $propertyInjections = [];

        /* @var \ReflectionProperty $property */
        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }
            $propertyName = $property->getName();
            list($injectProperty, $isRef) = $this->parsePropertyAnnotations($reader, $property, $className, $propertyName);
            if ($injectProperty == null) {
                continue;
            }
            $propertyInjection = new ObjectDefinition\PropertyInjection($propertyName, $injectProperty, (bool)$isRef);
            $propertyInjections[$propertyName] = $propertyInjection;
        }

        return $propertyInjections;
    }

    /**
     * 解析属性注解
     *
     * @param AnnotationReader    $reader
     * @param \ReflectionProperty $property
     * @param string              $className
     * @param string              $propertyName
     *
     * @return array
     */
    private function parsePropertyAnnotations(AnnotationReader $reader, \ReflectionProperty $property, string $className, string $propertyName)
    {
        $isRef = false;
        $injectProperty = "";
        $propertyAnnotations = $reader->getPropertyAnnotations($property);

        // 没有任何注解
        if (empty($propertyAnnotations)) {
            return [null, false];
        }

        // 属性注解解析
        foreach ($propertyAnnotations as $propertyAnnotation) {
            $annotationParser = $this->getAnnotationParser($propertyAnnotation);
            if ($annotationParser === null) {
                $injectProperty = null;
                $isRef = false;
                continue;
            }
            list($injectProperty, $isRef) = $annotationParser->parser($className, $propertyAnnotation, $propertyName, "");
        }

        return [$injectProperty, $isRef];
    }

    /**
     * 解析方法
     *
     * @param AnnotationReader    $reader
     * @param string              $className
     * @param \ReflectionMethod[] $publicMethods
     */
    private function parseMethods(AnnotationReader $reader, string $className, array $publicMethods)
    {
        // 循环解析
        foreach ($publicMethods as $method) {
            if ($method->isStatic()) {
                continue;
            }

            // 解析方法注解
            $methodAnnotations = $reader->getMethodAnnotations($method);
            $this->parseMethodAnnotations($className, $method, $methodAnnotations);
        }
    }

    /**
     * 解析方法注解
     *
     * @param string            $className
     * @param \ReflectionMethod $method
     * @param array             $methodAnnotations
     */
    private function parseMethodAnnotations(string $className, \ReflectionMethod $method, array $methodAnnotations)
    {
        // 方法没有注解解析
        $methodName = $method->getName();
        if (empty($methodAnnotations)) {
            $this->parseMethodWithoutAnnotation($className, $methodName);
            return;
        }

        foreach ($methodAnnotations as $methodAnnotation) {
            $annotationParser = $this->getAnnotationParser($methodAnnotation);
            if ($annotationParser == null) {
                $this->parseMethodWithoutAnnotation($className, $methodName);
                continue;
            }
            $annotationParser->parser($className, $methodAnnotation, "", $methodName);
        }
    }

    /**
     * 方法没有配置路由注解解析
     *
     * @param string $className
     * @param string $methodName
     */
    private function parseMethodWithoutAnnotation(string $className, string $methodName)
    {
        $parser = new MethodWithoutAnnotationParser($this, $this->resourceDataProxy);
        $parser->parser($className, null, "", $methodName);
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
            AnnotationRegistry::registerLoader(function ($class) use ($dir) {
                if (!class_exists($class)) {
                    return false;
                }
                return true;
            });

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
        $phpFiles = [];
        $files = scandir($dir);
        foreach ($files as $file) {
            // 排除不必要的文件解析
            if ($file != '.' && $file != '..' && is_dir($dir . "/" . $file)) {
                $phpFiles = array_merge($phpFiles, $this->scanPhpFile($dir . "/" . $file, $namespace . "\\" . $file));
                continue;
            }

            // php文件解析
            if (strpos($file, '.php') !== false) {
                $file = str_replace(".php", "", $file);
                $phpFiles[] = $namespace . "\\" . $file;
                continue;
            }
        }

        return $phpFiles;
    }
}