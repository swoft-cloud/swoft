<?php

namespace app\controllers;

use app\models\logic\IndexLogic;

/**
 *
 *
 * @uses      IndexController
 * @version   2017年04月25日
 * @author    lilin <lilin@ugirls.com>
 * @copyright Copyright 2010-2016 北京尤果网文化传媒有限公司
 * @license   PHP Version 5.x {@link http://www.php.net/license/3_0.txt}
 */
class IndexController
{
    /**
     * @Inject
     * @var IndexLogic
     */
    private $logic;

    public function actionIndex()
    {
        return $this->logic->getUser();
    }
}