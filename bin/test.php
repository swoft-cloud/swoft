<?php
echo swoole_version();
$serv = new Swoole\Http\Server("127.0.0.1", 9501);

$serv->set(array(
    'worker_num'            => 1,
    'task_worker_num'       => 4,
    'task_enable_coroutine' => true,
));

$serv->on('request', function ($request, $response) use ($serv) {
//    $task_id = $serv->task('stelins', -1);
    $task_id = $serv->task('stelins', -1, null);
var_dump($task_id);
    $response->end("<h1>Hello Swoole. #" . rand(1000, 9999) . "</h1>");
});

$serv->on('Task', function ($serv, Swoole\Server\Task $task) {
    //来自哪个`Worker`进程
    $task->worker_id;
    //任务的编号
    $task->id;
    //任务的类型，taskwait, task, taskCo, taskWaitMulti 可能使用不同的 flags
    $task->flags;
    //任务的数据
    $task->data;
    //协程 API
    co::sleep(0.2);
    //完成任务，结束并返回数据
    $task->finish([123, 'hello']);
});

$serv->on('Finish', function (swoole_server $serv, $task_id, $data) {
    var_dump($data, $task_id);
//    echo "Task#$task_id finished, data_len=" . strlen($data) . PHP_EOL;
});

$serv->on('workerStart', function ($serv, $worker_id) {

});

$serv->start();

