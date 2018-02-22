<?php

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