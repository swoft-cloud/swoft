<?php

namespace Swoft\Bean\Resource;

/**
 * 资源接口
 *
 * @uses      IResource
 * @version   2017年08月18日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IResource
{
    /**
     * 获取已解析的配置beans
     *
     * @return array
     */
    public function getDefinitions();
}
