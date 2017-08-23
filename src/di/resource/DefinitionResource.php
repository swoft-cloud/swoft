<?php

namespace swoft\di\resource;

use swoft\di\ObjectDefinition;
use swoft\di\ObjectDefinition\PropertyInjection;
use swoft\di\ObjectDefinition\MethodInjection;
use swoft\di\ObjectDefinition\ArgsInjection;

/**
 * 定义配置解析资源
 *
 * @uses      DefinitionResource
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class DefinitionResource extends AbstractResource
{
    /**
     * 定义的beans配置
     *
     * @var array
     */
    private $definitions = [];

    /**
     * DefinitionResource constructor.
     *
     * @param $definitions
     */
    public function __construct($definitions)
    {
        $this->definitions = $definitions;
        $this->properties = $definitions['config']['properties'];
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
        $definitions = [];
        foreach ($this->definitions as $beanName => $definition) {
            $definitions[$beanName] = $this->resolvedefinitation($beanName, $definition);
        }

        return $definitions;
    }

    /**
     * 解析bean配置
     *
     * @param string $beanName   名称
     * @param array  $definition 数组定义格式
     *
     * @return ObjectDefinition
     */
    public function resolvedefinitation(string $beanName, array $definition)
    {
        if (!isset($definition['class'])) {
            throw new \InvalidArgumentException("definitions of bean 初始化失败，class字段没有配置,data=" . json_encode($definition));
        }

        $className = $definition['class'];
        unset($definition['class']);

        // 初始化
        $objDefinitation = new ObjectDefinition();
        $objDefinitation->setName($beanName);
        $objDefinitation->setClassName($className);

        // 解析属性和构造函数
        list($propertyInjections, $constructorInjection) = $this->resolverPropertiesAndConstructor($definition);

        // 设置属性和构造函数
        $objDefinitation->setPropertyInjections($propertyInjections);
        if ($constructorInjection != null) {
            $objDefinitation->setConstructorInjection($constructorInjection);
        }

        return $objDefinitation;
    }

    /**
     * 解析配置属性和构造函数
     *
     * @param array $definition
     *
     * @return array
     * <pre>
     * [$propertyInjections, $constructorInjection]
     * <pre>
     */
    private function resolverPropertiesAndConstructor(array $definition)
    {
        $propertyInjections = [];
        $constructorInjection = null;

        // 循环解析
        foreach ($definition as $name => $property) {

            // 构造函数
            if (is_array($property) && $name === 0) {
                $constructorInjection = $this->resolverConstructor($property);
                continue;
            }

            // 数组属性解析
            if (is_array($property)) {
                $injectProperty = $this->resolverArrayArgs($property);
                $propertyInjection = new PropertyInjection($name, $injectProperty, false);
                $propertyInjections[$name] = $propertyInjection;
                continue;
            }

            // 普通解析
            list($injectProperty, $isRef) = $this->getTransferProperty($property);
            $propertyInjection = new PropertyInjection($name, $injectProperty, (bool)$isRef);
            $propertyInjections[$name] = $propertyInjection;
        }

        return [$propertyInjections, $constructorInjection];
    }

    /**
     * 解析数组值属性
     *
     * @param array $propertyValue
     *
     * @return array
     */
    private function resolverArrayArgs(array $propertyValue)
    {
        $args = [];
        foreach ($propertyValue as $key => $subArg) {

            // 递归解析
            if (is_array($subArg)) {
                $args[$key] = $this->resolverArrayArgs($subArg);
                continue;
            }

            // 普通解析
            list($injectProperty, $isRef) = $this->getTransferProperty($subArg);
            $args[$key] = new ArgsInjection($injectProperty, (bool)$isRef);
        }

        return $args;
    }

    /**
     * 解析构造函数
     *
     * @param array $args
     *
     * @return MethodInjection
     */
    private function resolverConstructor(array $args)
    {
        $methodArgs = [];
        foreach ($args as $arg) {

            // 数组参数解析
            if(is_array($arg)){
                $injectProperty = $this->resolverArrayArgs($arg);
                $methodArgs[] = new ArgsInjection($injectProperty, false);
                continue;
            }

            // 普通参数解析
            list($injectProperty, $isRef) = $this->getTransferProperty($arg);
            $methodArgs[] = new ArgsInjection($injectProperty, (bool)$isRef);
        }

        $methodInject = new MethodInjection("__construct", $methodArgs);
        return $methodInject;
    }
}