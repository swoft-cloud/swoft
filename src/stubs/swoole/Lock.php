<?php
namespace Swoole;

/**
 * Class swoole_lock
 */
class Lock
{

    /**
     * @param int $type 为锁的类型
     * @param string $lockfile 当类型为SWOOLE_FILELOCK时必须传入，指定文件锁的路径
     * 注意每一种类型的锁支持的方法都不一样。如读写锁、文件锁可以支持 $lock->lock_read()。
     * 另外除文件锁外，其他类型的锁必须在父进程内创建，这样fork出的子进程之间才可以互相争抢锁。
     */
    public function __construct($type, $lockfile = NULL)
    {
    }


    /**
     * 加锁操作
     *
     * 如果有其他进程持有锁，那这里将进入阻塞，直到持有锁的进程unlock。
     */
    public function lock()
    {
    }


    /**
     * 加锁操作
     *
     * 与lock方法不同的是，trylock()不会阻塞，它会立即返回。
     * 当返回false时表示抢锁失败，有其他进程持有锁。返回true时表示加锁成功，此时可以修改共享变量。
     *
     * SWOOlE_SEM 信号量没有trylock方法
     */
    public function trylock()
    {
    }


    /**
     * 释放锁
     */
    public function unlock()
    {
    }


    /**
     * 阻塞加锁
     *
     * lock_read方法仅可用在读写锁(SWOOLE_RWLOCK)和文件锁(SWOOLE_FILELOCK)中，表示仅仅锁定读。
     * 在持有读锁的过程中，其他进程依然可以获得读锁，可以继续发生读操作。但不能$lock->lock()或$lock->trylock()，这两个方法是获取独占锁的。
     *
     * 当另外一个进程获得了独占锁(调用$lock->lock/$lock->trylock)时，$lock->lock_read()会发生阻塞，直到持有锁的进程释放。
     */
    public function lock_read()
    {
    }


    /**
     * 非阻塞加锁
     *
     * 此方法与lock_read相同，但是非阻塞的。调用会立即返回，必须检测返回值以确定是否拿到了锁。
     */
    public function trylock_read()
    {
    }
}

