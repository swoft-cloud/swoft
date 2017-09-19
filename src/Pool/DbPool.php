<?php

namespace Swoft\Pool;

/**
 *
 *
 * @uses      DbPool
 * @version   2017年09月01日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class DbPool extends ConnectPool
{
    const MYSQL = "Mysql";

    /**
     * 数据库驱动
     *
     * @var string
     */
    private $driver = self::MYSQL;

    public function createConnect()
    {
        $uri = $this->getConnectAddress();
        $options = $this->parseUri($uri);
        $options['timeout'] = $this->timeout;

        $connectClassName = "Swoft\Db\\".$this->driver."\\Connect";
        if(!class_exists($connectClassName)){
            throw new \InvalidArgumentException("暂时不支持该驱动数据库，driver=".$this->driver);
        }
        return new $connectClassName($this->driver, $options);
    }

    public function reConnect($client)
    {

    }

    private function parseUri($uri)
    {
        $parseAry = parse_url($uri);
        if(!isset($parseAry['host']) || !isset($parseAry['port']) || !isset($parseAry['path']) || !isset($parseAry['query'])){
            throw new \InvalidArgumentException("数据量连接uri格式不正确，uri=".$uri);
        }
        $parseAry['database'] = str_replace('/', '', $parseAry['path']);
        $query = $parseAry['query'];
        parse_str($query, $options);

        if(!isset($options['user']) || !isset($options['password'])){
            throw new \InvalidArgumentException("数据量连接uri格式不正确，未配置用户名和密码，uri=".$uri);
        }
        if(!isset($options['charset'])){
            $options['charset'] = "";
        }

        $configs = array_merge($parseAry, $options);
        unset($configs['path']);
        unset($configs['query']);
        return $configs;
    }
}
