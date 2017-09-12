<?php

namespace Swoft\Db;

use Swoft\App;
use Swoft\Helpers\ArrayHelper;
use Swoft\Web\AbstractResult;

/**
 *
 *
 * @uses      DbResult
 * @version   2017年09月10日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class DbResult extends AbstractResult
{
    public function getResult(string $className = "")
    {
        if ($this->sendResult === null || $this->sendResult === false) {
            return false;
        }
        $result = $this->recv();

        list($key, $sqlId) = explode(".", $this->profileKey);
        App::debug("SQL语句执行结果(defer) sqlId=$sqlId result=".json_encode($result));
        if(is_array($result) && !empty($className)){
            $result = ArrayHelper::resultToEntity($result, $className);
        }

        return $result;
    }
}