<?php

namespace swoft;

use DI\Container;
use DI\ContainerBuilder;
use swoft\base\ApplicationContext;

/**
 *
 *
 * @uses      SwoftInitializer
 * @version   2017年04月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class SwoftInitializer
{

    /**
     * Add definitions to the container.
     *
     * @param array bean configures
     *
     * @return Container
     */
    public function init($config)
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAnnotations(true);
        $container = $containerBuilder->build();
        $container = $this->annotationBeans($container);
        ApplicationContext::setContainer($container);
        ApplicationContext::createBean('application', $config);
        return $container;
    }

    /**
     * Initializer annotation beans
     *
     * @param Container $container
     *
     * @return Container
     */
    private function annotationBeans($container)
    {
        return $container;
    }
}