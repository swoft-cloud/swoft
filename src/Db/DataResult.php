<?php

namespace Swoft\Db;

use Swoft\App;
use Swoft\Helper\ArrayHelper;
use Swoft\Web\AbstractResult;

/**
 * 数据库数据结果对象
 *
 * @uses      DataResult
 * @version   2017年09月10日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class DataResult extends AbstractResult
{
    /**
     * 获取执行结果
     *
     * @param string $className 数据填充到实体的类名
     *
     * @return array|bool 返回结果如果执行失败返回false，更新成功返回true,查询返回数据
     */
    public function getResult(string $className = "")
    {
        // 发包是否成功验证
        if ($this->sendResult === null || $this->sendResult === false) {
            return false;
        }

        // 接受包数据
        $result = $this->recv();

        // 日志记录处理
        list(, $sqlId) = explode(".", $this->profileKey);
        App::debug("SQL语句执行结果(defer) sqlId=$sqlId result=" . json_encode($result));

        // 填充实体数据
        if (is_array($result) && !empty($className)) {
            $result = ArrayHelper::resultToEntity($result, $className);
        }

        return $result;
    }
}