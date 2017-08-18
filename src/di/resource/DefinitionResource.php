<?php

namespace swoft\di\resolver;

use swoft\di\ObjectDefinition;
use swoft\di\ObjectDefinition\PropertyInjection;
use swoft\di\ObjectDefinition\MethodInjection;


/**
 *
 *
 * @uses      DefinitionResource
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class DefinitionResource implements IResource
{
    /**
     * @var array
     */
    private $definitions = [];

    /**
     * @var array
     */
    private $properties = [];

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

        return [];
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
        $constructorInjection = [];
        foreach ($definition as $name => $property) {
            // 构造函数
            if (is_array($property) && $name == 0) {
                $constructorInjection = $this->resolverConstructor($property);
                continue;
            }

            $injectProperty = $property;
            $isRef = preg_match('/^\$\{(.*)\}$/', $property, $match);

            if (!empty($match)) {
                $injectProperty = $this->getInjectProperty($match[1]);
            }

            $propertyInjection = new PropertyInjection($name, $injectProperty, (bool)$isRef);
            $propertyInjections[] = $propertyInjection;

        }

        $objDefinitation->setPropertyInjections($propertyInjections);
        $objDefinitation->setConstructorInjection($constructorInjection);

        return $objDefinitation;
    }

    private function getInjectProperty(string $property)
    {
        // '${beanName}'格式解析
        $propertyKeys = explode(".", $property);
        if (count($propertyKeys) == 1) {
            return $property;
        }

        if ($propertyKeys[0] != 'config') {
            throw new \InvalidArgumentException("properties配置引用格式不正确，key=" . $propertyKeys[0]);
        }

        // '${config.xx.yy}' 格式解析,直接key
        $propertyKey = str_replace("config.", "", $property);
        if (isset($this->properties[$propertyKey])) {
            return $this->properties[$propertyKey];
        }

        // '${config.xx.yy}' 格式解析, 层级解析
        $layerProperty = "";
        unset($propertyKeys[0]);
        foreach ($propertyKeys as $subPropertyKey) {
            if (isset($this->properties[$subPropertyKey])) {
                $layerProperty = $this->properties[$subPropertyKey];
                continue;
            }

            if (!isset($layerProperty[$subPropertyKey])) {
                throw new \InvalidArgumentException("$subPropertyKey is not exisit configed");
            }
            $layerProperty = $layerProperty[$subPropertyKey];
        }

        return $layerProperty;

    }

    private function resolverConstructor(array $args)
    {
        return new MethodInjection();
    }
}