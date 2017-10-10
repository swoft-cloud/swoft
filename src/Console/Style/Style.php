<?php

namespace Swoft\Console\Style;

/**
 *
 *
 * @uses      Style
 * @version   2017年10月08日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Style
{
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

    const TAGS_REG = '/<([a-z=;]+)>(.*?)<\/\\1>/s';
    const STRIP_REG = '/<[\/]?[a-z=;]+>/';

    /**
     * Array of Style objects
     *
     * @var array
     */
    private static $tags = [];


    public static function t(string $message)
    {
        if (!self::isSupportColor()) {
            return static::stripColor($message);
        }


        preg_match_all(self::TAGS_REG, $message, $matches);
        if (!$matches) {
            return $message;
        }

        foreach ((array)$matches[0] as $i => $m) {
            if (array_key_exists($matches[1][$i], self::$tags)) {

                $message = self::replaceColor($message, $matches[1][$i], $matches[2][$i], (string)self::$tags[$matches[1][$i]]);
            }
        }
        return $message;
    }

    public static function addTag(string $name, string $fg = '', string $bg = '', array $options = [])
    {
        self::$tags[$name] = Color::make($fg, $bg, $options);
    }

    public static function addTagByColor(string $name, Color $color)
    {
        self::$tags[$name] = $color;
    }

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

    private static function replaceColor($text, $tag, $match, $style): string
    {
        $replace = sprintf("\033[%sm%s\033[0m", $style, $match);
        return str_replace("<$tag>$match</$tag>", $replace, $text);
    }

    private static function stripColor($string)
    {
        return preg_replace(self::STRIP_REG, '', $string);
    }

    private static function isSupportColor()
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return '10.0.10586' === PHP_WINDOWS_VERSION_MAJOR . '.' . PHP_WINDOWS_VERSION_MINOR . '.' . PHP_WINDOWS_VERSION_BUILD
                || // 0 == strpos(PHP_WINDOWS_VERSION_MAJOR . '.' . PHP_WINDOWS_VERSION_MINOR . PHP_WINDOWS_VERSION_BUILD, '10.') ||
                false !== getenv('ANSICON')
                || 'ON' === getenv('ConEmuANSI')
                || 'xterm' === getenv('TERM')// || 'cygwin' === getenv('TERM')
                ;
        }

        if (!defined('STDOUT')) {
            return false;
        }

        return self::isInteractive(STDOUT);
    }

    private static function isInteractive($fileDescriptor)
    {
        return function_exists('posix_isatty') && @posix_isatty($fileDescriptor);
    }
}