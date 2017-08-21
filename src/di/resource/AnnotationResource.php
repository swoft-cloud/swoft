<?php

namespace swoft\di\resource;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use PhpDocReader\PhpDocReader;
use swoft\App;
use swoft\di\annotation\Bean;
use swoft\di\annotation\Inject;
use swoft\di\annotation\Scope;
use swoft\di\ObjectDefinition;

/**
 * 注释解析
 *
 * @uses      AnnotationResource
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class AnnotationResource extends AbstractResource
{
    private $scanNamespaces = [
        'swoft' => BASE_PATH."/src"
    ];

    private $definitions = [];

    public function getDefinitions()
    {
        $classNames = $this->registerLoaderAndScanBean();
        foreach ($classNames as $className){
            $this->registerBean($className);
        }
        return $this->definitions;
    }

    public function registerBean($className)
    {
        if (!class_exists($className)) {
            return null;
        }
        $reader = new AnnotationReader();
        $reflectionClass = new \ReflectionClass($className);
        $classAnnotations = $reader->getClassAnnotations($reflectionClass);

        list($beanName, $scope) = $this->parseClassAnnotations($className, $classAnnotations);

        // 没配置注入bean注解
        if (empty($beanName)) {
            return null;
        }

        $objectDefinition = new ObjectDefinition();
        $objectDefinition->setName($beanName);
        $objectDefinition->setClassName($className);
        $objectDefinition->setScope($scope);


        $properties = $reflectionClass->getProperties();
        $propertyInjections = $this->parseProperties($reader, $properties, $className);
        $objectDefinition->setPropertyInjections($propertyInjections);

        $this->definitions[$beanName] = $objectDefinition;
    }

    /**
     * 解析注释属性
     *
     * @param AnnotationReader $reader
     * @param array            $properties
     * @return array
     */
    private function parseProperties(AnnotationReader $reader, array $properties, string $className)
    {
        $propertyInjections = [];

        /* @var \ReflectionProperty $property*/
        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }
            $propertyName = $property->getName();
            list($injectProperty, $isRef) = $this->parsePropertyAnnotation($reader, $property, $className, $propertyName);
            if($injectProperty == null){
                continue;
            }
            $propertyInjection = new ObjectDefinition\PropertyInjection($propertyName, $injectProperty, (bool)$isRef);
            $propertyInjections[$propertyName] = $propertyInjection;
        }

        return $propertyInjections;
    }

    private function parsePropertyAnnotation(AnnotationReader $reader, \ReflectionProperty $property, string $className, string $propertyName)
    {
        $isRef = false;
        $injectProperty = "";
        $propertyAnnotations = $reader->getPropertyAnnotations($property);
        if(empty($propertyAnnotations)){
            $injectProperty = null;
            $isRef = false;
        }
        foreach ($propertyAnnotations as $propertyAnnotation) {
            if ($propertyAnnotation instanceof Inject) {
                $injectValue = $propertyAnnotation->getName();
                if (!empty($injectValue)) {
                    list($injectProperty, $isRef) = $this->getTransferProperty($injectValue);
                    continue;
                }

                $phpReader = new PhpDocReader();
                $property = new \ReflectionProperty($className, $propertyName);
                $propertyClass = $phpReader->getPropertyClass($property);

                $isRef = true;
                $injectProperty = $propertyClass;
                continue;
            }else{
                $injectProperty = null;
                $isRef = false;
            }


        }

        return [$injectProperty, $isRef];
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

        foreach ($classAnnotations as $classAnnotation){
            if($classAnnotation instanceof Bean){
                $name = $classAnnotation->getName();
                $scope = $classAnnotation->getScope();

                $beanName = empty($name)? $className: $name;
            }
        }
        return [$beanName, $scope];
    }

    /**
     * 添加扫描namespace
     *
     * @param array $namespaces
     */
    public function addScanNamespaces(array $namespaces)
    {
        foreach ($namespaces as $namespace){
            $nsPath = str_replace("\\", "/", $namespace);
            $this->scanNamespaces[$namespace] = BASE_PATH."/".$nsPath;
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
        foreach ($files as $file){
            if($file != '.' && $file != '..' && is_dir($dir."/".$file)){
                $phpFiles = array_merge($phpFiles, $this->scanPhpFile($dir."/".$file, $namespace."\\".$file));
            }elseif(strpos($file, '.php') !== false){
                $file = str_replace(".php", "", $file);
                $phpFiles[] = $namespace."\\".$file;
            }
        }

        return $phpFiles;
    }


    /**
     * 注册加载器和扫描PHP文件
     *
     * @return array
     */
    private function registerLoaderAndScanBean()
    {
        $phpClass = [];
        foreach ($this->scanNamespaces as $namespace => $dir){
            AnnotationRegistry::registerLoader(function($class) use($dir){
                if(!class_exists($class)){
                    return false;
                }
                return true;
            });
            $scanClass = $this->scanPhpFile($dir, $namespace);
            $phpClass = array_merge($phpClass, $scanClass);
        }
        return $phpClass;
    }
}