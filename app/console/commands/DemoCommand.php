<?php

namespace app\console\commands;

use swoft\console\Command;

/**
 */
class DemoCommand extends Command
{
    protected static $name = 'demo';

    protected static $description = 'a demo command.';

    protected function execute($input, $output)
    {
        $output->write('hello');
    }
}
