<?php

namespace Swoft\Event\Listeners;

use Swoft\App;
use Swoft\Base\ApplicationContext;
use Swoft\Di\Annotation\Listener;
use Swoft\Di\ResourceDataProxy;
use Swoft\Event\ApplicationEvent;
use Swoft\Event\IApplicationListener;
use Swoft\Event\Event;
use Swoft\Web\Router;

/**
 *
 * @Listener(Event::APPLICATION_LOADER)
 * @uses      ApplicationLoaderListener
 * @version   2017年09月04日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ApplicationLoaderListener implements IApplicationListener
{
    public function onApplicationEvent(ApplicationEvent $event = null, ...$params)
    {
        if(!isset($params[0])){
            return ;
        }

        /* @var ResourceDataProxy $resourceDataProxy*/
        $resourceDataProxy  = $params[0];

        // 路由自动注册
        /* @var Router $router */
        $router = App::getBean('router');
        $requestMapping = $resourceDataProxy->requestMapping;
        $router->registerRoutes($requestMapping);

        App::setProperties();
    }
}