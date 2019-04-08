<?php declare(strict_types=1);


namespace App\Task\Controller;

use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Task\Exception\TaskException;
use Swoft\Task\Task;

/**
 * Class TaskController
 *
 * @since 2.0
 *
 * @Controller("task")
 */
class TaskController
{
    /**
     * @RequestMapping("co")
     *
     * @return array
     * @throws TaskException
     */
    public function co(): array
    {
//        $result = Task::co('test', 'getData', [], 10);
        $result = Task::async('test', 'getData', []);
        var_dump($result);
        return [$result];
    }
}