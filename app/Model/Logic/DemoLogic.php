<?php

namespace App\Model\Logic;

use Swoft\Bean\Annotation\Mapping\Bean;
use App\Model\Data\DemoData;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * @Bean()
 */
class DemoLogic
{
    /**
     * @Inject()
     * @var DemoData
     */
    private $data;
}