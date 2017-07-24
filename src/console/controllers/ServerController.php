<?php

namespace swoft\console\controllers;

use swoft\console\Controller;

/**
 */
class ServerController extends Controller
{
    protected static $name = 'server';

    protected static $description = 'manage the swoft application runtime. [<info>built in</info>]';

    protected $showMore = false;

    protected function createApp()
    {
        /* @var  \swoft\web\Application $application */
        $application = \swoft\base\ApplicationContext::getBean('application');
        $application->run();

        //return $application;
    }

    /**
     * start the swoole application
     */
    public function startCommand()
    {
        $this->write('hello start');
    }

    /**
     * restart the swoole application
     */
    public function restartCommand()
    {
        $this->write('hello restart');
    }

    /**
     * stop the swoole application
     */
    public function stopCommand()
    {
        $this->write('hello stop');
    }
}
