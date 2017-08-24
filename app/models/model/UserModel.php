<?php

namespace app\models\model;

use app\models\logic\IndexLogic;
use Monolog\Logger;
use swoft\di\annotation\Bean;
use swoft\di\annotation\Inject;

/**
 * @Bean(name="userModel")
 *
 * @uses      UserModel
 * @version   2017年08月23日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class UserModel
{
    /**
     * @Inject("${logger}")
     * @var Logger
     */
    private $d2;

    /**
     * @Inject()
     * @var IndexLogic
     */
    private $data;

    /**
     * @Inject("${config.service.user.timeout}")
     * @var int
     */
    private $data2;

    /**
     * @Inject(name="${config.user.stelin.steln}")
     * @var string
     */
    private $data3;

    private $data4;

    private $data5;


    public function getData()
    {
        return $this->data;
    }
}