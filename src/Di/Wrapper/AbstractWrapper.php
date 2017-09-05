<?php

namespace Swoft\Di\Wrapper;

use Swoft\Di\Annotation\AutoController;
use Swoft\Di\Annotation\Inject;
use Swoft\Di\Annotation\RequestMapping;
use Swoft\Di\Annotation\Scope;
use Swoft\Di\ObjectDefinition;
use Swoft\Di\ObjectDefinition\PropertyInjection;
use Swoft\Di\Parser\AbstractParser;
use Swoft\Di\Parser\MethodWithoutAnnotationParser;
use Swoft\Di\Resource\AnnotationResource;
use Swoft\Di\ResourceDataProxy;

/**
 *
 *
 * @uses      AbstractWrapper
 * @version   2017年09月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractWrapper implements IWrapper
{
    protected $classAnnotations = [];

    protected $propertyAnnotations = [];

    protected $methodAnnotations = [];

    protected $resourceDataProxy;
    protected $annotationResource;

    public function __construct(AnnotationResource $annotationResource, ResourceDataProxy $resourceDataProxy)
    {
        $this->annotationResource = $annotationResource;
        $this->resourceDataProxy = $resourceDataProxy;
    }

    public function doWrapper(string $className, array $annotations)
    {
        $reflectionClass = new \ReflectionClass($className);

        // 解析类级别的注解
        list($beanName, $scope) = $this->parseClassAnnotations($className, $annotations['class']);

        // 没配置注入bean注解
        if (empty($beanName)) {
            return null;
        }
        // 初始化对象
        $objectDefinition = new ObjectDefinition();
        $objectDefinition->setName($beanName);
        $objectDefinition->setClassName($className);
        $objectDefinition->setScope($scope);

        // 解析属性
        $properties = $reflectionClass->getProperties();

        // 解析属性
        $propertyAnnotations = $annotations['property']??[];
        $propertyInjections = $this->parseProperties( $propertyAnnotations,$properties, $className);
        $objectDefinition->setPropertyInjections($propertyInjections);

        // 解析方法
        $publicMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methodAnnotations = $annotations['method'] ??[];
        $this->parseMethods($methodAnnotations, $className, $publicMethods);

        return [$beanName, $objectDefinition];
    }

    private function parseProperties($propertyAnnotations, array $properties, string $className)
    {
        $propertyInjections = [];

        /* @var \ReflectionProperty $property */
        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }
            $propertyName = $property->getName();
            list($injectProperty, $isRef) = $this->parsePropertyAnnotations($propertyAnnotations, $className, $propertyName);
            if ($injectProperty == null) {
                continue;
            }
            $propertyInjection = new PropertyInjection($propertyName, $injectProperty, (bool)$isRef);
            $propertyInjections[$propertyName] = $propertyInjection;
        }

        return $propertyInjections;
    }


    private function parseMethods($methodAnnotations, string $className, array $publicMethods)
    {
        // 循环解析
        foreach ($publicMethods as $method) {
            if ($method->isStatic()) {
                continue;
            }
            // 解析方法注解
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
        if (empty($methodAnnotations)  || !isset($methodAnnotations[$methodName]) || !$this->isParseMethodAnnotations($methodAnnotations[$methodName])) {
            $this->parseMethodWithoutAnnotation($className, $methodName);
            return;
        }

        foreach ($methodAnnotations[$methodName] as $methodAnnotation) {
            $annotationClass = get_class($methodAnnotation);
            if(!in_array($annotationClass, $this->methodAnnotations)){
                continue;
            }

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
        $parser = new MethodWithoutAnnotationParser($this->annotationResource, $this->resourceDataProxy);
        $parser->parser($className, null, "", $methodName);
    }


    private function parsePropertyAnnotations($propertyAnnotations, string $className, string $propertyName)
    {
        $isRef = false;
        $injectProperty = "";

        // 没有任何注解
        if (empty($propertyAnnotations) ||  !isset($propertyAnnotations[$propertyName]) || !$this->isParsePropertyAnnotations($propertyAnnotations[$propertyName])) {
            return [null, false];
        }

        // 属性注解解析
        foreach ($propertyAnnotations[$propertyName] as $propertyAnnotation) {

            $annotationClass = get_class($propertyAnnotation);
            if(!in_array($annotationClass, $this->propertyAnnotations)){
                continue;
            }

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

    public function parseClassAnnotations($className, $annotations)
    {
        $beanName = '';
        $scope = Scope::SINGLETON;
        if(!$this->isParseClassAnnotations($annotations)){
            return [$beanName, $scope];
        }

        foreach ($annotations as $annotation){
            $annotationClass = get_class($annotation);
            if(!in_array($annotationClass, $this->classAnnotations)){
                continue;
            }
            $annotationParser = $this->getAnnotationParser($annotation);
            if($annotationParser == null){
                continue;
            }
            $annotationData = $annotationParser->parser($className, $annotation);
            if($annotationData == null){
                return [$beanName, $scope];
            }
            list($beanName, $scope) = $annotationData;
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

        $annotationParser = new $annotationParserClassName($this->annotationResource, $this->resourceDataProxy);
        return $annotationParser;
    }


    abstract public function isParseClassAnnotations($annotations);
    abstract public function isParsePropertyAnnotations($annotations);
    abstract public function isParseMethodAnnotations($annotations);
}