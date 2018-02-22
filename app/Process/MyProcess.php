<?php

namespace App\Process;

use Swoft\App;
use Swoft\Core\Coroutine;
use Swoft\Process\Bean\Annotation\Process;
use Swoft\Process\Process as SwoftProcess;
use Swoft\Process\ProcessInterface;

/**
 * Custom process
 *
 * @Process(name="customProcess", coroutine=true)
 */
class MyProcess implements ProcessInterface
{
    public function run(SwoftProcess $process)
    {
        $pname = App::$server->getPname();
        $processName = "$pname myProcess process";
        $process->name($processName);

        echo "Custom child process \n";
        var_dump(Coroutine::id());
    }

    public function check(): bool
    {
        return true;
    }
}