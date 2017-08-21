<?php

namespace swoft\di\resource;

use swoft\di\ObjectDefinition;
use swoft\di\ObjectDefinition\PropertyInjection;
use swoft\di\ObjectDefinition\MethodInjection;
use swoft\di\ObjectDefinition\ArgsInjection;


/**
 *
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
     * @var array
     */
    private $definitions = [];

    public function __construct($definitions)
    {
        $this->definitions = $definitions;
        $this->properties = $definitions['config']['properties'];
    }

    public function getDefinitions()
    {
        $definitions = [];
        foreach ($this->definitions as $beanName => $definition) {
            $definitions[$beanName] = $this->resolvedefinitation($beanName, $definition);
        }

        return $definitions;
    }

    public function resolvedefinitation($beanName, array $definition)
    {
        if (!isset($definition['class'])) {
            throw new \InvalidArgumentException("definitions of bean 初始化失败，class字段没有配置,data=" . json_encode($definition));
        }
        $className = $definition['class'];
        unset($definition['class']);

        $objDefinitation = new ObjectDefinition();
        $objDefinitation->setName($beanName);
        $objDefinitation->setClassName($className);

        $propertyInjections = [];
        $constructorInjection = null;

        foreach ($definition as $name => $property) {


            // 构造函数
            if (is_array($property) && $name === 0) {
                $constructorInjection = $this->resolverConstructor($property);
                continue;
            }

            if(is_array($property)){
                $injectProperty = $this->getArrayPropertyValue($property);

                $propertyInjection = new PropertyInjection($name, $injectProperty, false);
                $propertyInjections[$name] = $propertyInjection;
            }else{
                list($injectProperty, $isRef) = $this->getTransferProperty($property);
                $propertyInjection = new PropertyInjection($name, $injectProperty, (bool)$isRef);
                $propertyInjections[$name] = $propertyInjection;
            }
        }

        $objDefinitation->setPropertyInjections($propertyInjections);
        if($constructorInjection != null){
            $objDefinitation->setConstructorInjection($constructorInjection);
        }

        return $objDefinitation;
    }

    private function getArrayPropertyValue(array $propertyValue)
    {
        $args = [];
        foreach ($propertyValue as $key => $subArg){
            if(is_array($subArg)){
                $args[$key] = $this->getArrayPropertyValue($subArg);
                continue;
            }

            list($injectProperty, $isRef) = $this->getTransferProperty($subArg);
            $args[$key] = new ArgsInjection($injectProperty, (bool)$isRef);
        }
        return $args;
    }

    /**
     * @param array $args
     *
     * @return MethodInjection
     */
    private function resolverConstructor(array $args)
    {
        $methodArgs = [];
        foreach ($args as $arg) {
            list($injectProperty, $isRef) = $this->getTransferProperty($arg);
            $methodArgs[] = new ArgsInjection($injectProperty, (bool)$isRef);
        }

        $methodInject = new MethodInjection("__construct", $methodArgs);
        return $methodInject;
    }
}