<?php

namespace Swoft\Helper;

/**
 * @uses      DirHelper
 * @version   2017-11-01
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class DirHelper
{

    /**
     * 仅扫描当前层级文件夹
     */
    const SCAN_CURRENT_DIR = 'current';
    /**
     * 广度优先搜索
     */
    const SCAN_BFS = 'bfs';
    /**
     * 深度优先搜索
     */
    const SCAN_DFS = 'dfs';

    /**
     * 根据规则扫描文件路径
     *
     * @param string $path 扫描路径
     * @param null $pattern 扫描匹配规则
     * @param string $strategy 扫描算法
     * @return array
     */
    public static function glob(string $path, $pattern = null, $strategy = self::SCAN_DFS): array
    {
        if (! is_dir($path) || ! $pattern) {
            throw new \InvalidArgumentException('Invalid $path or $pattern for DirHelper::glob');
        }

        $files = self::scan($path, $strategy);
        $result = [];
        foreach ($files as $file) {
            if (false === self::matchPattern($pattern, $file)) {
                continue;
            }
            $result[] = $file;
        }
        return $result;
    }

    /**
     * 扫描路径的所有文件
     *
     * @param string $path 扫描路径
     * @param string $strategy 扫描算法
     * @param bool $excludeDir 是否忽略文件夹
     * @return array
     */
    public static function scan(string $path, $strategy = self::SCAN_CURRENT_DIR, $excludeDir = true): array
    {
        if (! is_dir($path)) {
            throw new \InvalidArgumentException('Invalid $path for DirHelper::scan');
        }

        switch ($strategy) {
            case self::SCAN_CURRENT_DIR:
                $files = self::scanCurrentDir($path, $excludeDir);
                break;
            case self::SCAN_BFS:
                $files = self::scanBfs($path, $excludeDir);;
                break;
            case self::SCAN_DFS:
                $files = self::scanDfs($path, $excludeDir);
                break;
            default:
                throw new \InvalidArgumentException('Invalid $strategy for DirHelper::glob');
        }

        return $files;
    }

    /**
     * 格式化路径，给结尾始终带上 '/'
     *
     * @param string $path
     * @return string
     */
    public static function formatPath(string $path): string
    {
        if ('/' == substr($path, -1)) {
            return $path;
        }

        return $path . '/';
    }

    /**
     * $fileName 是否匹配 $pattern 规则
     *
     * @param string $pattern
     * @param string $fileName
     * @return bool
     */
    public static function matchPattern(string $pattern, string $fileName): bool
    {
        $replaceMap = [
            '*' => '.*',
            '.' => '\.',
            '+' => '.+',
            '/' => '\/',
        ];

        $pattern = str_replace(array_keys($replaceMap), array_values($replaceMap), $pattern);
        $pattern = '/' . $pattern . '/i';

        if (preg_match($pattern, $fileName)) {
            return true;
        }

        return false;
    }

    /**
     * 获取路径中的文件名
     *
     * @param array $pathes
     * @param string $suffix If the filename ends in suffix this will also be cut off
     * @return array
     */
    public static function basename(array $pathes, $suffix = ''): array
    {
        if (! $pathes) {
            return [];
        }

        $ret = [];
        foreach ($pathes as $path) {
            $ret[] = basename($path, $suffix);
        }

        return $ret;
    }

    /**
     * 扫描当前层级文件夹
     *
     * @param string $path 路径
     * @param bool $ignoreDir 是否忽略文件夹
     * @return array
     */
    private static function scanCurrentDir(string $path, bool $ignoreDir = true): array
    {
        $path = self::formatPath($path);
        $dh = opendir($path);
        if (! $dh) {
            return [];
        }

        $files = [];
        while (false !== ($file = readdir($dh))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $fileType = filetype($path . $file);
            if ('dir' == $fileType && false === $ignoreDir) {
                $files[] = $path . $file . '/';
            }
            if ('file' == $fileType) {
                $files[] = $path . $file;
            }
        }
        closedir($dh);
        return $files;
    }

    /**
     * 以广度优先搜索扫描路径
     *
     * @param string $path 路径
     * @param bool $ignoreDir 是否忽略文件夹
     * @return array
     */
    private static function scanBfs(string $path, bool $ignoreDir = true): array
    {
        $files = [];
        $queue = new \SplQueue();
        $queue->enqueue($path);

        while (! $queue->isEmpty()) {
            $file = $queue->dequeue();
            $fileType = filetype($file);
            if ('dir' == $fileType) {
                $subFiles = self::scanCurrentDir($file, false);
                foreach ($subFiles as $subFile) {
                    $queue->enqueue($subFile);
                }
                if (false === $ignoreDir && $file != $path) {
                    $files[] = $file;
                }
            }
            if ('file' == $fileType) {
                $files[] = $file;
            }
        }
        return $files;
    }

    /**
     * 以深度优先搜索扫描路径
     *
     * @param string $path 路径
     * @param bool $excludeDir 是否忽略文件夹
     * @return array
     */
    private static function scanDfs(string $path, bool $excludeDir = true): array
    {
        $files = [];
        $subFiles = self::scanCurrentDir($path, false);

        foreach ($subFiles as $subFile) {
            $fileType = filetype($subFile);
            if ('dir' == $fileType) {
                $innerFiles = self::scanDfs($subFile, $excludeDir);
                $files = ArrayHelper::merge($files, $innerFiles);
                if (false === $excludeDir) {
                    $files[] = $subFile;
                }
            }
            if ('file' == $fileType) {
                $files[] = $subFile;
            }
        }
        return $files;
    }

}