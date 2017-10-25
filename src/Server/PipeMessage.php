<?php

namespace Swoft\Server;

/**
 * 管道消息
 *
 * @uses      PipeMessage
 * @version   2017年10月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class PipeMessage
{
    /**
     * 任务消息
     */
    const TYPE_TASK = 'task';

    /**
     * pipe消息格式化
     *
     * @param string $type 类型
     * @param array  $data 数据
     *
     * @return string
     */
    public static function pack(string $type, array $data)
    {
        $data = [
            'pipeType' => $type,
            'message'  => $data
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * pipe消息解析
     *
     * @param string $message 消息字符串
     *
     * @return array
     */
    public static function unpack(string $message)
    {
        $messageAry = json_decode($message, true);
        $type = $messageAry['pipeType'];
        $data = $messageAry['message'];

        return [$type, $data];
    }
}