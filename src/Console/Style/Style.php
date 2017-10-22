<?php

namespace Swoft\Console\Style;

/**
 * 命令行样式
 *
 * @uses      Style
 * @version   2017年10月08日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Style
{
    // 默认样式集合
    const NORMAL = 'normal';
    const FAINTLY = 'faintly';
    const BOLD = 'bold';
    const NOTICE = 'notice';
    const PRIMARY = 'primary';
    const SUCCESS = 'success';
    const INFO = 'info';
    const NOTE = 'note';
    const WARNING = 'warning';
    const COMMENT = 'comment';
    const QUESTION = 'question';
    const DANGER = 'danger';
    const ERROR = 'error';
    const UNDERLINE = 'underline';
    const BLUE = 'blue';
    const CYAN = 'cyan';
    const MAGENTA = 'magenta';
    const RED = 'red';
    const YELLOW = 'yellow';

    /**
     * tag样式表情匹配正则
     */
    const TAGS_REG = '/<([a-z=;]+)>(.*?)<\/\\1>/s';

    /**
     * 移除颜色匹配正则
     */
    const STRIP_REG = '/<[\/]?[a-z=;]+>/';

    /**
     * 所有初始化的样式tag标签
     *
     * @var array
     */
    private static $tags = [];

    /**
     * 初始化颜色标签
     */
    public static function init()
    {
        self::$tags[self::NORMAL] = Color::make('normal');
        self::$tags[self::FAINTLY] = Color::make('normal', '', ['italic']);
        self::$tags[self::BOLD] = Color::make('', '', ['bold']);
        self::$tags[self::INFO] = Color::make('green');
        self::$tags[self::NOTE] = Color::make('green', '', ['bold']);
        self::$tags[self::PRIMARY] = Color::make('blue');
        self::$tags[self::SUCCESS] = Color::make('green', '', ['bold']);
        self::$tags[self::NOTICE] = Color::make('', '', ['bold', 'underscore']);
        self::$tags[self::WARNING] = Color::make('yellow');
        self::$tags[self::COMMENT] = Color::make('yellow');
        self::$tags[self::QUESTION] = Color::make('black', 'cyan');
        self::$tags[self::DANGER] = Color::make('red');
        self::$tags[self::ERROR] = Color::make('black', 'red');
        self::$tags[self::UNDERLINE] = Color::make('normal', '', ['underscore']);
        self::$tags[self::BLUE] = Color::make('blue');
        self::$tags[self::CYAN] = Color::make('cyan');
        self::$tags[self::MAGENTA] = Color::make('magenta');
        self::$tags[self::RED] = Color::make('red');
        self::$tags[self::YELLOW] = Color::make('yellow');
    }

    /**
     * 颜色翻译
     *
     * @param string $message 文字
     *
     * @return mixed|string
     */
    public static function t(string $message)
    {
        // 不支持颜色，移除颜色标签
        if (!self::isSupportColor()) {
            return static::stripColor($message);
        }

        $isMatch = preg_match_all(self::TAGS_REG, $message, $matches);
        // 未匹配颜色标签
        if ($isMatch == false) {
            return $message;
        }

        // 颜色标签处理
        foreach ((array)$matches[0] as $i => $m) {
            if (array_key_exists($matches[1][$i], self::$tags)) {
                $message = self::replaceColor($message, $matches[1][$i], $matches[2][$i], (string)self::$tags[$matches[1][$i]]);
            }
        }
        return $message;
    }

    /**
     * 根据信息，新增一个tag颜色标签
     *
     * @param string $name    名称
     * @param string $fg      前景色
     * @param string $bg      背景色
     * @param array  $options 颜色选项
     */
    public static function addTag(string $name, string $fg = '', string $bg = '', array $options = [])
    {
        self::$tags[$name] = Color::make($fg, $bg, $options);
    }

    /**
     * 根据颜色对象，新增一个tag颜色标签
     *
     * @param string $name
     * @param Color  $color
     */
    public static function addTagByColor(string $name, Color $color)
    {
        self::$tags[$name] = $color;
    }

    /**
     * 标签替换成颜色
     *
     * @param string $text
     * @param string $tag
     * @param string $match
     * @param string $style
     *
     * @return string
     */
    private static function replaceColor(string $text, string $tag, string $match, string $style): string
    {
        $replace = sprintf("\033[%sm%s\033[0m", $style, $match);
        return str_replace("<$tag>$match</$tag>", $replace, $text);
    }

    /**
     * 移除颜色标签
     *
     * @param string $message
     *
     * @return mixed
     */
    private static function stripColor(string $message)
    {
        return preg_replace(self::STRIP_REG, '', $message);
    }

    /**
     * 命令行是否支持颜色
     *
     * @return bool
     */
    private static function isSupportColor()
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            $term = 'xterm' === getenv('TERM');
            $ansicon = false !== getenv('ANSICON');
            $conemuansi = 'ON' === getenv('ConEmuANSI');
            $windowsVersion = '10.0.10586' === PHP_WINDOWS_VERSION_MAJOR . '.' . PHP_WINDOWS_VERSION_MINOR . '.' . PHP_WINDOWS_VERSION_BUILD;
            $isSupport = $windowsVersion || $ansicon || $conemuansi || $term;
            return $isSupport;
        }

        if (!defined('STDOUT')) {
            return false;
        }

        return self::isInteractive(STDOUT);
    }

    /**
     * 是否是交互是终端
     *
     * @param mixed $fileDescriptor
     *
     * @return bool
     */
    private static function isInteractive($fileDescriptor)
    {
        return function_exists('posix_isatty') && @posix_isatty($fileDescriptor);
    }
}