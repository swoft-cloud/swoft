<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-09-25
 * Time: 11:00
 */

namespace Swoft\Helper;

/**
 * Class PhpHelper
 * @package Swoft\Helper
 */
class PhpHelper
{
    /**
     * @param $cb
     * @param array $args
     * @return mixed
     */
    public static function call($cb, array $args = [])
    {
        if (is_object($cb) || (is_string($cb) && function_exists($cb))) {
            $ret = $cb(...$args);
        } elseif (is_array($cb)) {
            list($obj, $mhd) = $cb;

            $ret = is_object($obj) ? $obj->$mhd(...$args) : $obj::$mhd(...$args);
        } else {
            $ret = call_user_func_array($cb, $args);
        }

        return $ret;
    }
}