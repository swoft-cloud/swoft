<?php
/**
 * swoole-ide-helper.
 *
 * Author: wudi <wudi23@baidu.com>
 * Date: 2016/02/17
 */

namespace Swoole\WebSocket;


class Frame
{
    /**
     * @var int
     */
    public $fd;

    /**
     * @var bool
     */
    public $finish;

    /**
     * @var string
     */
    public $opcode;

    /**
     * @var string
     */
    public $data;
}