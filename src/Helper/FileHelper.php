<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-24
 * Time: 11:11
 */

namespace Swoft\Helper;

/**
 * Class FileHelper
 * @package Swoft\Helper
 */
class FileHelper
{

    /**
     * 获得文件扩展名、后缀名
     * @param $filename
     * @param bool $clearPoint 是否带点
     * @return string
     */
    public static function getSuffix($filename, $clearPoint = false): string
    {
        $suffix = strrchr($filename, '.');

        return (bool)$clearPoint ? trim($suffix, '.') : $suffix;
    }

    /**
     * @param $path
     * @return bool
     */
    public static function isAbsPath($path): bool
    {
        if (!$path || !is_string($path)) {
            return false;
        }

        if (
            $path{0} === '/' ||  // linux/mac
            1 === preg_match('#^[a-z]:[\/|\\\]{1}.+#i', $path) // windows
        ) {
            return true;
        }

        return false;
    }
}