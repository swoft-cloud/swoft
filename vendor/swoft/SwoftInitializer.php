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
 * @author    lilin <lilin@ugirls.com>
 * @copyright Copyright 2010-2016 北京尤果网文化传媒有限公司
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
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