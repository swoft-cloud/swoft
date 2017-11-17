<?php

namespace App\Commands;

use App\Models\Logic\UserLogic;
use Swoft\App;
use Swoft\Console\ConsoleController;
use Swoft\Log\Log;

/**
 * the group of test command
 *
 * @uses      TestController
 * @version   2017年11月03日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class TestController extends ConsoleController
{
    /**
     * this demo command
     *
     * @usage
     * server:{command} [arguments] [options]
     *
     * @options
     * -o,--o this is command option
     *
     * @arguments
     * arg this is argument
     *
     * @example
     * php swoft test:demo arg=stelin -o opt
     */
    public function demoCommand()
    {
        $hasOpt = $this->input->hasOpt('o');
        $opt = $this->input->getOpt('o');
        $name = $this->input->getArg('arg', 'swoft');

        App::trace("this is command log");
        Log::info("this is comamnd info log");
        /* @var UserLogic $logic*/
        $logic = App::getBean(UserLogic::class);
        $data = $logic->getUserInfo(['uid1']);
        var_dump($hasOpt, $opt, $name, $data);
    }
}