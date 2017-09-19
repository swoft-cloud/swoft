<?php

namespace Swoft\Bean\ObjectDefinition;

/**
 * 方法注入对象
 *
 * @uses      MethodInjection
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class MethodInjection
{
    /**
     * 方法名称
     *
     * @var string
     */
    private $methodName;

    /**
     * 参数对象
     *
     * @var ArgsInjection[]
     */
    private $parameters = [];

    /**
     * MethodInjection constructor.
     *
     * @param string $methodName
     * @param array  $parameters
     */
    public function __construct(string $methodName, array $parameters)
    {
        $this->methodName = $methodName;
        $this->parameters = $parameters;
    }

    /**
     * 获取方法名称
     *
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * 获取参数对象列表
     *
     * @return ArgsInjection[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
