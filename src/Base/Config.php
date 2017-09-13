<?php

namespace Swoft\Base;

/**
 * 全局配置管理器
 *
 * @uses      Config
 * @version   2017年07月07日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Config implements \ArrayAccess, \Iterator
{
    /**
     * @var array 所有配置参数
     */
    public $properties = [];

    /**
     * Return the current element
     *
     * @return mixed Can return any type.
     */
    public function current()
    {
        return current($this->properties);
    }

    /**
     * Move forward to next element
     *
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        next($this->properties);
    }

    /**
     * Return the key of the current element
     *
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return key($this->properties);
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return ($this->current() !== false);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->properties);
    }

    /**
     * Whether a offset exists
     *
     * @param mixed $offset An offset to check for.
     *
     * @return boolean true on success or false on failure.
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->properties[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset The offset to retrieve.
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return isset($this->properties[$offset]) ? $this->properties[$offset] : null;
    }

    /**
     * Offset to set
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_string($offset) || is_int($offset)) {
            $this->properties[$offset] = $value;
        }
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset The offset to unset.
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        if (isset($this->properties[$offset])) {
            unset($this->properties[$offset]);
        }
    }

    /**
     * 查询值
     *
     * @param string|int $name    名称
     * @param mixed      $defalut 默认值
     *
     * @return mixed 返回值
     */
    public function get($name, $defalut = null)
    {
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }
        return $defalut;
    }

    /**
     * 设置值，如存在会覆盖
     *
     * @param string|int $name
     * @param mixed      $value
     */
    public function set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    /**
     * 初始化值
     *
     * @param string|int $name  key名称
     * @param mixed      $value val值
     */
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    /**
     * 查询值
     *
     * @param string|int $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->properties[$name])) {
            $this->properties[$name];
        }
        return null;
    }
}
