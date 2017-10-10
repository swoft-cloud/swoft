<?php

namespace Swoft\Console\Command;

use Swoft\Bean\Annotation\Arguments;
use Swoft\Bean\Annotation\Example;
use Swoft\Bean\Annotation\Options;
use Swoft\Bean\Annotation\Usage;
use Swoft\Console\ConsoleCommand;

/**
 *
 *
 * @uses      ServerController
 * @version   2017年10月06日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ServerController extends ConsoleCommand
{

    /**
     * this is description
     * this is description
     * this is description
     *
     * @Usage
     * 默认命令
     * 默认命令
     *
     * @Arguments
     * name 用户名称
     * age  用户年龄
     *
     * @Options
     * --d 后台启动
     * -k  快捷启动
     *
     * @Example
     * php swoft server:index name=name age=18 -f -k
     * php swoft server:index name=name age=18 -f -k
     */
    public function indexCommand()
    {

    }
}