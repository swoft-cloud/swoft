<?php

namespace Swoft\Console;

/**
 *
 *
 * @uses      CommandParser
 * @version   2017年10月06日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class CommandParser
{
    const TRUE_WORDS = '|on|yes|true|';
    const FALSE_WORDS = '|off|no|false|';

    public static function parse(array $params, array $noValues = [], $mergeOpts = false): array
    {
        $args = $sOpts = $lOpts = [];

        while (list(, $p) = each($params)) {
            // is options
            if ($p{0} === '-') {
                $isLong = false;
                $opt = substr($p, 1);
                $value = true;

                // long-opt: (--<opt>)
                if ($opt{0} === '-') {
                    $isLong = true;
                    $opt = substr($opt, 1);

                    // long-opt: value specified inline (--<opt>=<value>)
                    if (strpos($opt, '=') !== false) {
                        list($opt, $value) = explode('=', $opt, 2);
                    }

                    // short-opt: value specified inline (-<opt>=<value>)
                } elseif (strlen($opt) > 2 && $opt{1} === '=') {
                    list($opt, $value) = explode('=', $opt, 2);
                }

                // check if next parameter is a descriptor or a value
                $nxp = current($params);

                // fix: allow empty string ''
                if ($value === true && $nxp !== false && (!$nxp || $nxp{0} !== '-') && !in_array($opt, $noValues, true)) {
                    list(, $value) = each($params);

                    // short-opt: bool opts. like -e -abc
                } elseif (!$isLong && $value === true) {
                    foreach (str_split($opt) as $char) {
                        $sOpts[$char] = true;
                    }

                    continue;
                }

                if ($isLong) {
                    $lOpts[$opt] = self::filterBool($value);
                } else {
                    $sOpts[$opt] = self::filterBool($value);
                }

                // arguments: param doesn't belong to any option, define it is args
            } else {
                // value specified inline (<arg>=<value>)
                if (strpos($p, '=') !== false) {
                    list($name, $value) = explode('=', $p, 2);
                    $args[$name] = self::filterBool($value);
                } else {
                    $args[] = $p;
                }
            }
        }

        if ($mergeOpts) {
            return [$args, array_merge($sOpts, $lOpts)];
        }

        return [$args, $sOpts, $lOpts];
    }

    public static function filterBool($val, $enable = true)
    {
        if ($enable) {
            if (is_bool($val) || is_numeric($val)) {
                return $val;
            }

            $tVal = strtolower($val);

            // check it is a bool value.
            if (false !== strpos(self::TRUE_WORDS, "|$tVal|")) {
                return true;
            }

            if (false !== strpos(self::FALSE_WORDS, "|$tVal|")) {
                return false;
            }
        }

        return $val;
    }
}