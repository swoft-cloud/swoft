<?php

namespace swoft\console\controllers;

use swoft\console\Controller;
use swoft\base\ApplicationContext;

/**
 * ServerController
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

        return $application;
    }

    /**
     * start the swoole application server
     */
    public function startCommand()
    {
        $this->write('hello start');
        $router = ApplicationContext::getBean('router');

        require APP_PATH . '/app/routes.php';

        $this->createApp()->run();
    }

    /**
     * restart the swoole application server
     */
    public function restartCommand()
    {
        $this->write('hello restart');
    }

    /**
     * reload the swoole application server
     */
    public function reloadCommand()
    {
        $this->write('hello restart');
    }

    /**
     * stop the swoole application server
     */
    public function stopCommand()
    {
        $this->write('hello stop');
    }
}
