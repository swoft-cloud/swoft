<?php

namespace App\Model\Dao;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * @Bean()
 */
class DemoDao
{

    public function get()
    {
        echo 'dao get' . PHP_EOL;
    }
}