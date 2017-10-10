<?php

namespace Swoft\Console\Style;

/**
 *
 *
 * @uses      Color
 * @version   2017年10月08日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
final class Color
{
    /**
     * Foreground base value
     */
    const FG_BASE = 30;

    /**
     * Background base value
     */
    const BG_BASE = 40;

    // color
    const BLACK = 'black';
    const RED = 'red';
    const GREEN = 'green';
    const YELLOW = 'yellow'; // BROWN
    const BLUE = 'blue';
    const MAGENTA = 'magenta';
    const CYAN = 'cyan';
    const WHITE = 'white';
    const NORMAL = 'normal';

    // color option
    const BOLD = 'bold';       // 加粗
    const FUZZY = 'fuzzy';      // 模糊(不是所有的终端仿真器都支持)
    const ITALIC = 'italic';     // 斜体(不是所有的终端仿真器都支持)
    const UNDERSCORE = 'underscore'; // 下划线
    const BLINK = 'blink';      // 闪烁
    const REVERSE = 'reverse';    // 颠倒的 交换背景色与前景色
    const CONCEALED = 'concealed';  // 隐匿的

    const COLORS
        = [
            'black'   => 0,
            'red'     => 1,
            'green'   => 2,
            'yellow'  => 3,
            'blue'    => 4,
            'magenta' => 5, // 洋红色 洋红 品红色
            'cyan'    => 6, // 青色 青绿色 蓝绿色
            'white'   => 7,
            'normal'  => 9,
        ];

    const OPTIONS
        = [
            'bold'       => 1,       // 22 加粗
            'fuzzy'      => 2,      // 模糊(不是所有的终端仿真器都支持)
            'italic'     => 3,      // 斜体(不是所有的终端仿真器都支持)
            'underscore' => 4, // 24 下划线
            'blink'      => 5,      // 25 闪烁
            'reverse'    => 7,    // 27 颠倒的 交换背景色与前景色
            'concealed'  => 8,  // 28 隐匿的
        ];

    private $fgColor = 0;

    private $bgColor = 0;

    private $options = [];

    public static function make(string $fg = '', string $bg = '', array $options = [])
    {
        return new self($fg, $bg, $options);
    }

    private function __construct(string $fg = '', string $bg = '', array $options = [])
    {
        $fgNotExist = !empty($fg) && !array_key_exists($fg, self::COLORS);
        $bgNotExist = !empty($bg) && !array_key_exists($bg, self::COLORS);
        if ($fgNotExist || $bgNotExist) {
            throw new \InvalidArgumentException("Foreground和Background参数值，不存在，检查后再试！");
        }

        if (!empty($fg)) {
            $this->fgColor = self::FG_BASE + self::COLORS[$fg];
        }

        if (!empty($bg)) {
            $this->bgColor = self::BG_BASE + self::COLORS[$bg];
        }

        foreach ($options as $option) {
            if (!array_key_exists($option, self::OPTIONS)) {
                throw new \InvalidArgumentException("选项参数不存在，option=" . $option);
            }
            $this->options[] = self::OPTIONS[$option];
        }
    }

    public function __toString()
    {
        return $this->getStyle();
    }

    public function getStyle()
    {
        $values = $this->options;
        if($this->bgColor > 0){
            array_unshift($values, $this->bgColor);
        }

        if($this->fgColor > 0){
            array_unshift($values, $this->fgColor);
        }

        return implode(';', $values);
    }
}