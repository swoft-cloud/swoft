<?php

namespace Swoft\Db;

use Swoft\Pool\AbstractConnect;

/**
 *
 *
 * @uses      AbstractDbConnect
 * @version   2017年09月29日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractDbConnect extends AbstractConnect implements IDbConnect
{
    public function recv()
    {
    }

    public function setDefer($defer = true)
    {
    }

    /**
     * 返回数据库驱动
     *
     * @return string
     */
    public function getDriver(): string
    {
        return $this->connectPool->getDriver();
    }

    protected function parseUri($uri)
    {
        $parseAry = parse_url($uri);
        if (!isset($parseAry['host']) || !isset($parseAry['port']) || !isset($parseAry['path']) || !isset($parseAry['query'])) {
            throw new \InvalidArgumentException("数据量连接uri格式不正确，uri=" . $uri);
        }
        $parseAry['database'] = str_replace('/', '', $parseAry['path']);
        $query = $parseAry['query'];
        parse_str($query, $options);

        if (!isset($options['user']) || !isset($options['password'])) {
            throw new \InvalidArgumentException("数据量连接uri格式不正确，未配置用户名和密码，uri=" . $uri);
        }
        if (!isset($options['charset'])) {
            $options['charset'] = "";
        }

        $configs = array_merge($parseAry, $options);
        unset($configs['path']);
        unset($configs['query']);
        return $configs;
    }
}