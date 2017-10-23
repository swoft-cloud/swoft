<?php

namespace Swoft\Console;

/**
 * 命令行接口
 *
 * @uses      IConsole
 * @version   2017年10月06日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IConsole
{
    public function run();
}