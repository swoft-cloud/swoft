<?php

namespace Swoole;

/**
 * Class swoole_atomic
 *
 * woole_atomic是swoole扩展提供的原子计数操作类，可以方便整数的无锁原子增减。
 * swoole_atomic使用共享内存，可以在不同的进程之间操作计数
 * swoole_atomic基于gcc提供的CPU原子指令，无需加锁
 * swoole_atomic在服务器程序中必须在swoole_server->start前创建才能在Worker进程中使用
 */
class Atomic
{
    /**
     * @param int $init_value
     */
    public function __construct($init_value)
    {
    }

    /**
     * 增加计数
     *
     * @param $add_value
     * @return int
     */
    public function add($add_value)
    {
    }

    /**
     * 减少计数
     *
     * @param $sub_value
     * @return int
     */
    public function sub($sub_value)
    {
    }

    /*
     * 获取当前计数的值
     * @return int
     */
    public function get()
    {
    }

    /**
     * 将当前值设置为指定的数字
     *
     * @param $value
     */
    public function set($value)
    {
    }

    /**
     * 如果当前数值等于参数1，则将当前数值设置为参数2
     *
     * @param int $cmp_value
     * @param int $set_value
     */
    public function cmpset($cmp_value, $set_value)
    {
    }
}