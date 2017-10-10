<?php

namespace Swoft\Console\Output;

use Swoft\Console\Style\Style;

/**
 *
 *
 * @uses      Output
 * @version   2017年10月06日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Output implements IOutput
{
    const GAP_CHAR = '  ';
    const LEFT_CHAR = '  ';

    public static function writeln($messages = '', $newline = true, $quit = false)
    {
        $messages = Style::t($messages);
        echo $messages;
        if ($newline) {
            echo "\n";
        }
        if ($quit) {
            exit();
        }
    }

    public static function writeList(array $list, $titleStyle = null, string $cmdStyle = null, string $descStyle = null)
    {
        foreach ($list as $title => $items) {
            if ($titleStyle != null) {
                $title = "<$titleStyle>$title</$titleStyle>";
            }
            self::writeln($title);
            foreach ($items as $cmd => $desc) {
                if (is_int($cmd)) {
                    $message = self::LEFT_CHAR . $desc;
                } else {
                    $maxLength = self::getCmdMaxLength(array_keys($items));
                    $cmd = str_pad($cmd, $maxLength, ' ');
                    if ($cmd != null) {
                        $cmd = "<$cmdStyle>$cmd</$cmdStyle>";
                    }
                    $message = self::LEFT_CHAR . $cmd . self::GAP_CHAR . $desc;
                }
                self::writeln($message);
            }

            self::writeln("");
        }
    }

    private static function getCmdMaxLength(array $cmds)
    {
        $max = 0;
        foreach ($cmds as $cmd) {
            $length = strlen($cmd);
            if ($length > $max) {
                $max = $length;
                continue;
            }
        }
        return $max;
    }
}