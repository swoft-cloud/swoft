<?php

namespace swoft\event;

/**
 * 抽象事件类
 *
 * @uses      ApplicationEvent
 * @version   2017年08月30日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class ApplicationEvent
{
    /**
     * 是否停止后续监听器执行，默认是false
     *
     * @var bool
     */
    private $handled = false;

    private $source;

    public function __construct($source = null)
    {
        $this->source = $source;
    }

    /**
     * @return bool
     */
    public function isHandled(): bool
    {
        return $this->handled;
    }
}