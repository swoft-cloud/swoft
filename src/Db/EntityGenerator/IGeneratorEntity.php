<?php

namespace Swoft\Db\EntityGenerator;

/**
 * 生成实体操作接口
 *
 * @uses      IGeneratorEntity
 * @version   2017年11月06日
 * @author    caiwh <471113744@qq.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IGeneratorEntity
{
    /**
     * 获取当前db的所有表
     *
     * @return array
     */
    public function getSchemaTables();
}
