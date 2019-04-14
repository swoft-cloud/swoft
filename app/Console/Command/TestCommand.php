<?php declare(strict_types=1);


namespace App\Console\Command;

use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;

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
        $type = \input()->get('type', '');
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
            $shell = \sprintf('ab -n 10000 -c 2000  127.0.0.1:18306%s', $uri);

            \output()->writeln('执行URL:' . $shell . PHP_EOL);
            exec($shell, $result);
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
            ]
        ];
    }
}