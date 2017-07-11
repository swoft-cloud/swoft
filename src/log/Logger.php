<?php

namespace swoft\log;

/**
 *
 *
 * @uses      Logger
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Logger extends \Monolog\Logger
{

    public $name = "swoft";
    public $flushInterval = 100;
    public $targets = [];

    private $uri = "";
    private $beginTime;
    private $logid = "";
    private $spanid = 0;

    // 性能日志
    public $profiles = [];

    // 计算日志
    public $countings= [];

    // 标记日志
    public $pushlogs = [];

    // 标记栈
    public $profileStacks = [];


    protected static $levels = array(
        self::DEBUG     => 'debug',
        self::INFO      => 'info',
        self::NOTICE    => 'notice',
        self::WARNING   => 'warning',
        self::ERROR     => 'error',
        self::CRITICAL  => 'critical',
        self::ALERT     => 'alert',
        self::EMERGENCY => 'emergency',
    );

    /**
     * @var array 记录请求日志
     */
    public $messages = [

    ];


    public function init()
    {
//        $output = "%datetime% [%level_name%] [%channel%] [logid:%logid%] [445(ms)] [4(MB)] [/Web/vrOrder/Order] [%extra%] [status=200] [] profile[] counting[]\n";
        $output = "%datetime% [%level_name%] [%channel%] [logid:%logid%] [spanid:%spanid%] %message%";

        // finally, create a formatter
        $formatter = new \Monolog\Formatter\LineFormatter($output, "Y/m/d H:i:s");

        foreach ($this->targets as $target){
            if(!isset($target['class']) || !isset($target['logFile']) || !isset($target['levels']) || !is_array($target['levels'])){
                continue;
            }

            $class = $target['class'];
            $logFile = $target['logFile'];
            $levels = $target['levels'];

            if($class == FileHandler::class){
                $handler = new FileHandler($logFile, $levels);
                $handler->setFormatter($formatter);
                $this->pushHandler($handler);
            }
        }
    }


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

        $message = $this->getTrace($message);
        $record = $this->formateRecord($message, $context, $level, $levelName, $ts, []);

        foreach ($this->processors as $processor) {
            $record = \Swoole\Coroutine::call_user_func($processor, $record);
        }

        $this->messages[] = $record;

        if(count($this->messages) >= $this->flushInterval){
            $this->flushLog();
        }
    }

    public function formateRecord($message, $context, $level, $levelName, $ts, $extra)
    {
        $record = array(
            "logid" => $this->logid,
            "spanid" => $this->spanid,
            'message' => $message,
            'context' => $context,
            'level' => $level,
            'level_name' => $levelName,
            'channel' => $this->name,
            'datetime' => $ts,
            'extra' => array(),
        );

        return $record;
    }

    /**
     * pushlog日志
     *
     * @param string $key
     * @param mixed $val
     */
    public function pushLog($key, $val)
    {
        if (!(is_string($key) || is_numeric($key))) {
            return;
        }

        $key = urlencode($key);
        if (is_array($val)) {
            $this->pushlogs[] = "$key=" . json_encode($val);
        } elseif (is_bool($val)) {
            $this->pushlogs[] = "$key=" . var_export($val, true);
        } elseif (is_string($val) || is_numeric($val)) {
            $this->pushlogs[] = "$key=" . urlencode($val);
        } elseif (is_null($val)) {
            $this->pushlogs[] = "$key=";
        }
    }

    /**
     * 标记开始
     *
     * @param string $name
     */
    public function profileStart($name)
    {
        if(is_string($name) == false || empty($name)){
            return ;
        }
        $this->profileStacks[$name]['start'] = microtime(true);
    }

    /**
     * 标记开始
     *
     * @param string $name
     */
    public function profileEnd($name)
    {
        if (is_string($name) == false || empty($name)) {
            return;
        }

        if (! isset($this->profiles[$name])) {
            $this->profiles[$name] = [
                'cost' => 0,
                'total' => 0
            ];
        }

        $this->profiles[$name]['cost'] += microtime(true) - $this->profileStacks[$name]['start'];
        $this->profiles[$name]['total'] = $this->profiles[$name]['total'] + 1;
    }

    /**
     * 组装profiles
     */
    public function getProfilesInfos()
    {
        $profileAry = [];
        foreach ($this->profiles as $key => $profile){
            if(!isset($profile['cost']) || !isset($profile['total'])){
                continue;
            }
            $cost = sprintf("%.2f", $profile['cost'] * 1000);
            $profileAry[] = "$key=" .  $cost. '(ms)/' . $profile['total'];
        }

        return implode(",", $profileAry);
    }

    /**
     * 缓存命中率计算
     *
     * @param string $name
     * @param int    $hit
     * @param int    $total
     */
    public function counting($name, $hit, $total = null)
    {
        if (!is_string($name) || empty($name)) {
            return;
        }
        if (!isset($this->countings[$name])) {
            $this->countings[$name] = ['hit' => 0, 'total' => 0];
        }
        $this->countings[$name]['hit'] += intval($hit);
        if ($total !== null) {
            $this->countings[$name]['total'] += intval($total);
        }
    }

    /**
     * 组装字符串
     */
    public function getCountingInfo()
    {
        if(empty($this->countings)){
            return "";
        }

        $countAry = [];
        foreach ($this->countings as $name => $counter){
            if(isset($counter['hit'], $counter['total']) && $counter['total'] != 0){
                $countAry[] = "$name=".$counter['hit']."/".$counter['total'];
            }elseif(isset($counter['hit'])){
                $countAry[] = "$name=".$counter['hit'];
            }
        }
        return implode(',', $countAry);
    }

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

    public function flushLog($final = false)
    {
        if($final == true){
            $this->appendNoticeLog();
        }

        if(empty($this->messages)){
            return ;
        }
        reset($this->handlers);

        while ($handler = current($this->handlers)) {
            $handler->handleBatch($this->messages);
            next($this->handlers);
        }

        // 清空数组
        $this->messages = [];
    }

    public function appendNoticeLog()
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

        // php耗时单位ms毫秒
        $timeUsed = sprintf("%.0f", (microtime(true)-$this->beginTime)*1000);

        // php运行内存大小单位M
        $memUsed = sprintf("%.0f", memory_get_peak_usage()/(1024*1024));

        $profileInfo = $this->getProfilesInfos();
        $countingInfo = $this->getCountingInfo();

        $messageAry = array(
            "[$timeUsed(ms)]",
            "[$memUsed(MB)]",
            "[{$this->uri}]",
            "[".implode(" ", $this->pushlogs)."]",
            "profile[".$profileInfo."]",
            "counting[".$countingInfo."]"
        );


        $message = implode(" ", $messageAry);

        $this->profiles = [];
        $this->countings = [];
        $this->pushlogs = [];
        $this->profileStacks = [];

        $levelName = self::$levels[self::NOTICE];
        $message = $this->formateRecord($message, [], self::NOTICE, $levelName, $ts, []);

        $this->messages[] = $message;

    }


    /**
     * @param string $logid
     */
    public function setLogid(string $logid)
    {
        $this->logid = $logid;
    }



    /**
     * @param int $spanid
     */
    public function setSpanid(int $spanid)
    {
        $this->spanid = $spanid;
    }

    /**
     * @param string $uri
     */
    public function setUri(string $uri)
    {
        $this->uri = $uri;
    }


    /**
     * @param int $beginTime
     */
    public function setBeginTime($beginTime)
    {
        $this->beginTime = $beginTime;
    }
}