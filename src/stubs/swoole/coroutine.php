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
 * 复制文件到路径：com\jetbrains\php\lang\psi\stubs\data\
 * 保存文件，重启Phpstorm.
 *
 * PS:替换前请备份php.jar 若发生错误便于恢复
 *
 * Author:jonwang <jonwang@myqee.com>
 * Date: 2016/07/27
 *
 */
namespace
{

    define('HTTP_GLOBAL_ALL', 1);
    define('HTTP_GLOBAL_GET', 2);
    define('HTTP_GLOBAL_POST', 4);
    define('HTTP_GLOBAL_COOKIE', 8);
    define('WEBSOCKET_OPCODE_TEXT', 1);

    define('SWOOLE_VERSION', '1.8.8'); //当前Swoole的版本号

    /**
     * new Server 构造函数参数
     */
    define('SWOOLE_BASE', 1);     //使用Base模式，业务代码在Reactor中直接执行
    define('SWOOLE_THREAD', 2);   //使用线程模式，业务代码在Worker线程中执行
    define('SWOOLE_PROCESS', 3);  //使用进程模式，业务代码在Worker进程中执行
    define('SWOOLE_PACKET', 0x10);

    /**
     * new Client 构造函数参数
     */
    define('SWOOLE_SOCK_TCP', 1);           //创建tcp socket
    define('SWOOLE_SOCK_TCP6', 3);          //创建tcp ipv6 socket
    define('SWOOLE_SOCK_UDP', 2);           //创建udp socket
    define('SWOOLE_SOCK_UDP6', 4);          //创建udp ipv6 socket
    define('SWOOLE_SOCK_UNIX_DGRAM', 5);    //创建udp socket
    define('SWOOLE_SOCK_UNIX_STREAM', 6);   //创建udp ipv6 socket

    define('SWOOLE_SSL', 5);

    define('SWOOLE_TCP', 1);        //创建tcp socket
    define('SWOOLE_TCP6', 2);       //创建tcp ipv6 socket
    define('SWOOLE_UDP', 3);        //创建udp socket
    define('SWOOLE_UDP6', 4);       //创建udp ipv6 socket
    define('SWOOLE_UNIX_DGRAM', 5);
    define('SWOOLE_UNIX_STREAM', 6);

    define('SWOOLE_SOCK_SYNC', 0);  //同步客户端
    define('SWOOLE_SOCK_ASYNC', 1); //异步客户端

    define('SWOOLE_SYNC', 0);   //同步客户端
    define('SWOOLE_ASYNC', 1);  //异步客户端

    /**
     * new Lock构造函数参数
     */
    define('SWOOLE_FILELOCK', 2);   //创建文件锁
    define('SWOOLE_MUTEX', 3);      //创建互斥锁
    define('SWOOLE_RWLOCK', 1);     //创建读写锁
    define('SWOOLE_SPINLOCK', 5);   //创建自旋锁
    define('SWOOLE_SEM', 4);        //创建信号量

    define('SWOOLE_EVENT_WRITE', 1);
    define('SWOOLE_EVENT_READ', 2);

    function swoole_async_set(array $conf)
    {

    }

    /**
     * Server_set函数用于设置Server运行时的各项参数
     *
     * @param Swoole\Server $serv
     * @param               $arguments
     */
    function swoole_server_set($serv, array $arguments)
    {
    }

    /**
     * 创建一个swoole server资源对象
     *
     * @param string $host      参数用来指定监听的ip地址，如127.0.0.1，或者外网地址，或者0.0.0.0监听全部地址
     * @param int    $port      监听的端口，如9501，监听小于1024端口需要root权限，如果此端口被占用server-start时会失败
     * @param int    $mode      运行的模式，swoole提供了3种运行模式，默认为多进程模式
     * @param int    $sock_type 指定socket的类型，支持TCP/UDP、TCP6/UDP64种
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
     * @param Swoole\Server $serv
     * @param string        $host
     * @param int           $port
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
     * 此函数可以用于worker进程中。或者通过Server_set设置timer_interval来调整定时器最小间隔。
     *
     * 增加定时器后需要为Server设置onTimer回调函数，否则会造成严重错误。
     * 多个定时器都会回调此函数。
     * 在这个函数内需要自行switch，根据interval的值来判断是来自于哪个定时器。
     *
     * @param Swoole\Server $serv
     * @param int           $interval
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
     * @param Swoole\Server $serv
     * @param string        $event_name
     * @param callable      $event_callback_function
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
     * @param Swoole\Server $serv
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
     * SIGUSR1: 向管理进程发送SIGUSR1信号，将平稳地restart所有worker进程，在PHP代码中可以调用Server_reload($serv)完成此操作
     *
     * @param Swoole\Server $serv
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
     * @param Swoole\Server $serv
     * @param int           $fd
     * @param int           $from_id
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
     * @param Swoole\Server $serv
     * @param int           $fd
     * @param string        $data
     * @param int           $from_id
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
     * @param Swoole\Server $serv
     * @param int           $fd
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
     * @param Swoole\Server $serv
     * @param int           $start_fd
     * @param int           $pagesize
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
     * @param int|resource $sock
     * @param \\is_callable $callback
     * @param     $write_callback
     * @param     $flag
     * @return bool
     */
    function swoole_event_add($sock, $read_callback = null, $write_callback = null, $flag = null)
    {
    }

    /**
     * 修改socket的事件设置
     * 可以修改可读/可写事件的回调设置和监听的事件类型
     *
     * @param      $sock
     * @param      $read_callback
     * @param null $write_callback
     * @param null $flag
     */
    function swoole_event_set($sock, $read_callback = null, $write_callback = null, $flag = null)
    {
    }

    /**
     * 从reactor中移除监听的Socket
     *
     * swoole_event_del应当与 swoole_event_add 成对使用
     *
     * @param int|resource $sock
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
     *
     * @param mixed  $socket
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
    function swoole_get_mysqli_sock(mysqli $db)
    {

    }

    /**
     * 异步地执行一条SQL语言，需要依赖MySQLi和mysqlnd扩展。此函数是swoole底层提供的真异步函数。
     *
     * 解决了PHP官方mysqli->reap_async_query方法存在的2个严重问题:
     *
     *  * mysqli->reap_async_query的recv缓冲区设置过小，在读取较大的RecordSet时会浪费大量read系统调用，性能不佳
     *  * MySQL服务器的RecordSet可能会分段发送，mysqli->reap_async_query方法会阻塞。导致程序退化为同步阻塞模式。并发能力大大降低
     *
     * swoole_mysql_query底层使用64K内存缓冲区，即使读取很大的RecordSet也仅需少量的read系统调用。
     * 另外swoole_mysql_query借助swoole提供的Epoll接口异步读取MySQL服务器的RecordSet，整个过程没有任何阻塞。
     *
     * 每个MySQLi连接只能同时执行一条SQL，必须等待返回结果后才能执行下一条SQL
     *
     * @param \mysqli  $link 已连接的mysqli对象
     * @param string   $sql 要执行的SQL语句
     * @param callable $callback 执行成功后会回调此函数
     */
    function swoole_mysql_query(mysqli $link, $sql, callable $callback)
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
     * task_worker的数量在Server_set参数中调整，如task_worker_num => 64，表示启动64个进程来接收异步任务。
     * swoole_server_task和swoole_server_finish可发送$data的长度最大不得超过8K，此参数受SW_BUFFER_SIZE宏控制。
     *
     * @param Swoole\Server $serv
     * @param string        $data
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
     * @param Swoole\Server $serv
     * @param string        $response
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
     * @param Swoole\Server $serv
     * @param int           $interval
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
     * @param Swoole\Server $serv
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
     * @param float  $timeout
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
     *
     * @param $ms
     * @param $callback
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
     *
     * @param $timer_id
     * @return bool
     */
    function swoole_timer_clear($timer_id)
    {
    }

    /**
     * 添加TICK定时器
     *
     * @param      $ms
     * @param      $callback
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
     * @return string
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
        return 0;
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
     * @param mixed  $callback
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
     * @param string   $filename
     * @param string   $content
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
     * @param mixed  $callback
     * @param int    $trunk_size
     * @return bool
     */
    function swoole_async_read($filename, $callback, $trunk_size = 8192)
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
     * @param int    $offset
     * @param mixed  $callback
     *
     * @return bool
     */
    function swoole_async_write($filename, $content, $offset, $callback = null)
    {
    }

    /**
     * 将域名解析为IP地址
     * 调用此函数会立即返回，当DNS查询完成时，自动回调指定的callback函数
     *
     * callback($host, $ip)
     *
     * @param string   $domain
     * @param callback $callback
     */
    function swoole_async_dns_lookup($domain, $callback)
    {
    }

    /**
     * IO事件循环
     *
     *
     * Client的并行处理中用了select来做IO事件循环。为什么要用select呢？
     * 因为client一般不会有太多连接，而且大部分socket会很快接收到响应数据。
     * 在少量连接的情况下select比epoll性能更好，另外select更简单。
     *
     * $read,$write,$error分别是可读/可写/错误的文件描述符。
     * 这3个参数必须是数组变量的引用。数组的元素必须为Client对象。
     * $timeout参数是select的超时时间，单位为秒，接受浮点数。
     *
     * 调用成功后，会返回事件的数量，并修改$read/$write/$error数组。
     * 使用foreach遍历数组，然后执行$item->recv/$item->send来收发数据。
     * 或者调用$item->close()或unset($item)来关闭socket。
     *
     *
     * @param array $read  可读
     * @param array $write 可写
     * @param array $error 错误
     * @param float $timeout
     * @return int
     */
    function swoole_client_select(array &$read, array &$write, array &$error, $timeout)
    {
    }

    /**
     * 获取cpu数
     *
     * @return int
     */
    function swoole_cpu_num()
    {
        return 8;
    }
}

namespace Swoole
{

    /**
     * Client
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
         * $sock = fopen("php://fd/".$Client->sock);
         *
         * 将Client的socket转换成一个stream socket。可以调用fread/fwrite/fclose等函数进程操作。
         * Server中的$fd不能用此方法转换，因为$fd只是一个数字，$fd文件描述符属于主进程
         * $Client->sock可以转换成int作为数组的key.
         *
         * @var int
         */
        public $sock;

        /**
         * Client构造函数
         *
         * @param int $sock_type 指定socket的类型，支持TCP/UDP、TCP6/UDP64种
         * @param int $sync_type  SWOOLE_SOCK_SYNC/SWOOLE_SOCK_ASYNC  同步/异步
         * @param string $key 用于长连接的Key，默认使用IP:PORT作为key。相同key的连接会被复用
         */
        public function __construct($sock_type, $sync_type = SWOOLE_SOCK_SYNC, $key = null)
        {
        }

        /**
         * 连接到远程服务器
         *
         * @param string $host    是远程服务器的地址 v1.6.10+ 支持填写域名 Swoole会自动进行DNS查询
         * @param int    $port    是远程服务器端口
         * @param float  $timeout 是网络IO的超时，单位是s，支持浮点数。默认为0.1s，即100ms
         * @param int    $flag    参数在UDP类型时表示是否启用udp_connect。设定此选项后将绑定$host与$port，此UDP将会丢弃非指定host/port的数据包。
         *                        在send/recv前必须使用Client_select来检测是否完成了连接
         * @return bool
         */
        public function connect($host, $port, $timeout = 0.1, $flag = 0)
        {
        }

        /**
         * 调用此方法可以得到底层的socket句柄，返回的对象为sockets资源句柄
         *
         * 此方法需要依赖PHP的sockets扩展，并且编译swoole时需要开启--enable-sockets选项
         *
         * 使用 socket_set_option 函数可以设置更底层的一些 socket 参数
         *
         *     $socket = $client->getSocket();
         *     if (!socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1))
         *     {
         *         echo 'Unable to set option on socket: '. socket_strerror(socket_last_error()) . PHP_EOL;
         *     }
         *
         * @return resource On success a stream resource is returned which may
         */
        public function getSocket()
        {
            return stream_socket_client('');
        }

        /**
         * 用于获取客户端socket的本地host:port，必须在连接之后才可以使用
         *
         * 调用成功返回一个数组，如：array('host' => '127.0.0.1', 'port' => 53652)
         *
         * @return array
         */
        public function getSockName()
        {
            return ['host' => '127.0.0.1', 'port' => 1234];
        }

        /**
         * 获取对端socket的IP地址和端口，仅支持SWOOLE_SOCK_UDP/SWOOLE_SOCK_UDP6类型的swoole_client对象。
         * UDP协议通信客户端向一台服务器发送数据包后，可能并非由此服务器向客户端发送响应。可以使用getpeername方法获取实际响应的服务器IP:PORT。
         *
         * !!! 此函数必须在$client->recv() 之后调用
         *
         * @return string
         */
        public function getPeerName()
        {

        }

        /**
         * 获取服务器端证书信息
         *
         * 执行成功返回一个X509证书字符串信息
         * 执行失败返回false
         *
         * @since 1.8.8
         * @return string|false
         */
        public function getPeerCert()
        {

        }

        /**
         * 向远程服务器发送数据
         *
         * 参数为字符串，支持二进制数据。
         * 成功发送返回的已发数据长度
         * 失败返回false，并设置$Client->errCode
         *
         * @param string $data
         * @return bool
         */
        public function send($data)
        {
        }

        /**
         * 向任意IP:PORT的服务器发送数据包，仅支持UDP/UDP6的client
         *
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
         * 调用成功返回结果字符串，失败返回 false，并设置$Client->errCode属性
         *
         * @param int  $size    接收数据的最大长度
         * @param bool $waitall 是否等待所有数据到达后返回
         * @return string
         */
        public function recv($size = 65535, $waitall = false)
        {
        }

        /**
         * 关闭远程连接
         *
         * Client对象在析构时会自动close
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
         * @param $callback_function
         * @return bool
         */
        public function on($event_name, $callback_function)
        {
        }

        /**
         * 判断是否连接到服务器
         *
         * @return bool
         */
        public function isConnected()
        {
        }
    }


    /**
     * Class Server
     */
    class Server
    {
        /**
         * Server::set()函数所设置的参数会保存到Server::$setting属性上。在回调函数中可以访问运行参数的值
         *
         * @var array
         */
        public $setting = [];

        /**
         * 主进程PID
         *
         * @var int
         */
        public $master_pid;

        /**
         * 当前服务器管理进程的PID
         *
         * !! 只能在onStart/onWorkerStart之后获取到
         *
         * @var int
         */
        public $manager_pid;

        /**
         * 当前Worker进程的编号
         *
         * 这个属性与onWorkerStart时的$worker_id是相同的。
         *
         *  * Worker进程ID范围是[0, $serv->setting['worker_num'])
         *  * task进程ID范围是[$serv->setting['worker_num'], $serv->setting['worker_num'] + $serv->setting['task_worker_num'])
         *
         * 工作进程重启后worker_id的值是不变的
         *
         * @var int
         */
        public $worker_id;

        /**
         * 当前Worker进程的ID，0 - ($serv->setting[worker_num]-1)
         *
         * @var int
         */
        public $worker_pid;

        /**
         * 是否 Task 工作进程
         *
         *  true  表示当前的进程是Task工作进程
         *  false 表示当前的进程是Worker进程
         *
         * @var bool
         */
        public $taskworker;

        /**
         * TCP连接迭代器，可以使用foreach遍历服务器当前所有的连接，此属性的功能与Server->connnection_list是一致的，但是更加友好。遍历的元素为单个连接的fd
         *
         * 连接迭代器依赖pcre库，未安装pcre库无法使用此功能
         *
         *      foreach($server->connections as $fd)
         *      {
         *          $server->send($fd, "hello");
         *      }
         *
         *      echo "当前服务器共有 ".count($server->connections). " 个连接\n";
         *
         * @var array
         */
        public $connections;

        /**
         * Server构造函数
         *
         * @param     $host
         * @param     $port
         * @param int $mode
         * @param int $sock_type
         */
        function __construct($host, $port, $mode = SWOOLE_PROCESS, $sock_type = SWOOLE_SOCK_TCP)
        {
        }

        /**
         * 注册事件回调函数，与Server->on相同。Http\Server->on的不同之处是：
         *
         * * Http\Server->on不接受onConnect/onReceive回调设置
         * * Http\Server->on 额外接受1种新的事件类型onRequest
         *
         *  事件列表
         *
         *  * onStart
         *  * onShutdown
         *  * onWorkerStart
         *  * onWorkerStop
         *  * onTimer
         *  * onConnect
         *  * onReceive
         *  * onClose
         *  * onTask
         *  * onFinish
         *  * onPipeMessage
         *  * onWorkerError
         *  * onManagerStart
         *  * onManagerStop
         *
         *     $http_server->on('request', function(Swoole\Http\Request $request, Http\Response $response) {
         *         $response->end("<h1>hello swoole</h1>");
         *     })
         *
         *
         * 在收到一个完整的Http请求后，会回调此函数。回调函数共有2个参数：
         *
         * * $request，Http请求信息对象，包含了header/get/post/cookie等相关信息
         * * $response，Http响应对象，支持cookie/header/status等Http操作
         *
         *
         * !! $response/$request 对象传递给其他函数时，不要加&引用符号
         *
         * @param string $event
         * @param mixed  $callback
         */
        public function on($event, $callback)
        {
        }

        /**
         * 设置运行时参数
         *
         * Server->set函数用于设置Server运行时的各项参数。服务器启动后通过$serv->setting来访问set函数设置的参数数组。
         *
         * @param array $setting
         */
        public function set(array $setting)
        {
        }

        /**
         * 启动server，监听所有TCP/UDP端口
         *
         * 启动成功后会创建worker_num+2个进程。主进程+Manager进程+worker_num个Worker进程
         *
         * @return bool
         */
        public function start()
        {
        }

        /**
         * 向客户端发送数据
         *
         *  * $data，发送的数据。TCP协议最大不得超过2M，UDP协议不得超过64K
         *  * 发送成功会返回true，如果连接已被关闭或发送失败会返回false
         *
         * TCP服务器
         *
         *  * send操作具有原子性，多个进程同时调用send向同一个连接发送数据，不会发生数据混杂
         *  * 如果要发送超过2M的数据，可以将数据写入临时文件，然后通过sendfile接口进行发送
         *
         * swoole-1.6以上版本不需要$reactorThreadId
         *
         * UDP服务器
         *
         *  * send操作会直接在worker进程内发送数据包，不会再经过主进程转发
         *  * 使用fd保存客户端IP，from_id保存from_fd和port
         *  * 如果在onReceive后立即向客户端发送数据，可以不传$from_id
         *  * 如果向其他UDP客户端发送数据，必须要传入from_id
         *  * 在外网服务中发送超过64K的数据会分成多个传输单元进行发送，如果其中一个单元丢包，会导致整个包被丢弃。所以外网服务，建议发送1.5K以下的数据包
         *
         * @param int    $fd
         * @param string $data
         * @param int    $reactorThreadId
         * @return bool
         */
        public function send($fd, $data, $reactorThreadId = 0)
        {
        }

        /**
         * 向任意的客户端IP:PORT发送UDP数据包
         *
         *  * $ip为IPv4字符串，如192.168.1.102。如果IP不合法会返回错误
         *  * $port为 1-65535的网络端口号，如果端口错误发送会失败
         *  * $data要发送的数据内容，可以是文本或者二进制内容
         *  * $ipv6 是否为IPv6地址，可选参数，默认为false
         *
         * 示例
         *
         *      //向IP地址为220.181.57.216主机的9502端口发送一个hello world字符串。
         *      $server->sendto('220.181.57.216', 9502, "hello world");
         *      //向IPv6服务器发送UDP数据包
         *      $server->sendto('2600:3c00::f03c:91ff:fe73:e98f', 9501, "hello world", true);
         *
         * server必须监听了UDP的端口，才可以使用Server->sendto
         * server必须监听了UDP6的端口，才可以使用Server->sendto向IPv6地址发送数据
         *
         * @param string $ip
         * @param int    $port
         * @param string $data
         * @param bool   $ipv6
         * @return bool
         */
        public function sendto($ip, $port, $data, $ipv6 = false)
        {
        }

        /**
         * 关闭客户端连接
         *
         * !! swoole-1.6以上版本不需要$from_id swoole-1.5.8以下的版本，务必要传入正确的$from_id，否则可能会导致连接泄露
         *
         * 操作成功返回true，失败返回false.
         *
         * Server主动close连接，也一样会触发onClose事件。不要在close之后写清理逻辑。应当放置到onClose回调中处理。
         *
         * @param int $fd
         * @param int $from_id
         * @return bool
         */
        public function close($fd, $from_id = 0)
        {
        }

        /**
         * taskwait与task方法作用相同，用于投递一个异步的任务到task进程池去执行。
         * 与task不同的是taskwait是阻塞等待的，直到任务完成或者超时返回
         *
         * $result为任务执行的结果，由$serv->finish函数发出。如果此任务超时，这里会返回false。
         *
         * taskwait是阻塞接口，如果你的Server是全异步的请使用Server::task和Server::finish,不要使用taskwait
         * 第3个参数可以制定要给投递给哪个task进程，传入ID即可，范围是0 - serv->task_worker_num
         * $dst_worker_id在1.6.11+后可用，默认为随机投递
         * taskwait方法不能在task进程中调用
         *
         * @param mixed $task_data
         * @param float $timeout
         * @param int   $dst_worker_id
         * @return string
         */
        public function taskwait($task_data, $timeout = 0.5, $dst_worker_id = -1)
        {
        }

        /**
         * 并发执行多个Task
         *
         * 在 swoole 1.8.8 版本及以上可用
         *
         *      * $tasks 必须为数组，底层会遍历$tasks将任务逐个投递到Task进程
         *      * $timeout 为浮点型，单位为秒
         *      * 执行成功返回一个结果数据，顺序与传入的$tasks一致
         *      * 失败返回false
         *
         *  ```php
         *  $tasks[] = mt_rand(1000, 9999);
         *  $tasks[] = mt_rand(1000, 9999);
         *  //等待所有Task结果返回，超时为10s
         *  var_dump($tasks);
         *  $results = $serv->taskWaitMulti($tasks, 10.0);
         *  var_dump($results);
         *  ```
         *
         * @since 1.8.8
         * @param array $tasks
         * @param double $timeout
         * @return false|array
         */
        public function taskWaitMulti(array $tasks, $timeout)
        {
            return [];
        }

        /**
         * 投递一个异步任务到task_worker池中。此函数会立即返回。worker进程可以继续处理新的请求
         *
         *  * $data要投递的任务数据，可以为除资源类型之外的任意PHP变量
         *  * $dst_worker_id可以制定要给投递给哪个task进程，传入ID即可，范围是0 - serv->task_worker_num
         *  * 返回值为整数($task_id)，表示此任务的ID。如果有finish回应，onFinish回调中会携带$task_id参数
         *
         * 此功能用于将慢速的任务异步地去执行，比如一个聊天室服务器，可以用它来进行发送广播。当任务完成时，在task进程中调用$serv->finish("finish")告诉worker进程此任务已完成。当然Server->finish是可选的。
         *
         *  * AsyncTask功能在1.6.4版本增加，默认不启动task功能，需要在手工设置task_worker_num来启动此功能
         *  * task_worker的数量在Server::set参数中调整，如task_worker_num => 64，表示启动64个进程来接收异步任务
         *
         *
         * 注意事项
         *
         *  * 使用Server_task必须为Server设置onTask和onFinish回调，否则Server->start会失败
         *  * task操作的次数必须小于onTask处理速度，如果投递容量超过处理能力，task会塞满缓存区，导致worker进程发生阻塞。worker进程将无法接收新的请求
         *
         * @param mixed $data
         * @param int   $dst_worker_id
         * @param callable $callback 在 1.8.6 后支持
         * @return bool|int
         */
        public function task($data, $dst_worker_id = -1, $callback = null)
        {
            return true;
        }

        /**
         * 此函数可以向任意worker进程或者task进程发送消息。在非主进程和管理进程中可调用。收到消息的进程会触发onPipeMessage事件
         *
         *  * $message为发送的消息数据内容
         *  * $dst_worker_id为目标进程的ID，范围是0 ~ (worker_num + task_worker_num - 1)
         *
         * !! 使用sendMessage必须注册onPipeMessage事件回调函数
         *
         *      $serv = new Server("0.0.0.0", 9501);
         *      $serv->set(array(
         *          'worker_num' => 2,
         *          'task_worker_num' => 2,
         *      ));
         *      $serv->on('pipeMessage', function($serv, $src_worker_id, $data) {
         *          echo "#{$serv->worker_id} message from #$src_worker_id: $data\n";
         *      });
         *      $serv->on('task', function ($serv, $task_id, $from_id, $data){
         *          var_dump($task_id, $from_id, $data);
         *      });
         *      $serv->on('finish', function ($serv, $fd, $from_id){
         *
         *      });
         *      $serv->on('receive', function (Server $serv, $fd, $from_id, $data) {
         *          if (trim($data) == 'task')
         *          {
         *              $serv->task("async task coming");
         *          }
         *          else
         *          {
         *              $worker_id = 1 - $serv->worker_id;
         *              $serv->sendMessage("hello task process", $worker_id);
         *          }
         *      });
         *
         *      $serv->start();
         *
         * @param string $message
         * @param int    $dst_worker_id
         * @return bool
         */
        public function sendMessage($message, $dst_worker_id = -1)
        {
            return true;
        }

        /**
         * 此函数用于在task进程中通知worker进程，投递的任务已完成。此函数可以传递结果数据给worker进程
         *
         *      $serv->finish("response");
         *
         * 使用Server::finish函数必须为Server设置onFinish回调函数。此函数只可用于task进程的onTask回调中
         *
         * Server::finish是可选的。如果worker进程不关心任务执行的结果，不需要调用此函数
         * 在onTask回调函数中return字符串，等同于调用finish
         *
         * @param string $task_data
         */
        public function finish($task_data)
        {
        }

        /**
         * 检测服务器所有连接，并找出已经超过约定时间的连接。
         * 如果指定if_close_connection，则自动关闭超时的连接。未指定仅返回连接的fd数组'
         *
         *  * $if_close_connection是否关闭超时的连接，默认为true
         *  * 调用成功将返回一个连续数组，元素是已关闭的$fd。
         *  * 调用失败返回false
         *
         * @param bool $if_close_connection
         * @return array
         */
        public function heartbeat($if_close_connection = true)
        {
        }

        /**
         * 获取连接的信息
         *
         * connection_info可用于UDP服务器，但需要传入from_id参数
         *
         *      array (
         *           'from_id' => 0,
         *           'from_fd' => 12,
         *           'connect_time' => 1392895129,
         *           'last_time' => 1392895137,
         *           'from_port' => 9501,
         *           'remote_port' => 48918,
         *           'remote_ip' => '127.0.0.1',
         *      )
         *
         *  * $udp_client = $serv->connection_info($fd, $from_id);
         *  * var_dump($udp_client);
         *  * from_id 来自哪个reactor线程
         *  * server_fd 来自哪个server socket 这里不是客户端连接的fd
         *  * server_port 来自哪个Server端口
         *  * remote_port 客户端连接的端口
         *  * remote_ip 客户端连接的ip
         *  * connect_time 连接到Server的时间，单位秒
         *  * last_time 最后一次发送数据的时间，单位秒
         *
         * @param int $fd
         * @param int $from_id
         * @return array | bool
         */
        public function connection_info($fd, $from_id = -1)
        {
        }

        /**
         * 用来遍历当前Server所有的客户端连接，connection_list方法是基于共享内存的，不存在IOWait，遍历的速度很快。另外connection_list会返回所有TCP连接，而不仅仅是当前worker进程的TCP连接
         *
         * 示例：
         *
         *      $start_fd = 0;
         *      while(true)
         *      {
         *          $conn_list = $serv->connection_list($start_fd, 10);
         *          if($conn_list===false or count($conn_list) === 0)
         *          {
         *              echo "finish\n";
         *              break;
         *          }
         *          $start_fd = end($conn_list);
         *          var_dump($conn_list);
         *          foreach($conn_list as $fd)
         *          {
         *              $serv->send($fd, "broadcast");
         *          }
         *      }
         *
         * @param int $start_fd
         * @param int $pagesize
         * @return array | bool
         */
        public function connection_list($start_fd = -1, $pagesize = 100)
        {
        }

        /**
         * 重启所有worker进程
         *
         * 一台繁忙的后端服务器随时都在处理请求，如果管理员通过kill进程方式来终止/重启服务器程序，可能导致刚好代码执行到一半终止。 这种情况下会产生数据的不一致。如交易系统中，支付逻辑的下一段是发货，假设在支付逻辑之后进程被终止了。会导致用户支付了货币，但并没有发货，后果非常严重。
         *
         * Swoole提供了柔性终止/重启的机制，管理员只需要向SwooleServer发送特定的信号，Server的worker进程可以安全的结束。
         *
         *  * SIGTERM: 向主进程发送此信号服务器将安全终止
         *  * 在PHP代码中可以调用$serv->shutdown()完成此操作
         *  * SIGUSR1: 向管理进程发送SIGUSR1信号，将平稳地restart所有worker进程
         *  * 在PHP代码中可以调用$serv->reload()完成此操作
         *  * swoole的reload有保护机制，当一次reload正在进行时，收到新的重启信号会丢弃
         *
         *      #重启所有worker进程
         *      kill -USR1 主进程PID
         *
         * 仅重启task_worker的功能。只需向服务器发送SIGUSR2即可。
         *
         * #仅重启task进程
         * kill -USR2 主进程PID
         * 平滑重启只对onWorkerStart或onReceive等在Worker进程中include/require的PHP文件有效，Server启动前就已经include/require的PHP文件，不能通过平滑重启重新加载
         * 对于Server的配置即$serv->set()中传入的参数设置，必须关闭/重启整个Server才可以重新加载
         * Server可以监听一个内网端口，然后可以接收远程的控制命令，去重启所有worker
         *
         * @param bool $only_reload_taskworkrer 是否仅重启task进程
         * @return bool
         */
        public function reload($only_reload_taskworkrer = false)
        {
        }

        /**
         * 使当前worker进程停止运行，并立即触发onWorkerStop回调函数。
         *
         *  * 使用此函数代替exit/die结束Worker进程的生命周期
         *  * 如果要结束其他Worker进程，可以使用Process::kill($worker_pid)
         *
         * @since 1.8.2
         */
        public function stop()
        {

        }

        /**
         * 关闭服务器
         *
         * 此函数可以用在worker进程内。向主进程发送SIGTERM也可以实现关闭服务器。
         *
         * kill -15 主进程PID
         *
         * @return bool
         */
        public function shutdown()
        {
        }

        /**
         * Swoole提供了Server::addListener来增加监听的端口。业务代码中可以通过调用Server::connection_info来获取某个连接来自于哪个端口
         *
         * * SWOOLE_TCP/SWOOLE_SOCK_TCP tcp ipv4 socket
         * * SWOOLE_TCP6/SWOOLE_SOCK_TCP6 tcp ipv6 socket
         * * SWOOLE_UDP/SWOOLE_SOCK_UDP udp ipv4 socket
         * * SWOOLE_UDP6/SWOOLE_SOCK_UDP6 udp ipv6 socket
         * * SWOOLE_UNIX_DGRAM unix socket dgram
         * * SWOOLE_UNIX_STREAM unix socket stream
         *
         *
         * 可以混合使用UDP/TCP，同时监听内网和外网端口。 示例：
         *
         *      $serv->addlistener("127.0.0.1", 9502, SWOOLE_SOCK_TCP);
         *      $serv->addlistener("192.168.1.100", 9503, SWOOLE_SOCK_TCP);
         *      $serv->addlistener("0.0.0.0", 9504, SWOOLE_SOCK_UDP);
         *      $serv->addlistener("/var/run/myserv.sock", 0, SWOOLE_UNIX_STREAM);
         *
         * @param string $host
         * @param int    $port
         * @param int    $type
         */
        public function addlistener($host, $port, $type = SWOOLE_SOCK_TCP)
        {
        }

        /**
         * 得到当前Server的活动TCP连接数，启动时间，accpet/close的总次数等信息
         *
         *      array (
         *        'start_time' => 1409831644,
         *        'connection_num' => 1,
         *        'accept_count' => 1,
         *        'close_count' => 0,
         *      );
         *
         *  * start_time 服务器启动的时间
         *  * connection_num 当前连接的数量
         *  * accept_count 接受了多少个连接
         *  * close_count 关闭的连接数量
         *  * tasking_num 当前正在排队的任务数
         *
         * @return array
         */
        function stats()
        {
        }

        /**
         * 在指定的时间后执行函数
         *
         * Server::after函数是一个一次性定时器，执行完成后就会销毁。
         *
         * $after_time_ms 指定时间，单位为毫秒
         * $callback_function 时间到期后所执行的函数，必须是可以调用的。callback函数不接受任何参数
         * $after_time_ms 最大不得超过 86400000
         * 此方法是swoole_timer_after函数的别名
         *
         * @param       $ms
         * @param int   $after_time_ms
         * @param mixed $callback_function
         * @param mixed $param
         */
        public function after($after_time_ms, $callback_function, $param = null)
        {
        }

        /*
         * 增加监听端口，addlistener的别名
         *
         * @param $host
         * @param $port
         * @param $type
         * @return  Server\Port
         */
        public function listen($host, $port, $type)
        {
            return new Server\Port();
        }

        /**
         *
         * 添加一个用户自定义的工作进程
         *
         *  * $process 为Process对象，注意不需要执行start。在Server启动时会自动创建进程，并执行指定的子进程函数
         *  * 创建的子进程可以调用$server对象提供的各个方法，如connection_list/connection_info/stats
         *  * 在worker进程中可以调用$process提供的方法与子进程进行通信
         *  * 此函数通常用于创建一个特殊的工作进程，用于监控、上报或者其他特殊的任务。
         *
         * 子进程会托管到Manager进程，如果发生致命错误，manager进程会重新创建一个
         *
         * @param Process $process
         */
        public function addProcess(Process $process)
        {
        }

        /**
         * 设置定时器。1.6.12版本前此函数不能用在消息队列模式下，1.6.12后消息队列IPC模式也可以使用定时器
         *
         * 第二个参数是定时器的间隔时间，单位为毫秒。swoole定时器的最小颗粒是1毫秒。支持多个定时器。此函数可以用于worker进程中。
         *
         *  * swoole1.6.5之前支持的单位是秒，所以1.6.5之前传入的参数为1，那在1.6.5后需要传入1000
         *  * swoole1.6.5之后，addtimer必须在onStart/onWorkerStart/onConnect/onReceive/onClose等回调函数中才可以使用，否则会抛出错误。并且定时器无效
         *  * 注意不能存在2个相同间隔时间的定时器
         *  * 即使在代码中多次添加一个定时器，也只会有1个生效
         *
         *
         *  增加定时器后需要为Server设置onTimer回调函数，否则Server将无法启动。多个定时器都会回调此函数。在这个函数内需要自行switch，根据interval的值来判断是来自于哪个定时器。
         *
         *      // 面向对象风格
         *      $serv->addtimer(1000); //1s
         *      $serv->addtimer(20); //20ms
         *
         * @param int $interval
         * @return bool
         */
        public function addtimer($interval)
        {
        }

        /**
         * 删除定时器
         *
         * @param $interval
         */
        public function deltimer($interval)
        {
        }

        /**
         * 增加tick定时器
         *
         * 可以自定义回调函数。此函数是swoole_timer_tick的别名
         *
         * worker进程结束运行后，所有定时器都会自动销毁
         *
         * 设置一个间隔时钟定时器，与after定时器不同的是tick定时器会持续触发，直到调用swoole_timer_clear清除。与swoole_timer_add不同的是tick定时器可以存在多个相同间隔时间的定时器。
         *
         * @param int   $ms
         * @param mixed $callback
         * @param mixed $param
         * @return int
         */
        public function tick($interval_ms, $callback, $param = null)
        {
        }

        /**
         * 删除设定的定时器，此定时器不会再触发
         *
         * @param $id
         */
        function clearAfter($id)
        {
        }

        /**
         * 设置Server的事件回调函数
         *
         * 第一个参数是swoole的资源对象
         * 第二个参数是回调的名称, 大小写不敏感，具体内容参考回调函数列表
         * 第三个函数是回调的PHP函数，可以是字符串，数组，匿名函数。比如
         * handler/on/set 方法只能在Server::start前调用
         *
         *
         *      $serv->handler('onStart', 'my_onStart');
         *      $serv->handler('onStart', array($this, 'my_onStart'));
         *      $serv->handler('onStart', 'myClass::onStart');
         *
         * @param string $event_name
         * @param mixed  $event_callback_function
         * @return bool
         */
        public function handler($event_name, $event_callback_function)
        {
        }

        /**
         * 发送文件到TCP客户端连接
         *
         * endfile函数调用OS提供的sendfile系统调用，由操作系统直接读取文件并写入socket。sendfile只有2次内存拷贝，使用此函数可以降低发送大量文件时操作系统的CPU和内存占用。
         *
         * $filename 要发送的文件路径，如果文件不存在会返回false
         * 操作成功返回true，失败返回false
         * 此函数与Server->send都是向客户端发送数据，不同的是sendfile的数据来自于指定的文件。
         *
         * @param int    $fd
         * @param string $filename 文件绝对路径
         * @param int    $offset
         * @return bool
         */
        public function sendfile($fd, $filename, $offset = null)
        {
        }

        /**
         * 将连接绑定一个用户定义的ID，可以设置dispatch_mode=5设置已此ID值进行hash固定分配。可以保证某一个UID的连接全部会分配到同一个Worker进程
         *
         * 在默认的dispatch_mode=2设置下，server会按照socket fd来分配连接数据到不同的worker。
         * 因为fd是不稳定的，一个客户端断开后重新连接，fd会发生改变。这样这个客户端的数据就会被分配到别的Worker。
         * 使用bind之后就可以按照用户定义的ID进行分配。即使断线重连，相同uid的TCP连接数据会被分配相同的Worker进程。
         *
         * * $fd 连接的文件描述符
         * * $uid 指定UID
         *
         * 同一个连接只能被bind一次，如果已经绑定了uid，再次调用bind会返回false
         * 可以使用$serv->connection_info($fd) 查看连接所绑定uid的值
         *
         * @param int $fd
         * @param int $uid
         * @return bool
         */
        public function bind($fd, $uid)
        {
        }
    }


    /**
     * Class Lock
     */
    class Lock
    {

        /**
         * @param int    $type     为锁的类型
         * @param string $lockfile 当类型为SWOOLE_FILELOCK时必须传入，指定文件锁的路径
         *                         注意每一种类型的锁支持的方法都不一样。如读写锁、文件锁可以支持 $lock->lock_read()。
         *                         另外除文件锁外，其他类型的锁必须在父进程内创建，这样fork出的子进程之间才可以互相争抢锁。
         */
        public function __construct($type, $lockfile = null)
        {
        }

        /**
         * 加锁操作
         *
         * 如果有其他进程持有锁，那这里将进入阻塞，直到持有锁的进程unlock。
         */
        public function lock()
        {
        }

        /**
         * 加锁操作
         *
         * 与lock方法不同的是，trylock()不会阻塞，它会立即返回。
         * 当返回false时表示抢锁失败，有其他进程持有锁。返回true时表示加锁成功，此时可以修改共享变量。
         *
         * SWOOlE_SEM 信号量没有trylock方法
         */
        public function trylock()
        {
        }

        /**
         * 释放锁
         */
        public function unlock()
        {
        }

        /**
         * 阻塞加锁
         *
         * lock_read方法仅可用在读写锁(SWOOLE_RWLOCK)和文件锁(SWOOLE_FILELOCK)中，表示仅仅锁定读。
         * 在持有读锁的过程中，其他进程依然可以获得读锁，可以继续发生读操作。但不能$lock->lock()或$lock->trylock()，这两个方法是获取独占锁的。
         *
         * 当另外一个进程获得了独占锁(调用$lock->lock/$lock->trylock)时，$lock->lock_read()会发生阻塞，直到持有锁的进程释放。
         */
        public function lock_read()
        {
        }

        /**
         * 非阻塞加锁
         *
         * 此方法与lock_read相同，但是非阻塞的。调用会立即返回，必须检测返回值以确定是否拿到了锁。
         */
        public function trylock_read()
        {
        }
    }


    /**
     * swoole进程管理类
     * 内置IPC通信支持，子进程和主进程之间可以方便的通信
     * 支持标准输入输出重定向，子进程内echo，会发送到管道中，而不是输出屏幕
     * Class Process
     */
    class Process
    {
        /**
         * 进程的PID
         *
         * @var int
         */
        public $pid;

        /**
         * 管道PIPE
         *
         * @var int
         */
        public $pipe;

        /**
         * @param mixed $callback              子进程的回调函数
         * @param bool  $redirect_stdin_stdout 是否重定向标准输入输出
         * @param bool  $create_pipe           是否创建管道
         */
        function __construct($callback, $redirect_stdin_stdout = false, $create_pipe = true)
        {
        }

        /**
         * 向管道内写入数据
         *
         * @param string $data
         * @return int
         */
        function write($data)
        {
        }

        /**
         * 从管道内读取数据
         *
         * @param int $buffer_len 最大读取的长度
         * @return string
         */
        function read($buffer_len = 8192)
        {
        }

        /**
         * 退出子进程，实际函数名为exit，IDE将exit识别为关键词了，会有语法错误，所以这里叫_exit
         *
         * @param int $code
         * @return int
         */
        function _exit($code = 0)
        {
        }

        /**
         * 执行另外的一个程序
         *
         * @param string $execute_file 可执行文件的路径
         * @param array  $params       参数数组
         * @return bool
         */
        function exec($execute_file, $params)
        {
        }

        /**
         * 用于关闭创建的好的管道
         *
         * @return bool
         */
        function close()
        {
            return true;
        }

        /**
         * 阻塞等待子进程退出，并回收
         * 成功返回一个数组包含子进程的PID和退出状态码
         * 如array('code' => 0, 'pid' => 15001)，失败返回false
         *
         * @param bool $blocking
         * @return false | array
         */
        static function wait($blocking = true)
        {
        }

        /**
         * 守护进程化
         *
         * @param bool $nochdir
         * @param bool $noclose
         */
        static function daemon($nochdir = false, $noclose = false)
        {

        }

        /**
         * 创建消息队列
         *
         * @param int $msgkey 消息队列KEY
         * @param int $mode   模式
         */
        function useQueue($msgkey = -1, $mode = 2)
        {

        }

        /**
         * 向消息队列推送数据
         *
         * @param $data
         * @return bool
         */
        function push($data)
        {

        }

        /**
         * 从消息队列中提取数据
         *
         * @param int $maxsize
         * @return string
         */
        function pop($maxsize = 8192)
        {

        }

        /**
         * 退出子进程
         *
         * 在子进程中执行, 不可在主进程里执行, 主进程请用 kill 方法
         *
         * @param int $status
         */
        public function exit($status = 0)
        {

        }

        /**
         * 向某个进程发送信号
         *
         * @param     $pid
         * @param int $sig
         * @return bool
         */
        static function kill($pid, $sig = SIGTERM)
        {
        }

        /**
         * 注册信号处理函数
         * require swoole 1.7.9+
         *
         * @param int   $signo
         * @param mixed $callback
         */
        static function signal($signo, $callback)
        {
        }

        /**
         * 启动子进程
         *
         * @return int
         */
        function start()
        {
        }

        /**
         * 为工作进程重命名
         *
         * @param $process_name
         */
        function name($process_name)
        {

        }

        /**
         * 查看消息队列状态
         *
         * 返回一个数组，包括2项信息, 1.8.6 后提供
         *
         *  * queue_num 队列中的任务数量
         *  * queue_bytes 队列数据的总字节数
         *
         * @since 1.8.6
         * @return array
         */
        function statQueue()
        {

        }

        /**
         * 删除队列
         *
         * 此方法与useQueue成对使用，useQueue创建队列，使用freeQueue销毁队列。销毁队列后队列中的数据会被清空
         *
         * @since 1.8.6
         */
        function freeQueue()
        {

        }
    }


    /**
     * Class Swoole\Buffer
     *
     * 内存操作
     */
    class Buffer
    {

        /**
         * @param int $size
         */
        function __construct($size = 128)
        {
        }

        /**
         * 将一个字符串数据追加到缓存区末尾
         *
         * @param string $data
         * @return int
         */
        function append($data)
        {
        }

        /**
         * 从缓冲区中取出内容
         *
         * substr会复制一次内存
         * remove后内存并没有释放，只是底层进行了指针偏移。当销毁此对象时才会真正释放内存
         *
         * @param int  $offset 表示偏移量，如果为负数，表示倒数计算偏移量
         * @param int  $length 表示读取数据的长度，默认为从$offset到整个缓存区末尾
         * @param bool $remove 表示从缓冲区的头部将此数据移除。只有$offset = 0时此参数才有效
         */
        function substr($offset, $length = -1, $remove = false)
        {
        }

        /**
         * 清理缓存区数据
         * 执行此操作后，缓存区将重置。Swoole\Buffer对象就可以用来处理新的请求了。
         * Swoole\Buffer基于指针运算实现clear，并不会写内存
         */
        function clear()
        {
        }

        /**
         * 为缓存区扩容
         *
         * @param int $new_size 指定新的缓冲区尺寸，必须大于当前的尺寸
         */
        function expand($new_size)
        {
        }

        /**
         * 向缓存区的任意内存位置写数据
         * 此函数可以直接写内存。所以使用务必要谨慎，否则可能会破坏现有数据
         *
         * $data不能超过缓存区的最大尺寸。
         * write方法不会自动扩容
         *
         * @param int    $offset 偏移量
         * @param string $data   写入的数据
         */
        function write($offset, $data)
        {
        }

        /**
         * 读取缓存区任意位置的内存。此接口是一个底层接口，可直接操作内存。
         *
         *  * $offset 偏移量
         *  * $length 要读取的数据长度
         *  * 如果offset错误或读取的长度超过实际数据的长度，这里会返回false
         *
         * @param $offset
         * @param $length
         * @return string
         */
        function read($offset, $length)
        {
        }

    }


    /**
     * 创建内存表
     */
    class Table extends \ArrayIterator
    {
        const TYPE_INT    = 1;
        const TYPE_STRING = 2;
        const TYPE_FLOAT  = 3;

        /**
         * 内存表
         *
         * @param int $size
         */
        public function __construct($size)
        {
        }

        /**
         * 获取key
         *
         * @param $key
         * @return array
         */
        function get($key)
        {
        }

        /**
         * 设置key
         *
         * @param       $key
         * @param array $array
         * @return bool
         */
        function set($key, $array)
        {
        }

        /**
         * 删除key
         *
         * @param $key
         * @return bool
         */
        function del($key)
        {
        }

        /**
         * 原子自增操作，可用于整形或浮点型列
         *
         * @param $key
         * @param $column
         * @param $incrby
         * @return bool|int
         */
        function incr($key, $column, $incrby = 1)
        {
        }

        /**
         * 原子自减操作，可用于整形或浮点型列
         *
         * @param $key
         * @param $column
         * @param $decrby
         * @return bool|int
         */
        function decr($key, $column, $decrby = 1)
        {
        }

        /**
         * 增加字段定义
         *
         * @param     $name
         * @param     $type
         * @param int $len
         * @return bool
         */
        function column($name, $type = null, $size = 4)
        {
        }

        /**
         * 检查table中是否存在某一个key
         *
         * @param $key
         * @return bool
         */
        function exist($key)
        {
            return true;
        }

        /**
         * 创建表，这里会申请操作系统内存
         *
         * @return bool
         */
        function create()
        {
        }

        /**
         * 锁定整个表
         *
         * @return bool
         */
        function lock()
        {
        }

        /**
         * 释放表锁
         *
         * @return bool
         */
        function unlock()
        {
        }
    }


    class Atomic
    {

        /**
         * Swoole\Atomic constructor.
         *
         * @param int $init_value
         */
        public function __construct($init_value = 0)
        {
        }

        /**
         * @param int $add_value
         * @return int
         */
        public function add($add_value = 1)
        {
        }

        /**
         * @param int $sub_value
         * @return int
         */
        public function sub($sub_value = 1)
        {
        }

        /**
         * @param int $value
         * @return bool
         */
        public function set($value = 1)
        {
        }

        /**
         * @param int $sub_value
         * @return int
         */
        public function get()
        {
        }

        /**
         * 如果当前数值等于参数1，则将当前数值设置为参数2
         *
         *   * 如果当前数值等于$cmp_value返回true，并将当前数值设置为$set_value
         *   * 如果不等于返回false
         *   * $cmp_value，$set_value 必须为小于42亿的整数
         *
         * @param $cmp_value
         * @param $set_value
         * @return bool
         */
        public function cmpset($cmp_value, $set_value)
        {
        }
    }

    class MySQL
    {

        /**
         * MySQL constructor.
         */
        public function __construct()
        {
        }

        /**
         * 异步连接到MySQL服务器
         *
         * * host MySQL服务器的主机地址，支持IPv6（::1）和UnixSocket（unix:/tmp/mysql.sock）
         * * port MySQL服务器监听的端口，选填默认为3306
         * * user 用户名，必填
         * * password 密码，必填
         * * database 连接的数据库，必填
         *
         * @param array    $serverConfig
         * @param callable $callback
         */
        public function connect(array $serverConfig, $callback)
        {

        }

        /**
         * 设置事件回调函数
         *
         * @param          $event_name
         * @param callable $callback
         */
        public function on($event_name, callable $callback)
        {

        }

        /**
         * 执行SQL查询
         *
         * $sql为要执行的SQL语句
         * $callback执行成功后会回调此函数
         * 每个MySQLi连接只能同时执行一条SQL，必须等待返回结果后才能执行下一条SQL
         *
         * @param          $sql
         * @param callable $callback
         * @return false|array
         */
        public function query($sql, callable $callback)
        {

        }

        public function close()
        {}
    }

    class Redis extends \Redis
    {
        function __call($name, $arguments)
        {
        }

        /**
         * 注册事件回调函数
         *
         * @param string   $event_name
         * @param callable $callback
         */
        public function on($event_name, $callback)
        {

        }

        /**
         * 连接到Redis服务器
         *
         * @param string   $host
         * @param int      $port
         * @param callable $callback
         */
        public function connect($host, $port, $callback)
        {

        }

        public function close()
        {

        }
    }
}

namespace Swoole\Redis
{

    /**
     * 内置Web服务器
     * Class Http\Server
     */
    class Server extends \Swoole\Server
    {
        const NIL = null;

        const ERROR = 0;

        const STATUS = 0;

        const INT = 0;

        const STRING = 'string';

        const SET = 'set';

        const MAP = 'map';

        /**
         * 设置Redis命令字的处理器
         *
         * @param string   $command  命令的名称
         * @param callable $callback 命令的处理函数，回调函数返回字符串类型时会自动发送给客户端
         * @return mixed 返回的数据必须为Redis格式，可使用format静态方法进行打包
         */
        function setHandler($command, callable $callback)
        {
            return;
        }

        /**
         * 格式化命令响应数据
         *
         * $type表示数据类型，NIL类型不需要传入$value，ERROR和STATUS类型$value可选，INT、STRING、SET、MAP必选
         *
         * @param int        $type  数据类型
         * @param mixed|null $value
         * @return mixed
         */
        public static function format($type, $value = null)
        {
            return;
        }
    }
}

namespace Swoole\Http
{

    /**
     * 内置Web服务器
     * Class Http\Server
     */
    class Server extends \Swoole\Server
    {
        /**
         * 启用数据合并，HTTP请求数据到PHP的GET/POST/COOKIE全局数组
         *
         * @param     $flag
         * @param int $request_flag
         */
        function setGlobal($flag, $request_flag = 0)
        {
        }
    }
}

namespace Swoole\Websocket
{

    class Server extends \Swoole\Http\Server
    {
        /**
         * 向某个WebSocket客户端连接推送数据
         *
         * @param      $fd
         * @param      $data
         * @param bool $binary_data
         * @param bool $finish
         */
        function push($fd, $data, $binary_data = false, $finish = true)
        {
        }
    }


    class Frame
    {
        /**
         * 客户端的socket id，使用$server->push推送数据时需要用到
         *
         * @var int
         */
        public $fd;

        /**
         * 数据内容，可以是文本内容也可以是二进制数据，可以通过opcode的值来判断
         *
         * @var string
         */
        public $data;

        /**
         * WebSocket的OpCode类型，可以参考WebSocket协议标准文档
         *
         * @var int
         */
        public $opcode;

        /**
         * 表示数据帧是否完整，一个WebSocket请求可能会分成多个数据帧进行发送
         *
         * @var bool
         */
        public $finish;
    }
}

namespace Swoole\Http
{

    /**
     * Http请求对象
     * Class Swoole\Http\Request
     */
    class Request
    {
        public $get;
        public $post;
        public $header;
        public $server;
        public $cookie;

        /**
         * 文件列表
         *
         * @var array
         */
        public $files;

        /**
         * 原始请求的数据
         *
         * @var string
         */
        public $data;

        public $fd;

        /**
         * 获取非urlencode-form表单的POST原始数据
         *
         * @return string
         */
        function rawContent()
        {
        }
    }


    /**
     * Http响应对象
     * Class Http\Response
     */
    class Response
    {
        /**
         * @var int
         */
        public $fd = 0;

        /**
         * @var array
         */
        public $header = [];
        /**
         * 结束Http响应，发送HTML内容
         *
         * @param string $html
         */
        public function end($html = '')
        {
        }

        /**
         * 启用Http-Chunk分段向浏览器发送数据
         *
         * @param $html
         */
        public function write($html)
        {
        }

        /**
         * 发送文件到浏览器
         *
         *  * $filename 要发送的文件名称，文件不存在或没有访问权限sendfile会失败
         *  * 底层无法推断要发送文件的MIME格式因此需要应用代码指定Content-Type
         *  * 调用sendfile后会自定执行end，中途不得使用Http-Chunk
         *  * sendfile不支持gzip压缩
         *
         * @param $filename
         * @return bool
         */
        public function sendfile($filename)
        {
        }

        /**
         * 设置Http头信息
         *
         * @param $key
         * @param $value
         */
        public function header($key, $value)
        {
        }

        /**
         * 设置Cookie
         *
         * @param string $key
         * @param string $value
         * @param int    $expire
         * @param string $path
         * @param string $domain
         * @param bool   $secure
         * @param bool   $httponly
         */
        public function cookie($key, $value, $expire = 0, $path = '/', $domain = '', $secure = false, $httponly = false)
        {
        }

        /**
         * 设置HttpCode，如404, 501, 200
         *
         * @param $code
         */
        public function status($code)
        {

        }

        /**
         * 设置Http压缩格式
         *
         * @param int $level
         */
        function gzip($level = 1)
        {

        }
    }
}

namespace Swoole\Server
{

    class Port
    {

        /**
         * 监听端口，可以设置部分回调函数。
         *
         *  TCP服务器
         *
         *  * onConnect
         *  * onClose
         *  * onReceive
         *
         *  UDP服务器
         *
         *  * onPacket
         *  * onReceive
         *
         * @param string $event
         * @param mixed  $callback
         */
        public function on($event, $callback)
        {
        }

        /**
         * 设置运行时参数
         *
         * swoole_server->set函数用于设置swoole_server运行时的各项参数。服务器启动后通过$serv->setting来访问set函数设置的参数数组。
         *
         * @param array $setting
         */
        public function set(array $setting)
        {
        }
    }
}


namespace Swoole\Coroutine
{
    class Client extends \Swoole\Client
    {
        /**
         * @return bool
         */
        public function getDefer()
        {
            return true;
        }

        /**
         * * $is_defer：bool值，为true时，表明该Client要延迟收包，为false时，表明该Client非延迟收包，默认值为true
         * * 返回值：设置成功返回true，否则返回false。只有一种情况会返回false，当设置defer(true)并发包后，尚未recv()收包，就设置defer(false)，此时返回false。
         * * 如果需要进行延迟收包，需要在发包之前调用
         *
         * @param bool $is_defer
         * @return bool
         */
        public function setDefer($is_defer = true)
        {
            return true;
        }

        /**
         * 连接到远程服务器
         *
         *  * $host是远程服务器的地址
         *  * $port是远程服务器端口
         *  * $timeout是网络IO的超时，包括connect/send/recv，单位是s，支持浮点数。默认为0.1s，即100ms，超时发生时，连接会被自动close掉
         *  * $flag参数在UDP类型时表示是否启用udp_connect
         *  * 设定此选项后将绑定$host与$port，此UDP将会丢弃非指定host/port的数据包。
         *  * $flag参数在TCP类型,$flag=1表示设置为非阻塞socket，connect会立即返回。如果将$flag设置为1，那么在send/recv前必须使用swoole_client_select来检测是否完成了连接
         *
         * !! 原先异步客户端不支持recv超时，现在协程版已经支持超时，复用上面的$timeout参数
         *
         * connect不会发生阻塞，connect事件触发后，切回PHP上下文。
         *
         *
         *     if ($cli->connect('127.0.0.1', 9501)) {
         *         $cli->send("data");
         *     } else {
         *         echo "connect failed.";
         *     }
         *
         * 如果连接失败，会返回false
         *
         * !! 超时后返回errCode 110
         *
         * @param string $host
         * @param int    $port
         * @param float  $timeout
         * @param int    $flag
         * @return bool
         */
        public function connect($host, $port, $timeout = 0.1, $flag = 0)
        {
            return true;
        }
    }


    class MySQL
    {
        /**
         * 连接信息，保存的是传递给构造函数的数组
         *
         * @var array
         */
        public $serverInfo;

        /**
         * 连接使用的文件描述符
         *
         * @var resource
         */
        public $sock;

        /**
         * 是否连接上了MySQL服务器
         *
         * @var bool
         */
        public $connected;

        /**
         * 发生在sock上的连接错误信息
         *
         * @var string
         */
        public $connect_error = '';

        /**
         * 发生在sock上的连接错误号
         *
         * @var int
         */
        public $connect_errno = 0;

        /**
         * MySQL服务器返回的错误信息
         *
         * @var string
         */
        public $error = '';

        /**
         * MySQL服务器返回的错误号
         *
         * @var int
         */
        public $errno = 0;

        /**
         * 影响的行数
         *
         * @var int
         */
        public $affected_rows = 0;

        /**
         * 最后一个插入的记录id
         *
         * @var int
         */
        public $insert_id = 0;

        public function __construct()
        {
        }

        /**
         * 建立MySQL连接
         *
         * $serverInfo：参数以数组形式传递
         *
         *     [
         *         'host' => 'MySQL IP地址',
         *         'user' => '数据用户',
         *         'password' => '数据库密码',
         *         'database' => '数据库名',
         *         'timeout' => '建立连接超时时间',
         *         'charset' => '字符集'
         *     ]
         *
         * @param array $serverInfo
         * @return bool 连接建立成功返回true，否则返回false
         */
        public function connect(array $serverInfo)
        {
            return true;
        }

        /**
         * @param string $sql SQL语句
         * @param float  $timeout 超时时间，超时的话会断开MySQL连接，0表示不设置超时时间。
         * @return false|array 超时/出错返回false，否则以数组形式返回查询结果
         */
        public function query($sql, $timeout = 0)
        {
            return [];
        }

        /**
         * @return bool
         */
        public function getDefer()
        {
            return true;
        }

        /**
         * * $is_defer：bool值，为true时，表明该Client要延迟收包，为false时，表明该Client非延迟收包，默认值为true
         * * 返回值：设置成功返回true，否则返回false。只有一种情况会返回false，当设置defer(true)并发包后，尚未recv()收包，就设置defer(false)，此时返回false。
         * * 如果需要进行延迟收包，需要在发包之前调用
         *
         * @param bool $is_defer
         * @return bool
         */
        public function setDefer($is_defer = true)
        {
            return true;
        }
    }
}

namespace Swoole\Coroutine\Http
{

    class Client extends \Swoole\Http\Client
    {
        /**
         *
         * * $ip 目标服务器的IP地址，可使用swoole_async_dns_lookup查询域名对应的IP地址
         * * $port 目标服务器的端口，一般http为80，https为443
         * * $ssl 是否启用SSL/TLS隧道加密，如果目标服务器是https必须设置$ssl参数为true
         *
         *
         * @param string $ip
         * @param int    $port
         * @param bool   $ssl
         */
        public function __construct($ip, $port, $ssl = false)
        {

        }

        /**
         * @return bool
         */
        public function getDefer()
        {
            return true;
        }

        /**
         * * $is_defer：bool值，为true时，表明该Client要延迟收包，为false时，表明该Client非延迟收包，默认值为true
         * * 返回值：设置成功返回true，否则返回false。只有一种情况会返回false，当设置defer(true)并发包后，尚未recv()收包，就设置defer(false)，此时返回false。
         * * 如果需要进行延迟收包，需要在发包之前调用
         *
         * @param bool $is_defer
         * @return bool
         */
        public function setDefer($is_defer = true)
        {
            return true;
        }

        /**
         * 返回值：获取延迟收包的结果，当没有进行延迟收包或者收包超时，返回false。
         *
         * @return mixed
         */
        public function recv()
        {

        }

        /**
         * 发起GET请求
         *
         * * $path 设置URL路径，如/index.html，注意这里不能传入http://domain
         * * 使用get会忽略setMethod设置的请求方法，强制使用GET
         *
         *     $cli = new Swoole\Coroutine\Http\Client('127.0.0.1', 80);
         *     $cli->setHeaders([
         *         'Host' => "localhost",
         *         "User-Agent" => 'Chrome/49.0.2587.3',
         *         'Accept' => 'text/html,application/xhtml+xml,application/xml',
         *         'Accept-Encoding' => 'gzip',
         *     ]);
         *
         *     $cli->get('/index.php');
         *     echo $cli->body;
         *     $cli->close();
         *
         * @param string $path
         */
        public function get($path)
        {

        }

        /**
         * 发起POST请求
         *
         * * $path 设置URL路径，如/index.html，注意这里不能传入http://domain
         * * $data 请求的包体数据，如果$data为数组底层自动会打包为x-www-form-urlencoded格式的POST内容，并设置Content-Type为application/x-www-form-urlencoded
         * * 使用post会忽略setMethod设置的请求方法，强制使用POST
         *
         * @param string $path
         * @param mixed  $data
         */
        public function post($path, $data)
        {

        }
    }
}