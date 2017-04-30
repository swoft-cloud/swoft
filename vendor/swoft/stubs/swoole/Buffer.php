<?php

namespace Swoole;
/**
 * Class swoole_buffer
 *
 * 内存操作
 */
class Buffer
{

    /**
     * @param int $size
     */
    function __construct($size = 128)
    {
    }

    /**
     * 将一个字符串数据追加到缓存区末尾
     *
     * @param string $data
     * @return int
     */
    function append($data)
    {
    }

    /**
     * 从缓冲区中取出内容
     *
     * substr会复制一次内存
     * remove后内存并没有释放，只是底层进行了指针偏移。当销毁此对象时才会真正释放内存
     *
     * @param int $offset 表示偏移量，如果为负数，表示倒数计算偏移量
     * @param int $length 表示读取数据的长度，默认为从$offset到整个缓存区末尾
     * @param bool $remove 表示从缓冲区的头部将此数据移除。只有$offset = 0时此参数才有效
     */
    function substr($offset, $length = -1, $remove = false)
    {
    }

    /**
     * 清理缓存区数据
     * 执行此操作后，缓存区将重置。swoole_buffer对象就可以用来处理新的请求了。
     * swoole_buffer基于指针运算实现clear，并不会写内存
     */
    function clear()
    {
    }

    /**
     * 为缓存区扩容
     *
     * @param int $new_size 指定新的缓冲区尺寸，必须大于当前的尺寸
     */
    function expand($new_size)
    {
    }


    /**
     * 向缓存区的任意内存位置写数据
     * 此函数可以直接写内存。所以使用务必要谨慎，否则可能会破坏现有数据
     *
     * $data不能超过缓存区的最大尺寸。
     * write方法不会自动扩容
     *
     * @param int $offset 偏移量
     * @param string $data 写入的数据
     */
    function write($offset, $data)
    {
    }

}