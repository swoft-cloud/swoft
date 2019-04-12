<?php

namespace App\Model\Data;

use App\Model\Dao\DemoDao;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * @Bean()
 */
class DemoData
{
    /**
     * @Inject()
     * @var DemoDao
     */
    private $dao;

    public function getDao()
    {
        echo 'data getDao'.PHP_EOL;
        $this->dao->get();
    }
}