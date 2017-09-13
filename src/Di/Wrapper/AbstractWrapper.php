<?php

namespace Swoft\Di\Wrapper;

use Swoft\Di\Annotation\Scope;
use Swoft\Di\ObjectDefinition;
use Swoft\Di\ObjectDefinition\PropertyInjection;
use Swoft\Di\Parser\AbstractParser;
use Swoft\Di\Parser\MethodWithoutAnnotationParser;
use Swoft\Di\Resource\AnnotationResource;

/**
 * 抽象封装器
 *
 * @uses      AbstractWrapper
 * @version   2017年09月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractWrapper implements IWrapper
{
    /**
     * 类注解
     *
     * @var array
     */
    protected $classAnnotations = [];

    /**
     * 属性注解
     *
     * @var array
     */
    protected $propertyAnnotations = [];

    /**
     * 方法注解
     *
     * @var array
     */
    protected $methodAnnotations = [];

    /**
     * 注解资源
     *
     * @var AnnotationResource
     */
    protected $annotationResource;

    /**
     * AbstractWrapper constructor.
     *
     * @param AnnotationResource $annotationResource
     */
    public function __construct(AnnotationResource $annotationResource)
    {
        $this->annotationResource = $annotationResource;
    }

    /**
     * 封装注解
     *
     * @param string $className
     * @param array  $annotations
     *
     * @return array|null
     */
    public function doWrapper(string $className, array $annotations)
    {
        $reflectionClass = new \ReflectionClass($className);

        // 解析类级别的注解
        list($beanName, $scope) = $this->parseClassAnnotations($className, $annotations['class']);

        // 没配置注入bean注解
        if (empty($beanName)) {
            // 解析属性
            $properties = $reflectionClass->getProperties();

            // 解析属性
            $propertyAnnotations = $annotations['property']??[];
            $this->parseProperties($propertyAnnotations, $properties, $className);

            // 解析方法
            $publicMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
            $methodAnnotations = $annotations['method'] ??[];
            $this->parseMethods($methodAnnotations, $className, $publicMethods);

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
        $propertyInjections = $this->parseProperties($propertyAnnotations, $properties, $className);
        $objectDefinition->setPropertyInjections($propertyInjections);

        // 解析方法
        $publicMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methodAnnotations = $annotations['method'] ??[];
        $this->parseMethods($methodAnnotations, $className, $publicMethods);

        return [$beanName, $objectDefinition];
    }

    /**
     * 解析属性
     *
     * @param array  $propertyAnnotations
     * @param array  $properties
     * @param string $className
     *
     * @return array
     */
    private function parseProperties(array $propertyAnnotations, array $properties, string $className)
    {
        $propertyInjections = [];

        $object = new $className();
        /* @var \ReflectionProperty $property */
        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $propertyValue = $property->getValue($object);

            list($injectProperty, $isRef) = $this->parsePropertyAnnotations($propertyAnnotations, $className, $propertyName, $propertyValue);
            if ($injectProperty == null) {
                continue;
            }
            $propertyInjection = new PropertyInjection($propertyName, $injectProperty, (bool)$isRef);
            $propertyInjections[$propertyName] = $propertyInjection;
        }

        return $propertyInjections;
    }

    /**
     * 解析方法
     *
     * @param array  $methodAnnotations
     * @param string $className
     * @param array  $publicMethods
     */
    private function parseMethods(array $methodAnnotations, string $className, array $publicMethods)
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
        $isWithoutMethodAnnotation = empty($methodAnnotations) || !isset($methodAnnotations[$methodName]);
        if ($isWithoutMethodAnnotation || !$this->isParseMethodAnnotations($methodAnnotations[$methodName])) {
            $this->parseMethodWithoutAnnotation($className, $methodName);
            return;
        }

        // 循环方法注解解析
        foreach ($methodAnnotations[$methodName] as $methodAnnotation) {
            $annotationClass = get_class($methodAnnotation);
            if (!in_array($annotationClass, $this->methodAnnotations)) {
                continue;
            }

            // 解析器解析
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
        $parser = new MethodWithoutAnnotationParser($this->annotationResource);
        $parser->parser($className, null, "", $methodName);
    }

    /**
     * 属性解析
     *
     * @param  array $propertyAnnotations
     * @param string $className
     * @param string $propertyName
     * @param mixed  $propertyValue
     *
     * @return array
     */
    private function parsePropertyAnnotations(array $propertyAnnotations, string $className, string $propertyName, $propertyValue)
    {
        $isRef = false;
        $injectProperty = "";

        // 没有任何注解
        if (empty($propertyAnnotations) || !isset($propertyAnnotations[$propertyName])
            || !$this->isParsePropertyAnnotations($propertyAnnotations[$propertyName])
        ) {
            return [null, false];
        }

        // 属性注解解析
        foreach ($propertyAnnotations[$propertyName] as $propertyAnnotation) {

            $annotationClass = get_class($propertyAnnotation);
            if (!in_array($annotationClass, $this->propertyAnnotations)) {
                continue;
            }

            // 解析器
            $annotationParser = $this->getAnnotationParser($propertyAnnotation);
            if ($annotationParser === null) {
                $injectProperty = null;
                $isRef = false;
                continue;
            }
            list($injectProperty, $isRef) = $annotationParser->parser($className, $propertyAnnotation, $propertyName, "", $propertyValue);
        }

        return [$injectProperty, $isRef];
    }

    /**
     * 类注解解析
     *
     * @param string $className
     * @param array  $annotations
     *
     * @return array
     */
    public function parseClassAnnotations(string $className, array $annotations)
    {
        $beanName = '';
        $scope = Scope::SINGLETON;
        if (!$this->isParseClassAnnotations($annotations)) {
            return [$beanName, $scope];
        }

        foreach ($annotations as $annotation) {
            $annotationClass = get_class($annotation);
            if (!in_array($annotationClass, $this->classAnnotations)) {
                continue;
            }

            // 解析器
            $annotationParser = $this->getAnnotationParser($annotation);
            if ($annotationParser == null) {
                continue;
            }
            $annotationData = $annotationParser->parser($className, $annotation);
            if ($annotationData == null) {
                continue;
            }
            list($beanName, $scope) = $annotationData;
        }
        return [$beanName, $scope];
    }

    /**
     *  获取注解对应解析器
     *
     * @param $objectAnnotation
     *
     * @return AbstractParser
     */
    private function getAnnotationParser($objectAnnotation)
    {
        $annotationClassName = get_class($objectAnnotation);
        $classNameTmp = str_replace('\\', '/', $annotationClassName);
        $className = basename($classNameTmp);

        // 解析器类名
        $annotationParserClassName = "Swoft\\Di\Parser\\" . $className . "Parser";
        if (!class_exists($annotationParserClassName)) {
            return null;
        }

        $annotationParser = new $annotationParserClassName($this->annotationResource);
        return $annotationParser;
    }
}