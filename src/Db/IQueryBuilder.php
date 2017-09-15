<?php

namespace Swoft\Db;

/**
 * 查询器接口
 *
 * @uses      IQueryBuilder
 * @version   2017年09月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IQueryBuilder
{
    /**
     * 获取执行结果
     *
     * @param string $className 数据填充到实体的类名
     *
     * @return array|bool 返回结果如果执行失败返回false，更新成功返回true,查询返回数据
     */
    public function getResult(string $className = "");

    /**
     * 返回数据结果对象
     *
     * @return DataResult 返回数据结果对象
     */
    public function getDefer();
}