<?php

namespace swoft\console;

/**
 *
 *
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

    public function parseCommand($args)
    {
        $this->loadSwoftIni();
        if (!isset($args[1])) {
            exit("Usage: swoft {start|stop|reload|restart}\n");
        }

        $command = trim($args[1]);
        $this->status['startFile'] = $args[0];
        $methodName = strtolower($command);
        if(method_exists($this, $methodName) == false){
            exit("Usage: swoft invalid option: '".$command."'\n");
        }

        $this->command = $command;

        $this->checkStatus();
        $this->$methodName();
    }

    private function stop()
    {

        $pidFile = $this->server['pfile'];
        $startFile = $this->status['startFile'];
        @unlink($pidFile);
        echo("swoft ".$startFile." is stoping ... \n");

        $this->server['masterPid'] && posix_kill($this->server['masterPid'], SIGTERM);

        $timeout = 5;
        $startTime = time();

        while (1) {
            $masterIslive = $this->server['masterPid'] && posix_kill($this->server['masterPid'], SIGTERM);
            if ($masterIslive) {
                if (time() - $startTime >= $timeout) {
                    echo("swoft ".$startFile." stop fail \n");
                    exit;
                }
                usleep(10000);
                continue;
            }
            echo("swoft ".$startFile." stop success \n");
            break;
        }
    }

    private function reload()
    {
        $startFile = $this->status['startFile'];
        echo("swoft ".$startFile." is reloading \n");
        posix_kill($this->server['managerPid'], SIGUSR1);
        echo("swoft ".$startFile." reload success \n");
    }

    private function restart()
    {
        $this->stop();
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

        if($masterIslive && $this->command == 'start'){
            echo("ysf ".$this->status['startFile']." is already running \n");
            exit;
        }

        if($masterIslive == false && $this->command != "start"){
            echo("ysf ".$this->status['startFile']." is not running \n");
            exit;
        }
    }

    private function loadSwoftIni()
    {
        $setings = parse_ini_file($this->settingPath, true);
        if(!isset($setings['tcp'])){

        }
        if(!isset($setings['http'])){

        }
        if(!isset($setings['server'])){

        }

        if(!isset($setings['setting'])){

        }

        $this->tcp = $setings['tcp'];
        $this->http = $setings['http'];
        $this->server = $setings['server'];
        $this->setting = $setings['setting'];
    }
}