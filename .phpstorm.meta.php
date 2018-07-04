<?php

namespace PHPSTORM_META {

    // Reflect
    override(\bean(0), map('@'));
    override(\Swoft\App::getBean(0), map('@'));
    override(\Swoft\Core\ApplicationContext::getBean(0), map('@'));
    override(\Swoft\Bean\BeanFactory::getBean(0), map('@'));

    // Alias
    $map = [
        'defer' => \Swoft\Defer\Defer::class,
    ];
    override(\bean(0), map($map));
    override(\Swoft\App::getBean(0), map($map));
    override(\Swoft\Core\ApplicationContext::getBean(0), map($map));
    override(\Swoft\Bean\BeanFactory::getBean(0), map($map));

}