<?php

namespace swoft\base;

use swoft\web\HttpServer;

/**
 * 文件更新自动监听
 *
 * @uses      Inotify
 * @version   2017年08月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Inotify
{
    /**
     * 监听文件变化的路径
     *
     * @var string
     */
    private $watchDir = APP_PATH;

    /**
     * 监听文件变化事件
     *
     * @var int
     */
    private $events = IN_MODIFY | IN_DELETE | IN_CREATE | IN_MOVE;

    /**
     * 监听文件后缀类型，默认PHP
     *
     * @var array
     */
    private $fileTypes = ['php'];

    private $httpServer;

    public function __construct(HttpServer $httpServer)
    {
        $this->httpServer = new HttpServer();
    }

    /**
     * 启动监听
     */
    public function run()
    {
        global $watchFiles;

        $inotify = inotify_init();

        // 设置为非阻塞
        stream_set_blocking($inotify, 0);

        $iterator = new \RecursiveDirectoryIterator($this->watchDir);
        $files = new \RecursiveIteratorIterator($iterator);
        foreach ($files as $file) {
            // 监听类型判断
            $fileType = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array($fileType, $this->fileTypes)) {
                continue;
            }

            // 监听文件
            $wid = inotify_add_watch($inotify, $file, $this->events);
            $watchFiles[$wid] = $file;
        }

        // swoole event add
        $this->addSwooleEvent($inotify);
    }

    /**
     * 新增swoole事件
     *
     * @param mixed $inotify
     */
    private function addSwooleEvent($inotify)
    {
        global $watchFiles;

        // swoole event add
        swoole_event_add($inotify, function ($inotify) use ($watchFiles) {
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
        global $watchFiles;

        $isReload = false;
        // 更新的文件监控
        foreach ($events as $event) {
            $wid = $event['wd'];
            if (!isset($watchFiles[$wid])) {
                continue;
            }

            $file = $watchFiles[$wid];

            // 删除旧的监控
            unset($watchFiles[$wid]);

            $isReload = true;
            // 文件重新监控
            $wd = inotify_add_watch($inotify, $file, $this->events);

            $watchFiles[$wd] = $file;
        }


        if (!$isReload) {
            return;
        }

        // 延迟一秒
        sleep(3);

        echo "inotify开始自动reloading...\n";
        $this->httpServer->isRunning();
        $this->httpServer->reload();
        echo "inotify自动成功\n";
    }
}