<?php

namespace Swoft\Http;

/**
 * 抽象HTTP
 *
 * @uses      AbstractHttpClient
 * @version   2017年10月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractHttpClient implements IHttpClient
{
    /**
     * 请求header处理
     *
     * @param array  $header header参数
     * @param string $method HTTP方法
     *
     * @return array
     */
    protected static function getRequestHeader(array $header, string $method)
    {
        if ($method == self::GET) {
            return $header;
        }
        if ($method == self::POST && !isset($header['Content-Type'])) {
            $header['Content-Type'] = 'application/x-www-form-urlencoded';
        }
        return $header;
    }

    /**
     * 内容实体转换
     *
     * @param mixed $data 内存实体
     *
     * @return string
     */
    protected static function getContentData($data)
    {
        if (is_string($data)) {
            return $data;
        }
        if (is_array($data)) {
            $data = http_build_query($data);
        }
        return (string)$data;
    }
}