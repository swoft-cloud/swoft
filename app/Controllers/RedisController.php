<?php

namespace App\Controllers;


use Swoft\Bean\Annotation\AutoController;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\RequestMapping;
use Swoft\Cache\Redis\RedisClient;
use Swoft\Web\Controller;


/**
 * @AutoController(prefix="/redis")
 * @uses      RedisController
 * @version   2017-11-12
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RedisController extends Controller
{
    /**
     * @RequestMapping()
     * @return bool|string
     */
    public function actionTest()
    {
        $setResult = RedisClient::set('test', 123321);
        $getResult = RedisClient::get('test');

        return [
            'setResult' => $setResult,
            'getResult' => $getResult
        ];
    }

}