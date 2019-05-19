<?php declare(strict_types=1);


namespace App\Console\Command;

use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use function input;
use function output;
use function sprintf;

/**
 * Class TestCommand
 *
 * @since 2.0
 *
 * @Command(name="test",coroutine=false)
 */
class TestCommand
{
    /**
     * @CommandMapping(name="ab")
     */
    public function ab()
    {
        $type = input()->get('type', '');
        $uris = $this->uris();

        // Format data
        if (empty($type)) {
            $exeUris = [];
            foreach ($uris as $name => $uriAry) {
                $exeUris = array_merge($exeUris, $uriAry);
            }
        } else {
            $exeUris = $uris[$type] ?? [];
        }

        foreach ($exeUris as $uri) {
            $abShell   = sprintf('ab -n 10000 -c 2000  127.0.0.1:18306%s', $uri);
            $curlShell = sprintf('curl 127.0.0.1:18306%s', $uri);

            exec($curlShell, $curlResult);
            output()->writeln('执行结果:' . json_encode($curlResult));
            output()->writeln('执行URL:' . $abShell . PHP_EOL);

            exec($abShell, $abResult);
        }
    }

    /**
     * @return array
     */
    private function uris(): array
    {
        return [
            'redis' => [
                '/redis/str',
                '/redis/et',
                '/redis/ep',
                '/redis/release',
            ],
            'log'   => [
                '/log/test'
            ],
            'db'    => [
                '/dbTransaction/ts',
                '/dbTransaction/cm',
                '/dbTransaction/rl',
                '/dbTransaction/ts2',
                '/dbTransaction/cm2',
                '/dbTransaction/rl2',
                '/dbModel/find',
                '/dbModel/update',
                '/dbModel/delete',
                '/dbModel/save',
            ],
            'task'  => [
                '/task/getListByCo',
                '/task/deleteByCo',
                '/task/getListByAsync',
                '/task/deleteByAsync',
            ],
            'rpc'   => [
                '/rpc/getList',
                '/rpc/returnBool',
                '/rpc/bigString',
            ],
            'co'    => [
                '/co/multi'
            ]
        ];
    }
}
