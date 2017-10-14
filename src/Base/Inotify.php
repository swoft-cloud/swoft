<?php

namespace Swoft\Base;

use Swoft\App;
use Swoft\Server\IServer;

/**
 * 文件更新自动监听
 *
 * @uses      Inotify
 * @version   2017年08月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Inotify
{
    const CREATE_DIR_MASK = 1073742080;

    /**
     * 监听文件变化的路径
     *
     * @var string
     */
    private $watchDir;

    /**
     * 监听文件变化事件
     *
     * @var int
     */
    private $events = IN_MODIFY | IN_CREATE | IN_IGNORED | IN_DELETE;

    /**
     * 监听文件后缀类型，默认PHP
     *
     * @var array
     */
    private $fileTypes = ['php'];

    /**
     * server服务器
     *
     * @var IServer
     */
    private $server;

    private $watchFiles = [];

    public function __construct(IServer $server)
    {
        $this->server = $server;
        $this->watchDir = App::getAlias('@app');
    }

    /**
     * 启动监听
     */
    public function run()
    {

        $inotify = inotify_init();

        // 设置为非阻塞
        stream_set_blocking($inotify, 0);

        $tempFiles = [];
        $iterator = new \RecursiveDirectoryIterator($this->watchDir);
        $files = new \RecursiveIteratorIterator($iterator);
        foreach ($files as $file) {
            $path = dirname($file);

            // 只监听目录
            if (!isset($tempFiles[$path])) {
                $wd = inotify_add_watch($inotify, $path, $this->events);
                $tempFiles[$path] = $wd;
                $this->watchFiles[$wd] = $path;
            }
        }

        // swoole Event add
        $this->addSwooleEvent($inotify);
    }

    /**
     * 新增swoole事件
     *
     * @param mixed $inotify
     */
    private function addSwooleEvent($inotify)
    {
        // swoole Event add
        swoole_event_add($inotify, function ($inotify) {
            // 读取有事件变化的文件
            $events = inotify_read($inotify);
            if ($events) {
                $this->reloadFiles($inotify, $events);
            }
        }, null, SWOOLE_EVENT_READ);
    }

    /**
     * 重新reload
     *
     * @param mixed $inotify
     * @param array $events
     */
    private function reloadFiles($inotify, $events)
    {
        $isReload = false;

        // 更新的文件监控
        foreach ($events as $event) {
            $wid = $event['wd'];
            if (!isset($this->watchFiles[$wid])) {
                continue;
            }

            $mask = $event['mask'];
            $fileName = $event['name'];
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

            // 不是监控的文件类型处理(文件夹或非PHP文件)
            if (!in_array($fileExt, $this->fileTypes)) {
                $this->updateInotify($inotify, $wid, $mask, $fileName);
                continue;
            }

            $isReload = true;
        }

        if (!$isReload) {
            return;
        }

        // 延迟一秒
        sleep(3);

        echo "inotify开始自动reloading...\n";
        $this->server->isRunning();
        $this->server->reload();
        echo "inotify自动成功\n";
    }

    /**
     * 更新监控信息
     *
     * @param mixed  $inotify
     * @param int    $wid
     * @param int    $mask
     * @param string $fileName
     */
    private function updateInotify($inotify, int $wid, int $mask, string $fileName)
    {
        // 创建文件操作，添加到监控里面
        if ($mask == self::CREATE_DIR_MASK) {
            $path = $this->watchFiles[$wid] . '/' . $fileName;
            $wd = inotify_add_watch($inotify, $path, $this->events);
            $this->watchFiles[$wd] = $path;
        }
    }
}
