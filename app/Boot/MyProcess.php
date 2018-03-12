<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Boot;

use Swoft\App;
use Swoft\Process\Bean\Annotation\Process;
use Swoft\Process\Process as SwoftProcess;
use Swoft\Process\ProcessBuilder;
use Swoft\Process\ProcessInterface;
use Swoft\Task\Task;

/**
 * Custom process
 *
 * @Process(boot=false)
 */
class MyProcess implements ProcessInterface
{
    public function run(SwoftProcess $process)
    {
        $pname = App::$server->getPname();
        $processName = "$pname myProcess process";
        $process->name($processName);

        echo "Custom boot process \n";

        $result  = Task::deliverByProcess('sync', 'deliverCo', ['p', 'p2']);
        var_dump($result);

        ProcessBuilder::create('customProcess')->start();
    }

    public function check(): bool
    {
        return true;
    }
}