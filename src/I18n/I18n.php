<?php

namespace Swoft\I18n;

use Swoft\App;
use Swoft\Di\Annotation\Bean;
use Swoft\Di\Annotation\Inject;

/**
 * 国际化
 *
 * @Bean("I18n")
 * @uses      I18n
 * @version   2017年08月29日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class I18n
{
    /**
     * 源语言
     *
     * @Inject("${config.I18n.sourceLanguage}")
     * @var string
     */
    public $sourceLanguage = "";

    /**
     * 翻译文案
     *
     * @var array
     */
    private $messages = [];

    /**
     * 初始化
     */
    public function init()
    {
        $sourcePath = App::getAlias($this->sourceLanguage);
        $iterator = new \RecursiveDirectoryIterator($sourcePath);
        $files = new \RecursiveIteratorIterator($iterator);
        foreach ($files as $file) {
            // 只监控php文件
            if (pathinfo($file, PATHINFO_EXTENSION) != 'php') {
                continue;
            }
            $messages = str_replace([$sourcePath, '.php'], ["", ""], $file);
            list($language, $category) = explode("/", $messages);
            $this->messages[$language][$category] = require_once $file;
        }
    }

    /**
     * 翻译文本
     *
     * @param string $category 分类
     * @param array  $params   参数
     * @param string $language 语言
     *
     * @return string
     */
    public function translate(string $category, array $params, string $language)
    {
        $key = $category;
        $categoryFile = 'default';
        if (strpos($category, '.')) {
            list($categoryFile, $key) = explode(".", $category);
        }
        if (!isset($this->messages[$language][$categoryFile][$key])) {
            App::error("i18n翻译出错，category=" . $category . " 不存在！language=".$language);
            throw new \InvalidArgumentException("i18n翻译出错，category=" . $category . " 不存在！language=".$language);
        }
        $message = $this->messages[$language][$categoryFile][$key];
        return $this->formateMessage($message, $params);
    }

    /**
     * 格式化消息
     *
     * @param string $message 消息体
     * @param array  $params  参数
     *
     * @return string
     */
    private function formateMessage(string $message, array $params)
    {
        array_unshift($params, $message);
        return sprintf(...$params);
    }
}
