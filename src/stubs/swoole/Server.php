<?php

namespace Swoole;
/**
 * Class swoole_server
 *
 * Author: wudi <wudi23@baidu.com>
 * Date: 2016/02/17
 */
class Server
{
    /**
     * 当前服务器管理进程的Settings
     *
     * swoole_server::set()函数所设置的参数会保存到$setting属性上。
     * 在回调函数中可以访问运行参数的值。
     *
     * swoole-1.6.11+可用
     *
     * @var array
     */
    public $setting;

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
     * TCP连接迭代器，可以使用foreach遍历服务器当前所有的连接，此属性的功能与swoole_server->connnection_list是一致的，但是更加友好。遍历的元素为单个连接的fd
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
     * swoole_server构造函数
     * @param     $host
     * @param     $port
     * @param int $mode
     * @param int $sock_type
     */
    function __construct($host, $port, $mode = SWOOLE_PROCESS, $sock_type = SWOOLE_SOCK_TCP)
    {
    }

    /**
     * 注册事件回调函数，与swoole_server->on相同。swoole_http_server->on的不同之处是：
     *
     * * swoole_http_server->on不接受onConnect/onReceive回调设置
     * * swoole_http_server->on 额外接受1种新的事件类型onRequest
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
     *     $http_server->on('request', function(swoole_http_request $request, swoole_http_response $response) {
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
     * @param callable $callback
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
     * swoole-1.6以上版本不需要$from_id
     *
     * UDP服务器
     *
     *  * send操作会直接在worker进程内发送数据包，不会再经过主进程转发
     *  * 使用fd保存客户端IP，from_id保存from_fd和port
     *  * 如果在onReceive后立即向客户端发送数据，可以不传$from_id
     *  * 如果向其他UDP客户端发送数据，必须要传入from_id
     *  * 在外网服务中发送超过64K的数据会分成多个传输单元进行发送，如果其中一个单元丢包，会导致整个包被丢弃。所以外网服务，建议发送1.5K以下的数据包
     *
     * @param int $fd
     * @param string $data
     * @param int $from_id
     * @return bool
     */
    public function send($fd, $data, $from_id = 0)
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
     * server必须监听了UDP的端口，才可以使用swoole_server->sendto
     * server必须监听了UDP6的端口，才可以使用swoole_server->sendto向IPv6地址发送数据
     *
     * @param string $ip
     * @param int $port
     * @param string $data
     * @param bool $ipv6
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
     * taskwait是阻塞接口，如果你的Server是全异步的请使用swoole_server::task和swoole_server::finish,不要使用taskwait
     * 第3个参数可以制定要给投递给哪个task进程，传入ID即可，范围是0 - serv->task_worker_num
     * $dst_worker_id在1.6.11+后可用，默认为随机投递
     * taskwait方法不能在task进程中调用
     *
     * @param mixed $task_data
     * @param float $timeout
     * @param int $dst_worker_id
     * @return string
     */
    public function taskwait($task_data, $timeout = 0.5, $dst_worker_id = -1)
    {
    }

    /**
     * 投递一个异步任务到task_worker池中。此函数会立即返回。worker进程可以继续处理新的请求
     *
     *  * $data要投递的任务数据，可以为除资源类型之外的任意PHP变量
     *  * $dst_worker_id可以制定要给投递给哪个task进程，传入ID即可，范围是0 - serv->task_worker_num
     *  * 返回值为整数($task_id)，表示此任务的ID。如果有finish回应，onFinish回调中会携带$task_id参数
     *
     * 此功能用于将慢速的任务异步地去执行，比如一个聊天室服务器，可以用它来进行发送广播。当任务完成时，在task进程中调用$serv->finish("finish")告诉worker进程此任务已完成。当然swoole_server->finish是可选的。
     *
     *  * AsyncTask功能在1.6.4版本增加，默认不启动task功能，需要在手工设置task_worker_num来启动此功能
     *  * task_worker的数量在swoole_server::set参数中调整，如task_worker_num => 64，表示启动64个进程来接收异步任务
     *
     *
     * 注意事项
     *
     *  * 使用swoole_server_task必须为Server设置onTask和onFinish回调，否则swoole_server->start会失败
     *  * task操作的次数必须小于onTask处理速度，如果投递容量超过处理能力，task会塞满缓存区，导致worker进程发生阻塞。worker进程将无法接收新的请求
     *
     * @param mixed $data
     * @param int $dst_worker_id
     * @return bool
     */
    public function task($data, $dst_worker_id = -1)
    {
    }


    /**
     * 此函数可以向任意worker进程或者task进程发送消息。在非主进程和管理进程中可调用。收到消息的进程会触发onPipeMessage事件
     *
     *  * $message为发送的消息数据内容
     *  * $dst_worker_id为目标进程的ID，范围是0 ~ (worker_num + task_worker_num - 1)
     *
     * !! 使用sendMessage必须注册onPipeMessage事件回调函数
     *
     *      $serv = new swoole_server("0.0.0.0", 9501);
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
     *      $serv->on('receive', function (swoole_server $serv, $fd, $from_id, $data) {
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
     * @param int $dst_worker_id
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
     * 使用swoole_server::finish函数必须为Server设置onFinish回调函数。此函数只可用于task进程的onTask回调中
     *
     * swoole_server::finish是可选的。如果worker进程不关心任务执行的结果，不需要调用此函数
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
     * @param bool $ignore_close
     * @return array | bool
     */
    public function connection_info($fd, $from_id = -1, $ignore_close = false)
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
     * @return bool
     */
    public function reload()
    {
    }

    /**
     * 关闭服务器
     *
     * 此函数可以用在worker进程内。向主进程发送SIGTERM也可以实现关闭服务器。
     *
     * kill -15 主进程PID
     * @return bool
     */
    public function shutdown()
    {
    }

    /**
     * Swoole提供了swoole_server::addListener来增加监听的端口。业务代码中可以通过调用swoole_server::connection_info来获取某个连接来自于哪个端口
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
     * @param int $port
     * @param int $type
     * 
     * @return \swoole_server_port|bool 如果成功，1.8.0以上版本返回swoole_server_port，以下返回TRUE；如果失败返回FALSE
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
     * swoole_server::after函数是一个一次性定时器，执行完成后就会销毁。
     *
     * $after_time_ms 指定时间，单位为毫秒
     * $callback_function 时间到期后所执行的函数，必须是可以调用的。callback函数不接受任何参数
     * $after_time_ms 最大不得超过 86400000
     * 此方法是swoole_timer_after函数的别名
     *
     * @param $ms
     * @param int $after_time_ms
     * @param mixed $callback_function
     * @param mixed $param
     */
    public function after($after_time_ms, $callback_function, $param = null)
    {
    }

    /*
     * 增加监听端口，addlistener的别名
     * @param $host
     * @param $port
     * @param $type
     * @return bool
     */
    public function listen($host, $port, $type = SWOOLE_SOCK_TCP)
    {
    }

    /**
     *
     * 添加一个用户自定义的工作进程
     *
     *  * $process 为swoole_process对象，注意不需要执行start。在swoole_server启动时会自动创建进程，并执行指定的子进程函数
     *  * 创建的子进程可以调用$server对象提供的各个方法，如connection_list/connection_info/stats
     *  * 在worker进程中可以调用$process提供的方法与子进程进行通信
     *  * 此函数通常用于创建一个特殊的工作进程，用于监控、上报或者其他特殊的任务。
     *
     * 子进程会托管到Manager进程，如果发生致命错误，manager进程会重新创建一个
     *
     * @param swoole_process $process
     */
    public function addProcess(swoole_process $process)
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
     * @param int $ms
     * @param mixed $callback
     * @param mixed $param
     * @return int
     */
    public function tick($interval_ms, $callback, $param = null)
    {
    }

    /**
     * 删除设定的定时器，此定时器不会再触发
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
     * handler/on/set 方法只能在swoole_server::start前调用
     *
     *
     *      $serv->handler('onStart', 'my_onStart');
     *      $serv->handler('onStart', array($this, 'my_onStart'));
     *      $serv->handler('onStart', 'myClass::onStart');
     *
     * @param string $event_name
     * @param mixed $event_callback_function
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
     * 此函数与swoole_server->send都是向客户端发送数据，不同的是sendfile的数据来自于指定的文件。
     *
     * @param int $fd
     * @param string $filename 文件绝对路径
     * @return bool
     */
    public function sendfile($fd, $filename)
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

    /**
     * 根据监听的端口号获取ServerSocket，返回一个sockets资源
     * @param $port
     * @return resource
     */
    public function getSocket($port = 0)
    {

    }

    /**
     * 判断fd对应的连接是否存在
     * @param int $fd
     * @return bool
     */
    function exist($fd)
    {

    }

    /**
     * @param callable $callback
     */
    public function defer(callable $callback)
    {

    }

    /**
     * @param int $fd
     * @return bool | array
     */
    function getClientInfo($fd)
    {

    }
}
