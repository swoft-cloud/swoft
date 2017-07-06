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
    private $flushInterval = 100;
    public $targets = [];

    private $logid = "";
    private $spanid = 0;



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
    public $messages = [];


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

        $record = array(
            "logid" => $this->logid,
            "spanid" => $this->spanid,
            'message' => $this->getTrace($message),
            'context' => $context,
            'level' => $level,
            'level_name' => $levelName,
            'channel' => $this->name,
            'datetime' => $ts,
            'extra' => array(),
        );

        foreach ($this->processors as $processor) {
            $record = \Swoole\Coroutine::call_user_func($processor, $record);
        }

        $this->messages[] = $record;

        return true;
    }

    public function getTrace($message)
    {
        $traces = debug_backtrace();
        $count = count($traces);
        $ex = '';
        if ($count >= 2) {
            $info = $traces[1];
            if (isset($info['file'], $info['line'])) {
                $filename = basename($info['file']);
                $linenum = $info['line'];
                $ex = "$filename:$linenum";
            }
        }
        if ($count >= 3) {
            $info = $traces[2];
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
            $this->messages = $this->appendNoticeLog($this->messages);
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

    public function appendNoticeLog($messages)
    {
        return $messages;
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


}