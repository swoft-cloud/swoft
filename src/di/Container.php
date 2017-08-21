<?php

namespace swoft\di;

use app\controllers\IndexController;
use swoft\di\annotation\Scope;
use swoft\di\ObjectDefinition\ArgsInjection;
use swoft\di\ObjectDefinition\PropertyInjection;
use swoft\di\resource\AnnotationResource;
use swoft\di\resource\DefinitionResource;

/**
 * 全局容器
 *
 * @uses      Container
 * @version   2017年08月17日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Container
{
    /**
     * Map of entries with Singleton scope that are already resolved.
     *
     * @var array
     */
    private $singletonEntries = [];

    /**
     * @var ObjectDefinition[][]
     */
    private $definitions = [];

    /**
     * @var array
     */
    private $properties = [];

    private $initMethod = 'init';

    public function get(string $name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException("the name of bean 只能是字符串， name=" . json_encode($name));
        }
        if (isset($this->singletonEntries[$name])) {
            return $this->singletonEntries[$name];
        }

        if (!isset($this->definitions[$name])) {
            throw new \InvalidArgumentException("the name of bean 不存在， name=" . $name);
        }

        /* @var ObjectDefinition $objectDefinition */
        $objectDefinition = $this->definitions[$name];

        return $this->set($name, $objectDefinition);
    }

    public function create(string $beanName, array $definition)
    {
        return [];
    }

    public function hasBean(string $beanName): bool
    {
        return isset($this->definitions[$beanName]);
    }

    public function set(string $name, ObjectDefinition $objectDefinition)
    {
        $scope = $objectDefinition->getScope();
        $className = $objectDefinition->getClassName();
        $propertyInjects = $objectDefinition->getPropertyInjections();
        $constructorInject = $objectDefinition->getConstructorInjection();

        $constructorParameters = [];

        if ($constructorInject != null) {
            /* @var ArgsInjection $parameter */
            foreach ($constructorInject->getParameters() as $parameter) {
                if ($parameter->isRef()) {
                    $constructorParameters[] = $this->get($parameter->getValue());
                    continue;
                }
                $constructorParameters[] = $parameter->getValue();
            }
        }

        $reflectionClass = new \ReflectionClass($className);
        $properties = $reflectionClass->getProperties();

        $isDoMethod = $reflectionClass->hasMethod($this->initMethod);
        if($reflectionClass->hasMethod("__construct")){
            $object = $reflectionClass->newInstanceArgs($constructorParameters);
        }else{
            $object = $reflectionClass->newInstance();
        }

        foreach ($properties as $property) {

            if ($property->isStatic()) {
                continue;
            }

            $propertyName = $property->getName();
            if (!isset($propertyInjects[$propertyName])) {
                continue;
            }

            if (!$property->isPublic()) {
                $property->setAccessible(true);
            }

            /* @var PropertyInjection $propertyInject */
            $propertyInject = $propertyInjects[$propertyName];

            $injectProperty = $propertyInject->getValue();
            if(is_array($injectProperty)){
                $injectProperty = $this->injectArrayProperty($injectProperty);
            }
            if ($propertyInject->isRef()) {
                $injectProperty = $this->get($injectProperty);
            }

            $property->setValue($object, $injectProperty);
        }

        if ($isDoMethod) {
            $object->{$this->initMethod}();
        }

        if ($scope == Scope::SINGLETON) {
            $this->singletonEntries[$name] = $object;
        }

        return $object;
    }

    /**
     * @param array $injectProperty
     * @return array
     */
    private function injectArrayProperty(array $injectProperty)
    {
        $injectAry = [];
        foreach ($injectProperty as $key => $property){
            if(is_array($property)){
                $injectAry[$key] = $this->injectArrayProperty($property);
                continue;
            }

            if($property instanceof ArgsInjection){
                $propertyVlaue = $property->getValue();
                if($property->isRef()){
                    $injectAry[] = $this->get($propertyVlaue);
                    continue;
                }
                $injectAry[$key] = $propertyVlaue;
            }
        }
        return $injectAry;
    }


    /**
     * @return array
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * @param array $definitions
     */
    public function addDefinitions(array $definitions)
    {
        // properties.php配置数据
        if (!isset($definitions['config']['properties'])) {
            throw new \InvalidArgumentException("config bean properties没有配置");
        }
        $this->properties = $definitions['config']['properties'];

        $resource = new DefinitionResource($definitions);
        $this->definitions = $resource->getDefinitions();
    }

    public function autoloadAnnotations()
    {
        if(!isset($this->properties['beanScan'])){
            throw new \InvalidArgumentException("自动扫描注释，命令空间未配置the beanScan of properties!");
        }
        $beanScan = $this->properties['beanScan'];

        $resource = new AnnotationResource();
        $resource->addScanNamespaces($beanScan);
        $definitions = $resource->getDefinitions();

        $this->definitions = array_merge($definitions, $this->definitions);
    }
}