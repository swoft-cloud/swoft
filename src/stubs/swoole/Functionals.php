<?php
/**
 * Swoole Develop Structure
 *
 * Swoole 结构，便于开发过程中查看文档，以及屏蔽IDE undefined 提示，便于快速查看函数用法。
 *
 * 本文件使用方式
 *
 * 普通IDE：
 * 开发Swoole项目同时，在IDE中打开/导入本文件即可。
 * 如果IDE自带 Include Path 功能(如：PhpStorm)，设置该文件路径即可。
 *
 * PhpStorm 另一种方法:
 * WinRAR打开 <Phpstorm_Dir>/plugins/php/lib/php.jar 文件
 * 复制 swoole.php 到路径：com\jetbrains\php\lang\psi\stubs\data\
 * 保存文件，重启Phpstorm.
 *
 * PS:替换前请备份php.jar 若发生错误便于恢复 :)
 *
 * Author:EagleWu <eaglewudi@gmail.com>
 * Date: 2014/01/17
 *
 */


/**
 * swoole_server_set函数用于设置swoole_server运行时的各项参数
 *
 * @param \swoole_server $serv
 * @param $arguments
 */
function swoole_server_set($serv, array $arguments)
{
}


/**
 * 创建一个swoole server资源对象
 *
 * @param string $host 参数用来指定监听的ip地址，如127.0.0.1，或者外网地址，或者0.0.0.0监听全部地址
 * @param int $port 监听的端口，如9501，监听小于1024端口需要root权限，如果此端口被占用server-start时会失败
 * @param int $mode 运行的模式，swoole提供了3种运行模式，默认为多进程模式
 * @param int $sock_type 指定socket的类型，支持TCP/UDP、TCP6/UDP64种
 */
function swoole_server_create($host, $port, $mode = SWOOLE_PROCESS, $sock_type = SWOOLE_SOCK_TCP)
{
}


/**
 * 增加监听的端口
 *
 * 您可以混合使用UDP/TCP，同时监听内网和外网端口
 * 业务代码中可以通过调用swoole_connection_info来获取某个连接来自于哪个端口
 *
 * @param \swoole_server $serv
 * @param string $host
 * @param int $port
 * @return void
 */
function swoole_server_addlisten($serv, $host = '127.0.0.1', $port = 9502)
{
}


/**
 * 设置定时器
 *
 * 第二个参数是定时器的间隔时间，单位为毫秒。
 * swoole定时器的最小颗粒是1毫秒，支持多个定时器。
 * 此函数可以用于worker进程中。或者通过swoole_server_set设置timer_interval来调整定时器最小间隔。
 *
 * 增加定时器后需要为Server设置onTimer回调函数，否则会造成严重错误。
 * 多个定时器都会回调此函数。
 * 在这个函数内需要自行switch，根据interval的值来判断是来自于哪个定时器。
 *
 * @param \swoole_server $serv
 * @param int $interval
 * @return bool
 */
function swoole_server_addtimer($serv, $interval)
{
}


/**
 * 设置Server的事件回调函数
 *
 * 第一个参数是swoole的资源对象
 * 第二个参数是回调的名称, 大小写不敏感，具体内容参考回调函数列表
 * 第三个函数是回调的PHP函数，可以是字符串，数组，匿名函数。
 *
 * 设置成功后返回true。如果$event_name填写错误将返回false。
 *
 * onConnect/onClose/onReceive 这3个回调函数必须设置，其他事件回调函数可选。
 * 如果设定了timer定时器，onTimer事件回调函数也必须设置
 *
 * @param \swoole_server $serv
 * @param string $event_name
 * @param callable $event_callback_function
 * @return bool
 */
function swoole_server_handler($serv, $event_name, $event_callback_function)
{
}


/**
 * 启动server，监听所有TCP/UDP端口
 *
 * 启动成功后会创建worker_num+2个进程。主进程+Manager进程+n*Worker进程。
 * 启动失败扩展内会抛出致命错误，请检查php error_log的相关信息。errno={number}是标准的Linux Errno，可参考相关文档。
 * 如果开启了log_file设置，信息会打印到指定的Log文件中。
 *
 * 如果想要在开机启动时，自动运行你的Server，可以在/etc/rc.local文件中加入:
 *
 * /usr/bin/php /data/webroot/www.swoole.com/server.php
 *
 * 常见的错误有及拍错方法：
 *
 * 1、bind端口失败,原因是其他进程已占用了此端口
 * 2、未设置必选回调函数，启动失败
 * 3、php有代码致命错误，请检查php的错误信息
 * 4、执行ulimit -c unlimited，打开core dump，查看是否有段错误
 * 5、关闭daemonize，关闭log，使错误信息可以打印到屏幕
 *
 * @param \swoole_server $serv
 * @return bool
 */
function swoole_server_start($serv)
{
}


/**
 * 平滑重启Server
 *
 * 一台繁忙的后端服务器随时都在处理请求，如果管理员通过kill进程方式来终止/重启服务器程序，可能导致刚好代码执行到一半终止。
 * 这种情况下会产生数据的不一致。如交易系统中，支付逻辑的下一段是发货，假设在支付逻辑之后进程被终止了。
 * 会导致用户支付了货币，但并没有发货，后果非常严重。
 *
 * Swoole提供了柔性终止/重启的机制，管理员只需要向SwooleServer发送特定的信号，Server的worker进程可以安全的结束。
 *
 * SIGTREM: 向主进程发送此信号服务器将安全终止
 * SIGUSR1: 向管理进程发送SIGUSR1信号，将平稳地restart所有worker进程，在PHP代码中可以调用swoole_server_reload($serv)完成此操作
 *
 * @param \swoole_server $serv
 * @return void
 */
function swoole_server_reload($serv)
{
}


/**
 * 关闭客户端连接
 *
 * Server主动close连接，也一样会触发onClose事件。
 * 不要在close之后写清理逻辑，应当放置到onClose回调中处理。
 *
 * @param \swoole_server $serv
 * @param int $fd
 * @param int $from_id
 * @return bool
 */
function swoole_server_close($serv, $fd, $from_id = 0)
{
}


/**
 * 向客户端发送数据
 *
 * $data的长度可以是任意的。扩展函数内会进行切分。
 * 如果是UDP协议，会直接在worker进程内发送数据包。
 * 发送成功会返回true，如果连接已被关闭或发送失败会返回false.
 *
 * @param \swoole_server $serv
 * @param int $fd
 * @param string $data
 * @param int $from_id
 * @return bool
 */
function swoole_server_send($serv, $fd, $data, $from_id = 0)
{
}


/**
 * 获取客户端连接的信息
 *
 * 返回数组含义:
 * from_id 来自哪个poll线程
 * from_fd 来自哪个server socket
 * from_port 来自哪个Server端口
 * remote_port 客户端连接的端口
 * remote_ip 客户端连接的ip
 *
 * 以下 v1.6.10 增加
 * connect_time 连接时间
 * last_time 最后一次发送数据的时间
 *
 * @param \swoole_server $serv
 * @param int $fd
 * @return array on success or false on failure.
 */
function swoole_connection_info($serv, $fd)
{
}


/**
 * 遍历当前Server所有的客户端连接
 *
 * 此函数接受3个参数，第一个参数是server的资源对象，第二个参数是起始fd，第三个参数是每页取多少条，最大不得超过100。
 * 调用成功将返回一个数字索引数组，元素是取到的$fd。
 * 数组会按从小到大排序，最后一个$fd作为新的start_fd再次尝试获取。
 *
 * @param \swoole_server $serv
 * @param int $start_fd
 * @param int $pagesize
 * @return array on success or false on failure
 */
function swoole_connection_list($serv, $start_fd = 0, $pagesize = 10)
{
}


/**
 * 设置进程的名称
 *
 * 修改进程名称后，通过ps命令看到的将不再是php your_file.php。而是设定的字符串。
 * 此函数接受一个字符串参数。
 * 此函数与PHP5.5提供的cli_set_process_title功能是相同的，但swoole_set_process_name可用于PHP5.2之上的任意版本。
 *
 * @param string $name
 * @return void
 */
function swoole_set_process_name($name)
{
}


/**
 * 将Socket加入到swoole的reactor事件监听中
 *
 * 此函数可以用在Server或Client模式下
 *
 * 参数1为socket的文件描述符；
 * 参数2为回调函数，可以是字符串函数名、对象+方法、类静态方法或匿名函数，当此socket可读是回调制定的函数。
 *
 * Server程序中会增加到server socket的reactor中。
 * Client程序中，如果是第一次调用此函数会自动创建一个reactor，并添加此socket，程序将在此处进行wait。
 * swoole_event_add函数之后的代码不会执行。当调用swoole_event_exit才会停止wait，程序继续向下执行。
 * 第二次调用只增加此socket到reactor中，开始监听事件
 *
 * @param int $sock
 * @param \\is_callable $callback
 * @param $write_callback
 * @param $flag
 * @return bool
 */
function swoole_event_add($sock, $read_callback = NULL, $write_callback = NULL, $flag = NULL)
{
}

/**
 * 修改socket的事件设置
 * 可以修改可读/可写事件的回调设置和监听的事件类型
 *
 * @param $sock
 * @param $read_callback
 * @param null $write_callback
 * @param null $flag
 */
function swoole_event_set($sock, $read_callback = NULL, $write_callback = NULL, $flag = NULL)
{
}

/**
 * 从reactor中移除监听的Socket
 *
 * swoole_event_del应当与 swoole_event_add 成对使用
 *
 * @param int $sock
 * @return bool
 */
function swoole_event_del($sock)
{
}


/**
 * 退出事件轮询
 *
 * @return void
 */
function swoole_event_exit()
{
}

/**
 * 异步写
 * @param mixed $socket
 * @param string $data
 */
function swoole_event_write($socket, $data)
{

}

/**
 * 获取MySQLi的socket文件描述符
 *
 * 可将mysql的socket增加到swoole中，执行异步MySQL查询。
 * 如果想要使用异步MySQL，需要在编译swoole时制定--enable-async-mysql
 * swoole_get_mysqli_sock仅支持mysqlnd驱动，php5.4以下版本不支持此特性
 *
 * @param mysqli $db
 * @return int
 */
function swoole_get_mysqli_sock(\mysqli $db)
{
}

/**
 * 异步执行SQL
 * @param $db
 * @param $sql
 */
function swoole_mysql_query($db, $sql, $callback)
{

}

/**
 * 投递异步任务到task_worker池中
 *
 * 此函数会立即返回，worker进程可以继续处理新的请求。
 * 此功能用于将慢速的任务异步地去执行，比如一个聊天室服务器，可以用它来进行发送广播。
 * 当任务完成时，在task_worker中调用swoole_server_finish($serv, "finish");
 * 告诉worker进程此任务已完成。当然swoole_server_finish是可选的。
 *
 * 发送的$data必须为字符串，如果是数组或对象，请在业务代码中进行serialize处理，并在onTask/onFinish中进行unserialize。
 * $data可以为二进制数据，最大长度为8K。字符串可以使用gzip进行压缩。
 *
 * 使用swoole_server_task必须为Server设置onTask和onFinish回调，
 * 否则swoole_server_start会失败。此回调函数会在task_worker进程中被调用。
 *
 * 函数会返回一个$task_id数字，表示此任务的ID。如果有finish回应，onFinish回调中会携带$task_id参数。
 *
 * task_worker的数量在swoole_server_set参数中调整，如task_worker_num => 64，表示启动64个进程来接收异步任务。
 * swoole_server_task和swoole_server_finish可发送$data的长度最大不得超过8K，此参数受SW_BUFFER_SIZE宏控制。
 *
 * @param \swoole_server $serv
 * @param string $data
 * @return int  $task_id
 */
function swoole_server_task($serv, $data)
{
}


/**
 * task_worker进程中通知worker进程，投递的任务已完成
 *
 * 此函数可以传递结果数据给worker进程
 * 使用swoole_server_finish函数必须为Server设置onFinish回调函数。此函数只可用于task_worker进程的onTask回调中
 * swoole_server_finish是可选的。如果worker进程不关心任务执行的结果，可以不调用此函数
 *
 * @param \swoole_server $serv
 * @param string $response
 * @return void
 */
function swoole_server_finish($serv, $response)
{
}


/**
 * 删除定时器
 *
 * $interval 参数为定时器的间隔时间
 * 根据定时器时间区分不同的定时器
 *
 * @param \swoole_server $serv
 * @param int $interval
 * @return void
 */
function swoole_server_deltimer($serv, $interval)
{
}


/**
 * 关闭服务器
 *
 * 此函数可以用在worker进程内。
 *
 * @param \swoole_server $serv
 * @return void
 */
function swoole_server_shutdown($serv)
{
}


/**
 * 投递堵塞任务到task进程池
 *
 * taskwait与task方法作用相同，用于投递一个异步的任务到task进程池去执行。
 * 与task不同的是taskwait是阻塞等待的，直到任务完成或者超时返回。
 * $result为任务执行的结果，由$serv->finish函数发出。如果此任务超时，这里会返回false。
 *
 * taskwait是阻塞接口，如果你的Server是全异步的请不要使用它
 *
 * @param string $task_data
 * @param float $timeout
 * @return string
 */
function swoole_server_taskwait($task_data, $timeout = 0.5)
{
}

/**
 * 进行事件轮询
 *
 * PHP5.4之前的版本没有在ZendAPI中加入注册shutdown函数。所以swoole无法在脚本结尾处自动进行事件轮询。
 * 低于5.4的版本，需要在你的PHP脚本结尾处加swoole_event_wait函数，使脚本开始进行事件轮询。
 *
 * 5.4或更高版本不需要加此函数。
 *
 * @return void
 */
function swoole_event_wait()
{
}

/**
 * 添加定时器，可用于客户端环境和fpm中
 *
 * @param $interval
 * @param $callback
 * @return int
 */
function swoole_timer_add($interval, $callback)
{
}

/**
 * 单次定时器，在N毫秒后执行回调函数
 * @param $ms
 * @param $callback  function ($user_param)｛｝
 * @param $user_param
 * @return int
 */
function swoole_timer_after($ms, $callback, $user_param = null)
{
}

/**
 * 删除定时器
 *
 * @param $interval
 */
function swoole_timer_del($interval)
{
}

/**
 * 删除定时器
 * @param $timer_id
 * @return bool
 */
function swoole_timer_clear($timer_id)
{
}

/**
 * 添加TICK定时器
 * @param      $ms
 * @param      $callback  function($timmerID, $params){}
 * @param null $params
 * @return int
 */
function swoole_timer_tick($ms, $callback, $params = null)
{

}

/**
 * 获取swoole扩展的版本号，如1.6.10
 *
 * @return string
 */
function swoole_version()
{
}

/**
 * 将标准的Unix Errno错误码转换成错误信息
 *
 * @param int $errno
 */
function swoole_strerror($errno)
{
}

/**
 * 获取最近一次系统调用的错误码，等同于C/C++的errno变量。
 *
 * @return int
 */
function swoole_errno()
{
}


/**
 * 此函数用于获取本机所有网络接口的IP地址，
 * 目前只返回IPv4地址，返回结果会过滤掉本地loop地址127.0.0.1。
 * 结果数组是以interface名称为key的关联数组。
 * 比如 array("eth0" => "192.168.1.100")
 *
 * @return array
 */
function swoole_get_local_ip()
{
}


/**
 * 异步读取文件内容
 * 此函数调用后会马上返回，当文件读取完毕时会回调制定的callback函数。
 * callback( $filename, $content )
 *
 * swoole_async_readfile会将文件内容全部复制到内存，所以不能用于大文件的读取
 * 如果要读取超大文件，请使用swoole_async_read函数
 * swoole_async_readfile最大可读取4M的文件，受限于SW_AIO_MAX_FILESIZE宏
 *
 * @param string $filename
 * @param mixed $callback
 */
function swoole_async_readfile($filename, $callback)
{
}

/**
 * 异步写文件，调用此函数后会立即返回, 当写入完成时会自动回调指定的callback函数
 * callback($filename)
 *
 * swoole_async_writefile最大可写入4M的文件
 * swoole_async_writefile可以不指定回调函数
 *
 * @param string $filename
 * @param string $content
 * @param callback $callback
 */
function swoole_async_writefile($filename, $content, $callback)
{
}

/**
 * 异步读文件
 *
 * 使用此函数读取文件是非阻塞的，当读操作完成时会自动回调制定的函数
 * 此函数与swoole_async_readfile不同，它是分段读取，可以用于读取超大文件。
 * 每次只读 $trunk_size 个字节，不会占用太多内存
 *
 * callback($filename, $content)
 * callback函数，可以通过return true/false，来控制是否继续读下一个trunk
 * return true，继续读取
 * return false，停止读取并关闭文件
 *
 * @param string $filename
 * @param mixed $callback
 * @param int $trunk_size
 * @return bool
 */
function swoole_async_read($filename, $callback, $trunk_size = 8192)
{
}

/**
 * 设置异步相关的参数
 * @param array $setting
 */
function swoole_async_set(array $setting)
{

}

/**
 * 异步写文件
 *
 * 与swoole_async_writefile不同，write是分段读写的。
 * 不需要一次性将要写的内容放到内存里，所以只占用少量内存。
 * swoole_async_write通过传入的offset参数来确定写入的位置
 *
 * callback($filename)
 *
 * @param string $filename
 * @param string $content
 * @param int $offset
 * @param mixed $callback
 *
 * @return bool
 */
function swoole_async_write($filename, $content, $offset, $callback = NULL)
{
}

/**
 * 将域名解析为IP地址
 * 调用此函数会立即返回，当DNS查询完成时，自动回调指定的callback函数
 *
 * callback($host, $ip)
 *
 * @param string $domain
 * @param callback $callback
 */
function swoole_async_dns_lookup($domain, $callback)
{
}

/**
 * IO事件循环
 *
 *
 * swoole_client的并行处理中用了select来做IO事件循环。为什么要用select呢？
 * 因为client一般不会有太多连接，而且大部分socket会很快接收到响应数据。
 * 在少量连接的情况下select比epoll性能更好，另外select更简单。
 *
 * $read,$write,$error分别是可读/可写/错误的文件描述符。
 * 这3个参数必须是数组变量的引用。数组的元素必须为swoole_client对象。
 * $timeout参数是select的超时时间，单位为秒，接受浮点数。
 *
 * 调用成功后，会返回事件的数量，并修改$read/$write/$error数组。
 * 使用foreach遍历数组，然后执行$item->recv/$item->send来收发数据。
 * 或者调用$item->close()或unset($item)来关闭socket。
 *
 *
 * @param array $read 可读
 * @param array $write 可写
 * @param array $error 错误
 * @param float $timeout
 * @return int
 */
function swoole_client_select(array &$read, array &$write, array &$error, $timeout)
{
}


class swoole_http_client extends Swoole\Http\Client
{

}

class swoole_http_request extends Swoole\Http\Request
{

}

class swoole_http_response extends Swoole\Http\Response
{

}

class swoole_http_server extends Swoole\Http\Server
{

}

class swoole_atomic extends Swoole\Atomic
{

}

class swoole_buffer extends Swoole\Buffer
{

}

class swoole_client extends Swoole\Client
{

}

class swoole_server extends Swoole\Server
{

}

class swoole_lock extends Swoole\Lock
{

}

class swoole_redis extends Swoole\Redis
{

}

class swoole_process extends Swoole\Process
{

}

class swoole_table extends Swoole\Table
{

}

class swoole_websocket_server extends Swoole\WebSocket\Server
{

}

class swoole_websocket_frame extends Swoole\WebSocket\Frame
{

}