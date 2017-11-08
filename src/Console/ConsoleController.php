<?php

namespace Swoft\Console;

use Swoft\App;
use Swoft\Base\ApplicationContext;
use Swoft\Base\RequestContext;
use Swoft\Bean\BeanFactory;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;

/**
 * console控制器
 *
 * @uses      ConsoleController
 * @version   2017年11月03日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ConsoleController extends ConsoleCommand
{
    /**
     * 日志缓存刷新条数
     *
     * @var int
     */
    protected $flushInterval = 1;

    /**
     * ConsoleController constructor.
     *
     * @param Input  $input
     * @param Output $output
     */
    public function __construct(Input $input, Output $output)
    {
        parent::__construct($input, $output);
    }

    /**
     * 命令执行前
     *
     * @param string $command 当前执行命令
     */
    protected function beforeRun(string $command)
    {
        if ($command != Console::DEFAULT_CMD) {
            $this->init($command);
        }
    }

    /**
     * 后置执行逻辑
     *
     * @param string $command
     */
    protected function afterRun(string $command)
    {
        if ($command != Console::DEFAULT_CMD) {
            App::getLogger()->appendNoticeLog(true);
        }
    }

    /**
     * 初始化
     *
     * @param string $command 当前执行命令
     */
    private function init(string $command)
    {
        BeanFactory::reload();

        // 初始化
        $spanid = 0;
        $logid = uniqid();

        $uri = static::class . "->" . $command . self::COMMAND_SUFFIX;
        $contextData = [
            'logid'       => $logid,
            'spanid'      => $spanid,
            'uri'         => $uri,
            'requestTime' => microtime(true)
        ];

        RequestContext::setContextData($contextData);
        App::getLogger()->setFlushInterval($this->flushInterval);
    }


}