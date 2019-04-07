<?php declare(strict_types=1);


namespace App\Task;

use Swoft\Task\Annotation\Mapping\Task;
use Swoft\Task\Annotation\Mapping\TaskMapping;

/**
 * Class TestTask
 *
 * @since 2.0
 * @Task("test")
 */
class TestTask
{
    /**
     * @TaskMapping("getData")
     * @return array
     */
    public function getData(): array
    {
        return ['task', 'data'];
    }
}