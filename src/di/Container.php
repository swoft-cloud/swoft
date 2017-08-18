<?php

namespace swoft\di;

use swoft\di\ObjectDefinition\PropertyInjection;
use swoft\di\resolver\DefinitionResource;

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
        if(!isset($this->definitions['config']['properties'])){
            throw new \InvalidArgumentException("config bean properties没有配置");
        }
        $this->properties = $this->definitions['config']['properties'];

        $resource = new DefinitionResource($definitions);
        $this->definitions = $resource->getDefinitions();
    }

    public function autoloadAnnotations()
    {

    }
}