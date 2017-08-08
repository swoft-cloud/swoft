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

    protected static $description = 'manage the swoft application server runtime. [<info>built in</info>]';

    protected $showMore = false;

    /**
     * @return \swoft\web\Application
     */
    protected function createApp()
    {
        /* @var  \swoft\web\Application $application */
        $application = ApplicationContext::getBean('application');
        $application->init($this->input->getScript());

        return $application;
    }

    /**
     * start the swoole application server
     *
     * @options
     * -d, --daemon  run app server on the background
     */
    public function startCommand()
    {
        //$this->write('hello start');

        require BASE_PATH . '/app/routes.php';

        $daemon = $this->input->getSameOpt(['d', 'daemon']);

        $this->createApp()->asDaemon($daemon)->start();
    }

    /**
     * restart the swoole application server
     *
     * @options
     * -d, --daemon  run app server on the background
     */
    public function restartCommand()
    {
        require BASE_PATH . '/app/routes.php';

        $daemon = $this->input->getSameOpt(['d', 'daemon']);

        $this->createApp()->asDaemon($daemon)->restart();
    }

    /**
     * reload the swoole application server
     *
     * @options
     *  --task  only reload task worker when exec reload command
     */
    public function reloadCommand()
    {
        //$this->write('hello restart');
        $onlyTask = $this->input->getSameOpt(['task']);

        $this->createApp()->reload($onlyTask);
    }

    /**
     * stop the swoole application server
     */
    public function stopCommand()
    {
        //$this->write('hello stop');

        $this->createApp()->stop();
    }
}
