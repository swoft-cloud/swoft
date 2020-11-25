<?php

namespace App\Common;

use Exception;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class Snoflake
 * 
 * 
 *  unused                                                 datacenter_id        sequence_id
 *   |                                                           |                   |
 *   |                                                           |                   |
 *   | |                                                     |   |                   |
 *   | |                                                     |   |                   |
 *   V |                                                     |   |                   |   
 *     |<-------------------  41 bits  --------------------->|   V                   V
 * |---|-----------------------------------------------------|-------|-------|----------------|
 * | 0 | 0000 0000 0000 0000 0000 0000 0000 0000 0000 0000 0 | 00000 | 00000 | 0000 0000 0000 |
 * |___|_____________________________________________________|_______|_______|________________|
 *                              ^                                        ^
 *                              |                                        |
 *                              |                                        |
 *                              |                                        |
 *                     time in milliseconds                          worker_id
 * 
 * 
 * 生成的数值是 64 位，分成 4 个部分：
 * 第一个 bit 为符号位，最高位为 0 表示正数
 * 第二部分 41 个 bit 用于记录生成 ID 时候的时间戳，单位为毫秒，所以该部分表示的数值范围为 2^41 - 1（69 年），它是相对于某一时间的偏移量
 * 第三部分的 10 个 bit 表示工作节点的 ID，表示数值范围为 2^10 - 1，相当于支持 1024 个节点
 * 第四部分 12 个 bit 表示每个工作节点没毫秒生成的循环自增 id，最多可以生成 2^12 -1 个 id，超出归零等待下一毫秒重新自增
 * 
 * @since 2.0
 *
 * @Bean("snowflake")
 */
class Snowflake
{
    const EPOCH = 1606305096570;    // 起始时间戳，毫秒

    const SEQUENCE_BITS = 12;   //序号部分12位
    // 就是-1的二进制表示为1的补码,其实等同于 : 2**self::SEQUENCE_BITS - 1
    const SEQUENCE_MAX = -1 ^ (-1 << self::SEQUENCE_BITS);  // 序号最大值

    const DATACENTER_BITS = 5;    // 数据中心部分5位
    const DATACENTER_MAX = -1 ^ (-1 << self::DATACENTER_BITS);  // 节点最大数值

    const WORKER_BITS = 5; // 节点部分5位
    const WORKER_MAX = -1 ^ (-1 << self::WORKER_BITS);  // 节点最大数值

    const TIME_SHIFT = self::DATACENTER_BITS + self::WORKER_BITS + self::SEQUENCE_BITS; // 时间戳部分左偏移量
    const DATACENTER_SHIFT = self::WORKER_BITS + self::SEQUENCE_BITS;  //数据中心部分偏移量
    const WORKER_SHIFT = self::SEQUENCE_BITS;   // 节点部分左偏移量


    protected $timestamp;   // 上次ID生成时间戳
    protected $sequence;    // 序号
    protected $lock;        // Swoole 互斥锁

    public function __construct()
    {
        $this->timestamp = 0;
        $this->sequence = 0;
        $this->lock = new \swoole_lock(SWOOLE_MUTEX);
    }

    /**
     * 生成GUID
     * @param int $workerId 节点ID [0,32)
     * @param int $dataCenterId 数据中心ID [0,32)
     * @param int $timeout 超时时间（秒)
     * @return string
     * 
     * @throws Exception
     */
    public function id(int $workerId, int $dataCenterId, int $timeout = 3): string
    {
        if ($workerId < 0 || $workerId > self::WORKER_MAX) {
            throw new \Exception('Worker ID 超出[0-' . self::WORKER_MAX . ']范围');
        }
        if ($dataCenterId < 0 || $dataCenterId > self::DATACENTER_MAX) {
            throw new \Exception('Data Center ID 超出[0-' . self::DATACENTER_MAX . ']范围');
        }

        $startAt = time();
        while (1) {
            if (time() > $timeout + $startAt) {
                throw new Exception('ID生成失败，获取锁超时');
            }
            // 这里一定要记得加锁
            if (!$this->lock->trylock()) {
                continue;
            }

            $now = $this->now();
            if ($this->timestamp === $now) {
                $this->sequence++;
                if ($this->sequence > self::SEQUENCE_MAX) {
                    // 当前毫秒内生成的序号已经超出最大范围，等待下一毫秒重新生成
                    $now = $this->now();
                }
            } else {
                $this->sequence &= 0;
            }
            $this->timestamp = $now;    // 更新ID生时间戳

            $id = (($now - self::EPOCH) << self::TIME_SHIFT)
                | ($dataCenterId << self::DATACENTER_SHIFT)
                | ($workerId << self::WORKER_SHIFT)
                | $this->sequence;

            $this->lock->unlock();  //解锁

            break;
        }

        return (string)$id;
    }

    /**
     * 获取当前毫秒
     * @return int
     */
    private function now(): int
    {
        return sprintf("%d", microtime(true) * 1000);
    }
}
