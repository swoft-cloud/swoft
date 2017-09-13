<?php

namespace App\Models\Entity;

use App\Models\Logic\IndexLogic;
use Monolog\Logger;
use Swoft\Di\Annotation\Bean;
use Swoft\Di\Annotation\Inject;

/**
 * @Bean(name="userModel")
 *
 * @uses      User
 * @version   2017年08月23日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class User
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
     * @Inject("${config.Service.user.timeout}")
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
