<?php

namespace Swoft\Log;

use Swoft\App;
use Swoft\Base\RequestContext;

/**
 * 日志类
 *
 * @uses      Logger
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Logger extends \Monolog\Logger
{

    /**
     * trace 日志级别
     */
    const TRACE = 650;

    /**
     * @var string 日志系统名称
     */
    public $name = "Swoft";

    /**
     * @var int 刷新日志条数
     */
    public $flushInterval = 1;

    /**
     * @var bool 每个请求完成刷新一次日志到磁盘，默认未开启
     */
    public $flushRequest = false;

    /**
     * @var array 性能日志
     */
    public $profiles = [];

    /**
     * @var array 计算日志
     */
    public $countings = [];

    /**
     * @var array 标记日志
     */
    public $pushlogs = [];

    /**
     * @var array 标记栈
     */
    public $profileStacks = [];

    /**
     * @var array 日志数据记录
     */
    public $messages = [];


    protected $processors = [];


    /**
     * @var array 日志级别对应名称
     */
    protected static $levels
        = array(
            self::DEBUG     => 'debug',
            self::INFO      => 'info',
            self::NOTICE    => 'notice',
            self::WARNING   => 'warning',
            self::ERROR     => 'error',
            self::CRITICAL  => 'critical',
            self::ALERT     => 'alert',
            self::EMERGENCY => 'emergency',
            self::TRACE     => 'trace'
        );

    public function __construct()
    {

    }

    /**
     * 记录日志
     *
     * @param int   $level   日志级别
     * @param mixed $message 信息
     * @param array $context 附加信息
     *
     * @return bool
     */
    public function addRecord($level, $message, array $context = array())
    {
        $levelName = static::getLevelName($level);

        if (!static::$timezone) {
            static::$timezone = new \DateTimeZone(date_default_timezone_get() ?: 'UTC');
        }

        // php7.1+ always has microseconds enabled, so we do not need this hack
        if ($this->microsecondTimestamps && PHP_VERSION_ID < 70100) {
            $ts = \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)), static::$timezone);
        } else {
            $ts = new \DateTime(null, static::$timezone);
        }

        $ts->setTimezone(static::$timezone);

        $message = $this->formateMessage($message);
        $message = $this->getTrace($message);
        $record = $this->formateRecord($message, $context, $level, $levelName, $ts, []);

        foreach ($this->processors as $processor) {
            $record = \Swoole\Coroutine::call_user_func($processor, $record);
        }

        $this->messages[] = $record;

        if (count($this->messages) >= $this->flushInterval) {
            $this->flushLog();
        }

        return true;
    }

    /**
     * 格式化一条日志记录
     *
     * @param string    $message   信息
     * @param string    $context   内容
     * @param int       $level     级别
     * @param string    $levelName 级别名
     * @param \DateTime $ts        时间
     * @param array     $extra     附加信息
     *
     * @return array
     */
    public function formateRecord($message, $context, $level, $levelName, $ts, $extra)
    {
        $record = array(
            "logid"      => $this->getLogid(),
            "spanid"     => $this->getSpanid(),
            'messages'    => $message,
            'context'    => $context,
            'level'      => $level,
            'level_name' => $levelName,
            'channel'    => $this->name,
            'datetime'   => $ts,
            'extra'      => $extra,
        );

        return $record;
    }

    /**
     * pushlog日志
     *
     * @param string $key 记录KEY
     * @param mixed  $val 记录值
     */
    public function pushLog($key, $val)
    {
        if (!(is_string($key) || is_numeric($key))) {
            return;
        }

        $key = urlencode($key);
        $cid = App::getCoroutineId();
        if (is_array($val)) {
            $this->pushlogs[$cid][] = "$key=" . json_encode($val);
        } elseif (is_bool($val)) {
            $this->pushlogs[$cid][] = "$key=" . var_export($val, true);
        } elseif (is_string($val) || is_numeric($val)) {
            $this->pushlogs[$cid][] = "$key=" . urlencode($val);
        } elseif (is_null($val)) {
            $this->pushlogs[$cid][] = "$key=";
        }
    }

    /**
     * 标记开始
     *
     * @param string $name 标记名称
     */
    public function profileStart($name)
    {
        if (is_string($name) == false || empty($name)) {
            return;
        }
        $cid = App::getCoroutineId();
        $this->profileStacks[$cid][$name]['start'] = microtime(true);
    }

    /**
     * 标记开始
     *
     * @param string $name 标记名称
     */
    public function profileEnd($name)
    {
        if (is_string($name) == false || empty($name)) {
            return;
        }

        $cid = App::getCoroutineId();
        if (!isset($this->profiles[$cid][$name])) {
            $this->profiles[$cid][$name] = [
                'cost'  => 0,
                'total' => 0
            ];
        }

        $this->profiles[$cid][$name]['cost'] += microtime(true) - $this->profileStacks[$cid][$name]['start'];
        $this->profiles[$cid][$name]['total'] = $this->profiles[$cid][$name]['total'] + 1;
    }

    /**
     * 组装profiles
     */
    public function getProfilesInfos()
    {
        $profileAry = [];
        $cid = App::getCoroutineId();
        $profiles = $this->profiles[$cid]?? [];
        foreach ($profiles as $key => $profile) {
            if (!isset($profile['cost']) || !isset($profile['total'])) {
                continue;
            }
            $cost = sprintf("%.2f", $profile['cost'] * 1000);
            $profileAry[] = "$key=" . $cost . '(ms)/' . $profile['total'];
        }

        return implode(",", $profileAry);
    }

    /**
     * 缓存命中率计算
     *
     * @param string $name  计算KEY
     * @param int    $hit   命中数
     * @param int    $total 总数
     */
    public function counting($name, $hit, $total = null)
    {
        if (!is_string($name) || empty($name)) {
            return;
        }

        $cid = App::getCoroutineId();
        if (!isset($this->countings[$cid][$name])) {
            $this->countings[$cid][$name] = ['hit' => 0, 'total' => 0];
        }
        $this->countings[$cid][$name]['hit'] += intval($hit);
        if ($total !== null) {
            $this->countings[$cid][$name]['total'] += intval($total);
        }
    }

    /**
     * 组装字符串
     */
    public function getCountingInfo()
    {
        $cid = App::getCoroutineId();
        if (!isset($this->countings[$cid]) || empty($this->countings[$cid])) {
            return "";
        }

        $countAry = [];
        $countings = $this->countings[$cid];
        foreach ($countings as $name => $counter) {
            if (isset($counter['hit'], $counter['total']) && $counter['total'] != 0) {
                $countAry[] = "$name=" . $counter['hit'] . "/" . $counter['total'];
            } elseif (isset($counter['hit'])) {
                $countAry[] = "$name=" . $counter['hit'];
            }
        }
        return implode(',', $countAry);
    }

    /**
     * 写入日志信息格式化
     *
     * @param $message
     *
     * @return string
     */
    public function formateMessage($message)
    {
        if (is_array($message)) {
            return json_encode($message);
        }
        return $message;
    }

    /**
     * 计算调用trace
     *
     * @param $message
     *
     * @return string
     */
    public function getTrace($message)
    {
        $traces = debug_backtrace();
        $count = count($traces);

        $ex = '';
        if ($count >= 4) {
            $info = $traces[3];
            if (isset($info['file'], $info['line'])) {
                $filename = basename($info['file']);
                $linenum = $info['line'];
                $ex = "$filename:$linenum";
            }
        }
        if ($count >= 5) {
            $info = $traces[4];
            if (isset($info['class'], $info['type'], $info['function'])) {
                $ex .= ',' . $info['class'] . $info['type'] . $info['function'];
            } elseif (isset($info['function'])) {
                $ex .= ',' . $info['function'];
            }
        }

        if (!empty($ex)) {
            $message = "trace[$ex] " . $message;
        }


        return $message;
    }

    /**
     * 刷新日志到handlers
     */
    public function flushLog()
    {
        if (empty($this->messages)) {
            return;
        }

        reset($this->handlers);

        while ($handler = current($this->handlers)) {
            $handler->handleBatch($this->messages);
            next($this->handlers);
        }

        // 清空数组
        $this->messages = [];
    }

    /**
     * 请求完成追加一条notice日志
     */
    public function appendNoticeLog()
    {
        $cid = App::getCoroutineId();
        $ts = $this->getLoggerTime();

        // php耗时单位ms毫秒
        $timeUsed = sprintf("%.2f", (microtime(true) - $this->getRequestTime()) * 1000);

        // php运行内存大小单位M
        $memUsed = sprintf("%.0f", memory_get_peak_usage() / (1024 * 1024));

        $profileInfo = $this->getProfilesInfos();
        $countingInfo = $this->getCountingInfo();
        $pushlogs = $this->pushlogs[$cid]??[];

        $messageAry = array(
            "[$timeUsed(ms)]",
            "[$memUsed(MB)]",
            "[{$this->getUri()}]",
            "[" . implode(" ", $pushlogs) . "]",
            "profile[" . $profileInfo . "]",
            "counting[" . $countingInfo . "]"
        );


        $message = implode(" ", $messageAry);

        unset($this->profiles[$cid]);
        unset($this->countings[$cid]);
        unset($this->pushlogs[$cid]);
        unset($this->profileStacks[$cid]);

        $levelName = self::$levels[self::NOTICE];
        $message = $this->formateRecord($message, [], self::NOTICE, $levelName, $ts, []);

        $this->messages[] = $message;

        // 一个请求完成刷新一次或达到刷新的次数
        if ($this->flushRequest || count($this->messages) >= $this->flushInterval) {
            $this->flushLog();
        }

    }

    /**
     * 获取去日志时间
     *
     * @return \DateTime
     */
    private function getLoggerTime()
    {
        if (!static::$timezone) {
            static::$timezone = new \DateTimeZone(date_default_timezone_get() ?: 'UTC');
        }

        // php7.1+ always has microseconds enabled, so we do not need this hack
        if ($this->microsecondTimestamps && PHP_VERSION_ID < 70100) {
            $ts = \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)), static::$timezone);
        } else {
            $ts = new \DateTime(null, static::$timezone);
        }

        $ts->setTimezone(static::$timezone);
        return $ts;
    }

    /**
     * 添加一条trace日志
     *
     * @param       $message 日志信息
     * @param array $context 附加信息
     *
     * @return bool
     */
    public function addTrace($message, array $context = array())
    {
        return $this->addRecord(static::TRACE, $message, $context);
    }

    /**
     * 请求logid
     *
     * @return string
     */
    private function getLogid()
    {
        $contextData = RequestContext::getContextData();
        $logid = $contextData['logid']?? "";
        return $logid;
    }

    /**
     * 请求跨度值
     *
     * @return int
     */
    private function getSpanid()
    {
        $contextData = RequestContext::getContextData();
        $spanid = $contextData['spanid']?? 0;
        return $spanid;
    }

    /**
     * 请求URI
     *
     * @return string
     */
    private function getUri()
    {
        $contextData = RequestContext::getContextData();
        $uri = $contextData['uri']?? "";
        return $uri;
    }

    /**
     * 请求开始时间
     *
     * @return int
     */
    private function getRequestTime()
    {
        $contextData = RequestContext::getContextData();
        $requestTime = $contextData['requestTime']?? 0;
        return $requestTime;
    }

}
