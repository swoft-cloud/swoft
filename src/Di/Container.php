<?php

namespace Swoft\Di;

use Swoft\Di\Annotation\Scope;
use Swoft\Di\ObjectDefinition\ArgsInjection;
use Swoft\Di\ObjectDefinition\MethodInjection;
use Swoft\Di\ObjectDefinition\PropertyInjection;
use Swoft\Di\Resource\AnnotationResource;
use Swoft\Di\Resource\DefinitionResource;

/**
 * 全局容器
 *
 * @uses      Container
 * @version   2017年08月17日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
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
     * 已解析的bean规则
     *
     * @var ObjectDefinition[][]
     */
    private $definitions = [];

    /**
     * properties.php配置信息
     *
     * @var array
     */
    private $properties = [];

    /**
     * 已解析的路由规则
     *
     * @var array
     */
    private $requestMapping = [];

    /**
     * 监听器
     *
     * @var array
     */
    private $listeners = [];

    /**
     * 默认创建bean执行的初始化方法
     *
     * @var string
     */
    private $initMethod = 'init';

    /**
     * 获取一个bean
     *
     * @param string $name 名称
     *
     * @return mixed
     */
    public function get(string $name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException("the name of bean 只能是字符串， name=" . json_encode($name));
        }

        // 已经创建
        if (isset($this->singletonEntries[$name])) {
            return $this->singletonEntries[$name];
        }

        // 未定义
        if (!isset($this->definitions[$name])) {
            throw new \InvalidArgumentException("the name of bean 不存在， name=" . $name);
        }

        /* @var ObjectDefinition $objectDefinition */
        $objectDefinition = $this->definitions[$name];

        return $this->set($name, $objectDefinition);
    }

    public function create(string $beanName, array $definition)
    {
    }

    /**
     * 是否存在某个bean
     *
     * @param string $beanName 名称
     *
     * @return bool
     */
    public function hasBean(string $beanName): bool
    {
        return isset($this->definitions[$beanName]);
    }

    /**
     * 定义配置bean
     *
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

    /**
     * 解析注释bean
     */
    public function autoloadAnnotations()
    {
        if (!isset($this->properties['beanScan'])) {
            throw new \InvalidArgumentException("自动扫描注释，命令空间未配置the beanScan of properties!");
        }
        $beanScan = $this->properties['beanScan'];

        $resource = new AnnotationResource();
        $resource->addScanNamespaces($beanScan);
        $resource->setProperties($this->properties);
        $definitions = $resource->getDefinitions();
        $this->listeners = $resource->getListeners();
        $this->requestMapping = $resource->getRequestMapping();

        $this->definitions = array_merge($definitions, $this->definitions);
    }

    /**
     * 初始化已定义的bean
     */
    public function initBeans()
    {
        $autoInitBeans = $this->properties['autoInitBean'] ?? false;
        if (!$autoInitBeans) {
            return;
        }

        // 循环初始化
        foreach ($this->definitions as $beanName => $definition) {
            $this->get($beanName);
        }
    }

    /**
     * 所有bean定义
     *
     * @return array
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * 获取注入路由规则
     *
     * @return array
     */
    public function getRequestMapping()
    {
        return $this->requestMapping;
    }

    /**
     * 监听器
     *
     * @return array
     */
    public function getListeners(): array
    {
        return $this->listeners;
    }

    /**
     * 创建bean
     *
     * @param string           $name             名称
     * @param ObjectDefinition $objectDefinition bean定义
     *
     * @return object
     */
    private function set(string $name, ObjectDefinition $objectDefinition)
    {
        // bean创建信息
        $scope = $objectDefinition->getScope();
        $className = $objectDefinition->getClassName();
        $propertyInjects = $objectDefinition->getPropertyInjections();
        $constructorInject = $objectDefinition->getConstructorInjection();

        // 构造函数
        $constructorParameters = [];
        if ($constructorInject != null) {
            $constructorParameters = $this->injectConstructor($constructorInject);
        }

        $reflectionClass = new \ReflectionClass($className);
        $properties = $reflectionClass->getProperties();

        // new实例
        $isExeMethod = $reflectionClass->hasMethod($this->initMethod);
        $object = $this->newBeanInstance($reflectionClass, $constructorParameters);

        // 属性注入
        $this->injectProperties($object, $properties, $propertyInjects);

        // 执行初始化方法
        if ($isExeMethod) {
            $object->{$this->initMethod}();
        }

        // 单例处理
        if ($scope == Scope::SINGLETON) {
            $this->singletonEntries[$name] = $object;
        }

        return $object;
    }

    /**
     * 获取构造函数参数
     *
     * @param MethodInjection $constructorInject
     *
     * @return array
     */
    private function injectConstructor(MethodInjection $constructorInject)
    {
        $constructorParameters = [];

        /* @var ArgsInjection $parameter */
        foreach ($constructorInject->getParameters() as $parameter) {
            $argValue = $parameter->getValue();
            if (is_array($argValue)) {
                $constructorParameters[] = $this->injectArrayArgs($argValue);
                continue;
            }
            if ($parameter->isRef()) {
                $constructorParameters[] = $this->get($parameter->getValue());
                continue;
            }
            $constructorParameters[] = $parameter->getValue();
        }
        return $constructorParameters;
    }

    /**
     *
     *  初始化Bean实例
     *
     * @param \ReflectionClass $reflectionClass
     * @param array            $constructorParameters
     *
     * @return object
     */
    private function newBeanInstance(\ReflectionClass $reflectionClass, array $constructorParameters)
    {
        if ($reflectionClass->hasMethod("__construct")) {
            return $reflectionClass->newInstanceArgs($constructorParameters);
        }
        return $reflectionClass->newInstance();
    }

    /**
     * 注入属性
     *
     * @param  mixed                $object
     * @param \ReflectionProperty[] $properties $properties
     * @param  mixed                $propertyInjects
     */
    private function injectProperties($object, array $properties, $propertyInjects)
    {
        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $propertyName = $property->getName();
            if (!isset($propertyInjects[$propertyName])) {
                continue;
            }

            // 设置可用
            if (!$property->isPublic()) {
                $property->setAccessible(true);
            }

            /* @var PropertyInjection $propertyInject */
            $propertyInject = $propertyInjects[$propertyName];
            $injectProperty = $propertyInject->getValue();

            // 属性是数组
            if (is_array($injectProperty)) {
                $injectProperty = $this->injectArrayArgs($injectProperty);
            }

            // 属性是bean引用
            if ($propertyInject->isRef()) {
                $injectProperty = $this->get($injectProperty);
            }

            $property->setValue($object, $injectProperty);
        }
    }

    /**
     * 数组属性值注入
     *
     * @param array $injectProperty
     *
     * @return array
     */
    private function injectArrayArgs(array $injectProperty)
    {
        $injectAry = [];
        foreach ($injectProperty as $key => $property) {
            // 递归循环注入
            if (is_array($property)) {
                $injectAry[$key] = $this->injectArrayArgs($property);
                continue;
            }

            // 参数注入
            if ($property instanceof ArgsInjection) {
                $propertyVlaue = $property->getValue();
                if ($property->isRef()) {
                    $injectAry[$key] = $this->get($propertyVlaue);
                    continue;
                }
                $injectAry[$key] = $propertyVlaue;
            }
        }
        return $injectAry;
    }
}
