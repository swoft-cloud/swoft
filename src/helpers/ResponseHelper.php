<?php

namespace swoft\helpers;

/**
 *  数据响应帮助类
 *
 * @uses      ResponseHelper
 * @version   2017年08月01日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ResponseHelper
{
    /**
     * 格式化返回数据
     *
     * @param string $data    数据默认是空字符串
     * @param int    $status  状态200成功
     * @param string $message 描述文案
     *
     * @return array
     */
    public static function formatData($data = "", $message = "", $status = 200)
    {
        return [
            'data'   => $data,
            'status' => $status,
            'msg'    => $message,
            'time'   => microtime(true)
        ];
    }
}