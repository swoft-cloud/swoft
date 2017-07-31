<?php

namespace swoft\console;

use inhere\console\io\Input;
use inhere\console\utils\Show;

/**
 * @uses      Console
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
trait Console
{
    private $server;
    private $status;
    private $command;

    public function parseCommand()
    {
        $input = new Input;
        $command = $input->getCommand();

        if (!$command || $command === 'help' || $input->getSameOpt(['h', 'help'])) {
            $this->showHelp($input);
        }

        $this->loadSwoftIni();

        $this->status['startFile'] = $input->getScript();

        if (!in_array($command, self::ALLOW_COMMANDS, true)) {
            Show::error("The command: $command is not exists.");
            $this->showHelp($input);
        }

        $this->command = $command;
        $this->$command();
    }

    /**
     * stop the swoole application server
     */
    public function stop()
    {
        if (!$this->isRunning()) {
            echo "The server is not running! cannot stop\n";
            exit(0);
        }

        $pidFile = $this->server['pfile'];
        $startFile = $this->status['startFile'];
        @unlink($pidFile);
        echo("swoft $startFile is stopping ... \n");

        $this->server['masterPid'] && posix_kill($this->server['masterPid'], SIGTERM);

        $timeout = 5;
        $startTime = time();

        while (1) {
            $masterIslive = $this->server['masterPid'] && posix_kill($this->server['masterPid'], SIGTERM);

            if ($masterIslive) {
                if (time() - $startTime >= $timeout) {
                    echo("swoft " . $startFile . " stop fail \n");
                    exit;
                }
                usleep(10000);
                continue;
            }

            echo("swoft $startFile stop success \n");
            break;
        }
    }

    /**
     * reload the swoole application server
     */
    public function reload()
    {
        if (!$this->isRunning()) {
            echo "The server is not running! cannot reload\n";
            exit(0);
        }

        $startFile = $this->status['startFile'];

        echo "swoft $startFile  is reloading \n";

        posix_kill($this->server['managerPid'], SIGUSR1);

        echo "swoft $startFile reload success \n";
    }

    /**
     * restart the swoole application server
     */
    public function restart()
    {
        if ($this->isRunning()) {
            $this->stop();
        }

        $this->start();
    }

    private function checkStatus()
    {
        $masterIslive = false;
        $pfile = $this->server['pfile'];
        if (file_exists($pfile)) {
            $pidFile = file_get_contents($pfile);
            $pids = explode(',', $pidFile);

            $this->server['masterPid'] = $pids[0];
            $this->server['managerPid'] = $pids[1];
            $masterIslive = $this->server['masterPid'] && @posix_kill($this->server['managerPid'], 0);
        }

        if ($masterIslive && $this->command == 'start') {
//            echo("ysf ".$this->status['startFile']." is already running \n");
//            exit;
        }

        if ($masterIslive == false && $this->command != "start") {
            echo("ysf " . $this->status['startFile'] . " is not running \n");
            exit;
        }
    }

    /**
     * check Status
     * @return bool
     */
    protected function isRunning()
    {
        $masterIsLive = false;
        $pFile = $this->server['pfile'];

        if (file_exists($pFile)) {
            $pidFile = file_get_contents($pFile);
            $pids = explode(',', $pidFile);

            $this->server['masterPid'] = $pids[0];
            $this->server['managerPid'] = $pids[1];
            $masterIsLive = $this->server['masterPid'] && @posix_kill($this->server['managerPid'], 0);
        }

        return $masterIsLive;
    }

    private function loadSwoftIni()
    {
        $setings = parse_ini_file($this->settingPath, true);
        if (!isset($setings['tcp'])) {

        }
        if (!isset($setings['http'])) {

        }
        if (!isset($setings['server'])) {

        }

        if (!isset($setings['setting'])) {

        }

        $this->tcp = $setings['tcp'];
        $this->http = $setings['http'];
        $this->server = $setings['server'];
        $this->setting = $setings['setting'];
    }

    protected function showHelp(Input $input)
    {
        $script = $input->getScriptName();

        Show::helpPanel([
            Show::HELP_DES => 'the application server powered by swoole',
            Show::HELP_USAGE => "$script <cyan>{start|stop|reload|restart}</cyan> [--opt ...]",
            Show::HELP_COMMANDS => [
                'start' => 'start the swoole application server',
                'restart' => 'restart the swoole application server',
                'reload' => 'reload the swoole application server',
                'stop' => 'stop the swoole application server',
                'help' => 'display the help information',
            ],
            Show::HELP_OPTIONS => [
                '-h,--help' => 'display the help information',
                '--only-task' => 'only reload task worker when exec reload command'
            ]
        ]);
    }
}