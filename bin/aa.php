<?php
$userMiddlewares = [];

if (isset($info['handler'])) {
    // Extract action info from router handler
    $exploded = explode('@', $info['handler']);
    $controllerClass = $exploded[0] ?? '';
    $action = isset($exploded[1]) ? 'action' . ucfirst($exploded[1]) : '';
    $collectedMiddlewares = Collector::$requestMapping[$controllerClass]['middlewares'];
    // Get group middleware from Collector
    if ($controllerClass) {
        $collect = $collectedMiddlewares['group'] ?? [];
        $collect && $userMiddlewares = array_merge($userMiddlewares, $collect);
    }
    // Get the specified action middleware from Collector
    if ($action) {
        $collect = $collectedMiddlewares['actions'][$action];
        $collect && $userMiddlewares = array_merge($userMiddlewares, $collect ?? []);
    }
}