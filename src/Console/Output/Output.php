<?php

namespace Swoft\Console\Output;

use Swoft\Console\Style\Style;

/**
 * 输出
 *
 * @uses      Output
 * @version   2017年10月06日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Output implements IOutput
{
    /**
     * 间隙字符
     */
    const GAP_CHAR = '  ';

    /**
     * 左边字符
     */
    const LEFT_CHAR = '  ';

    /**
     * 输出一行数据
     *
     * @param string $messages 信息
     * @param bool   $newline  是否换行
     * @param bool   $quit     是否退出
     *
     * @return mixed
     */
    public function writeln($messages = '', $newline = true, $quit = false)
    {
        // 文字里面颜色标签翻译
        $messages = Style::t($messages);

        // 输出文字
        echo $messages;
        if ($newline) {
            echo "\n";
        }

        // 是否退出
        if ($quit) {
            exit();
        }
    }

    /**
     * 输出显示LOGO图标
     */
    public function writeLogo()
    {
        $logo = "<info>
 ____                __ _
/ ___|_      _____  / _| |_
\___ \ \ /\ / / _ \| |_| __|
 ___) \ V  V / (_) |  _| |_
|____/ \_/\_/ \___/|_|  \__|
</info>";
        $this->writeln($logo);
    }

    /**
     * 输出一个列表
     *
     * @param array       $list       列表数据
     * @param string      $titleStyle 标题样式
     * @param string      $cmdStyle   命令样式
     * @param string|null $descStyle  描述样式
     *
     * @return mixed
     */
    public function writeList(array $list, $titleStyle = 'comment', string $cmdStyle = 'info', string $descStyle = null)
    {
        foreach ($list as $title => $items) {
            // 标题
            $title = "<$titleStyle>$title</$titleStyle>";
            self::writeln($title);

            // 输出块内容
            $this->writeItems($items, $cmdStyle);
            self::writeln("");
        }
    }

    /**
     * 显示命令列表一块数据
     *
     * @param array  $items    数据
     * @param string $cmdStyle 命令样式
     */
    private function writeItems(array $items, string $cmdStyle)
    {
        foreach ($items as $cmd => $desc) {
            // 没有命令，只是一行数据
            if (is_int($cmd)) {
                $message = self::LEFT_CHAR . $desc;
                self::writeln($message);
                continue;
            }

            // 命令和描述
            $maxLength = self::getCmdMaxLength(array_keys($items));
            $cmd = str_pad($cmd, $maxLength, ' ');
            $cmd = "<$cmdStyle>$cmd</$cmdStyle>";
            $message = self::LEFT_CHAR . $cmd . self::GAP_CHAR . $desc;
            self::writeln($message);

        }
    }

    /**
     * 所有命令最大宽度
     *
     * @param array $cmds 所有命令
     *
     * @return int
     */
    private function getCmdMaxLength(array $cmds)
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