<?php

namespace swoft\di\annotation;

/**
 * bean注解
 *
 * @Annotation
 * @Target("CLASS")
 *
 * @uses      Bean
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
final class Bean
{
    /**
     * @var string bean名称
     */
    private $name;

    /**
     * @var int
     */
    private $scope = Scope::SINGLETON;

    public function __construct(array $values)
    {
        if(isset($values['value'])){
            $this->name = $values['value'];
        }
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
        if (isset($values['scope'])) {
            $this->scope = $values['scope'];
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getScope()
    {
        return $this->scope;
    }

}