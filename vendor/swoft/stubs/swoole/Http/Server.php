<?php

namespace Swoole\Http;
/**
 * Class swoole_http_server
 *
 *  内置 Web 服务器
 */
class Server extends \swoole_server
{
    /**
     * 启用数据合并，HTTP请求数据到PHP的GET/POST/COOKIE全局数组
     * @param     $flag
     * @param int $request_flag
     */
    function setGlobal($flag, $request_flag = 0)
    {
    }
}
