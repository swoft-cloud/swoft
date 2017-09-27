<?php

namespace Swoft\Event\Events;

use Swoft\Event\ApplicationEvent;


/**
 * 任务前置事件源
 *
 * @uses      BeforeTaskEvent
 * @version   2017年09月26日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class BeforeTaskEvent extends ApplicationEvent
{
    private $logid;
    private $spanid;
    private $taskName;
    private $method;
    private $type;

    /**
     * BeforeTaskEvent constructor.
     *
     * @param null   $source
     * @param string $logid
     * @param int    $spanid
     * @param string $taskName
     * @param string $method
     * @param string $type
     */
    public function __construct($source = null, string $logid, int $spanid, string $taskName, string $method, string $type)
    {
        parent::__construct($source);
        $this->type = $type;
        $this->logid = $logid;
        $this->spanid = $spanid;
        $this->method = $method;
        $this->taskName = $taskName;
    }

    /**
     * @return string
     */
    public function getLogid(): string
    {
        return $this->logid;
    }

    /**
     * @return int
     */
    public function getSpanid(): int
    {
        return $this->spanid;
    }

    /**
     * @return string
     */
    public function getTaskName(): string
    {
        return $this->taskName;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}