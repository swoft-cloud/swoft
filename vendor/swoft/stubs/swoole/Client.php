<?php

namespace Swoole;
/**
 * swoole_client
 *
 * Author: EagleWu <eaglewudi@gmail.com>
 * Date: 2016/02/17
 */
class Client
{

    /**
     * 函数执行错误会设置该变量
     *
     * @var
     */
    public $errCode;

    /**
     * socket的文件描述符
     *
     * PHP代码中可以使用:
     * $sock = fopen("php://fd/".$swoole_client->sock);
     *
     * 将swoole_client的socket转换成一个stream socket。可以调用fread/fwrite/fclose等函数进程操作。
     * swoole_server中的$fd不能用此方法转换，因为$fd只是一个数字，$fd文件描述符属于主进程
     * $swoole_client->sock可以转换成int作为数组的key.
     *
     * @var int
     */
    public $sock;

    /**
     * swoole_client构造函数
     *
     * @param int $sock_type 指定socket的类型，支持TCP/UDP、TCP6/UDP64种
     * @param int $sync_type SWOOLE_SOCK_SYNC/SWOOLE_SOCK_ASYNC  同步/异步
     * @param string $connectionKey 链接的编号，用于长连接复用
     */
    public function __construct($sock_type, $sync_type = SWOOLE_SOCK_SYNC, $connectionKey = '')
    {
    }

    /**
     * 连接到远程服务器
     *
     * @param string $host 是远程服务器的地址 v1.6.10+ 支持填写域名 Swoole会自动进行DNS查询
     * @param int $port 是远程服务器端口
     * @param float $timeout 是网络IO的超时，单位是s，支持浮点数。默认为0.1s，即100ms
     * @param int $flag 参数在UDP类型时表示是否启用udp_connect。设定此选项后将绑定$host与$port，此UDP将会丢弃非指定host/port的数据包。
     * 在send/recv前必须使用swoole_client_select来检测是否完成了连接
     * @return bool
     */
    public function connect($host, $port, $timeout = 0.1, $flag = 0)
    {
    }

    /**
     * 向远程服务器发送数据
     *
     * 参数为字符串，支持二进制数据。
     * 成功发送返回的已发数据长度
     * 失败返回false，并设置$swoole_client->errCode
     *
     * @param string $data
     * @return bool
     */
    public function send($data)
    {
    }

    /**
     * 向任意IP:PORT的服务器发送数据包，仅支持UDP/UDP6的client
     * @param $ip
     * @param $port
     * @param $data
     */
    function sendto($ip, $port, $data)
    {

    }

    /**
     * 从服务器端接收数据
     *
     * 如果设定了$waitall就必须设定准确的$size，否则会一直等待，直到接收的数据长度达到$size
     * 如果设置了错误的$size，会导致recv超时，返回 false
     * 调用成功返回结果字符串，失败返回 false，并设置$swoole_client->errCode属性
     *
     * @param int $size 接收数据的最大长度
     * @param bool $waitall 是否等待所有数据到达后返回
     * @return string
     */
    public function recv($size = 65535, $waitall = false)
    {
    }

    /**
     * 关闭远程连接
     *
     * swoole_client对象在析构时会自动close
     *
     * @return bool
     */
    public function close()
    {
    }

    /**
     * 注册异步事件回调函数
     *
     * @param $event_name
     * @param callable $callback_function
     * @return bool
     */
    public function on($event_name, $callback_function)
    {
    }

    /**
     * 判断是否连接到服务器
     * @return bool
     */
    public function isConnected()
    {
    }

    /**
     * 获取客户端socket的host:port信息
     * @return bool | array
     */
    public function getsockname()
    {
    }

    /**
     * 获取远端socket的host:port信息，仅用于UDP/UDP6协议
     * UDP发送数据到服务器后，可能会由其他的Server进行回复
     * @return bool | array
     */
    public function getpeername()
    {
    }

    /**
     * 设置客户端参数
     * @param array $setting
     */
    function set(array $setting)
    {
    }

    /**
     * 睡眠，停止接收数据
     */
    function sleep()
    {
    }

    /**
     * 唤醒，开始接收数据
     */
    function wakeup()
    {
    }
}
