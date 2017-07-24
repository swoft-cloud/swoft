<?php

namespace app\console\controllers;

use swoft\console\Controller;

/**
 */
class TestController extends Controller
{
    protected static $name = 'test';

    protected static $description = 'a test controller';

    public function oneCommand()
    {
        $this->write('hello');
    }
}
