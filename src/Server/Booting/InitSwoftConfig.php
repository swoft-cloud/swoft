<?php

namespace Swoft\Server\Booting;

use Swoft\App;
use Swoft\Helper\StringHelper;
use Swoft\Server\AbstractServer;

/**
 * @uses      InitSwoftConfig
 * @version   2017-11-02
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class InitSwoftConfig implements Bootable
{

    public function bootstrap()
    {
        $server = App::$server;
        if ($server instanceof AbstractServer) {
            $settings = App::getAppProperties()->get('server');
            if (! isset($settings['tcp'])) {
                throw new \InvalidArgumentException("未配置tcp启动参数，settings=" . json_encode($settings));
            }

            if (! isset($settings['http'])) {
                throw new \InvalidArgumentException("未配置http启动参数，settings=" . json_encode($settings));
            }

            if (! isset($settings['server'])) {
                throw new \InvalidArgumentException("未配置server启动参数，settings=" . json_encode($settings));
            }

            if (! isset($settings['setting'])) {
                throw new \InvalidArgumentException("未配置setting启动参数，settings=" . json_encode($settings));
            }

            if (isset($settings['setting']['log_file']) && StringHelper::contains($settings['setting']['log_file'], ['@'])) {
                $logPath = $settings['setting']['log_file'];
                $settings['setting']['log_file'] = App::getAlias($logPath);
            }

            $server->tcpSetting = $settings['tcp'];
            $server->httpSetting = $settings['http'];
            $server->serverSetting = $settings['server'];
            $server->processSetting = $settings['process'];
            $server->crontabSetting = $settings['crontab'];
            $server->setting = $settings['setting'];
        }
    }
}