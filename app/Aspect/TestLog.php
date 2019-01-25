<?php declare(strict_types=1);


namespace App\Aspect;

use mysql_xdevapi\Exception;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class TestLog
 *
 * @Bean("testLog")
 *
 * @since 2.0
 */
class TestLog
{

    public function log()
    {
//        throw new Exception('11');
        echo 'test log' . PHP_EOL;
    }
}