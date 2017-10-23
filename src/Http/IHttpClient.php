<?php

namespace Swoft\Http;

/**
 * HTTP接口定义
 *
 * @uses      IHttpClient
 * @version   2017年10月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IHttpClient
{
    /**
     * get方法
     */
    const GET = "GET";

    /**
     * post方法
     */
    const POST = "POST";

    /**
     * put方法
     */
    const PUT = "PUT";

    /**
     * delete方法
     */
    const DELETE = "DELETE";

    /**
     * patch方法
     */
    const PATCH = "PATCH";

    /**
     * CURL调用
     *
     * @param string $url     地址
     * @param string $method  方法,默认get
     * @param mixed  $data    数组格式传递数据
     * @param int    $timeout 超时时间，单位毫秒
     * @param array  $headers 请求header
     *
     * @return mixed
     */
    public static function call(string $url, string $method = self::GET, $data, int $timeout = 3, array $headers = []);
}