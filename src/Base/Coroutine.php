<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-09-12
 * Time: 14:33
 */

namespace Sws\Context;

use Swoft\Helper\PhpHelper;
use Swoole\Coroutine as SwCoroutine;

/**
 * Class Coroutine
 * @package Sws\Context
 */
class Coroutine
{
    /**
     * the Coroutine id map
     * @var array
     * [
     *  child id => top id,
     *  child id => top id,
     *  ... ...
     * ]
     */
    private static $idMap = [];

    /**
     * get current coroutine id
     * @return int|string
     */
    public static function id()
    {
        return SwCoroutine::getuid();
    }

    /**
     * get top coroutine id
     * @return int|string
     */
    public static function tid()
    {
        $id = SwCoroutine::getuid();

        return self::$idMap[$id] ?? $id;
    }

    /**
     * 创建子协程
     * @param callable $cb
     * @return bool
     */
    public static function create(callable $cb)
    {
        $tid = self::tid();

        return SwCoroutine::create(function() use($cb, $tid) {
            $id = SwCoroutine::getuid();
            self::$idMap[$id] = $tid;

            PhpHelper::call($cb);
        });
    }

    /**
     * 挂起当前协程
     * @param string $coId
     */
    public static function suspend($coId)
    {
        SwCoroutine::suspend($coId);
    }

    /**
     * 恢复某个协程，使其继续运行。
     * @param string $coId
     */
    public static function resume($coId)
    {
        SwCoroutine::resume($coId);
    }
}