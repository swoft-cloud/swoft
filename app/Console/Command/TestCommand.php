<?php declare(strict_types=1);


namespace App\Console\Command;

use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use function input;
use function output;
use function sprintf;
use Swoft\Console\Annotation\Mapping\CommandOption;
use Swoft\Console\Exception\ConsoleErrorException;
use Swoft\Console\Helper\Show;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Http\Server\Router\Route;
use Swoole\Client;

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
            $curlResult = null;
            $abShell    = sprintf('ab -k -n 10000 -c 2000  127.0.0.1:18306%s', $uri);
            $curlShell  = sprintf('curl 127.0.0.1:18306%s', $uri);

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
            'redis'   => [
                '/redis/str',
                '/redis/et',
                '/redis/ep',
                '/redis/release',
                '/redis/poolSet',
                '/redis/set',
            ],
            'log'     => [
                '/log/test'
            ],
            'db'      => [
                '/dbTransaction/ts',
                '/dbTransaction/cm',
                '/dbTransaction/rl',
                '/dbTransaction/ts2',
                '/dbTransaction/cm2',
                '/dbTransaction/rl2',
                '/dbTransaction/multiPool',
                '/dbModel/find',
                '/dbModel/update',
                '/dbModel/delete',
                '/dbModel/save',
                '/dbModel/batchUpdate',
                '/selectDb/modelNotExistDb',
                '/selectDb/queryNotExistDb',
                '/selectDb/dbNotExistDb',
                '/selectDb/modelDb',
                '/selectDb/queryDb',
                '/selectDb/dbDb',
                '/selectDb/select',
                '/builder/schema'
            ],
            'task'    => [
                '/task/getListByCo',
                '/task/deleteByCo',
                '/task/getListByAsync',
                '/task/deleteByAsync',
                '/task/returnNull',
                '/task/returnVoid',
            ],
            'rpc'     => [
                '/rpc/getList',
                '/rpc/returnBool',
                '/rpc/bigString',
                '/rpc/sendBigString',
                '/rpc/returnNull'
            ],
            'co'      => [
                '/co/multi'
            ],
            'bean'    => [
                '/bean/request'
            ],
            'breaker' => [
                '/breaker/unbreak',
                '/breaker/breaked',
                '/breaker/loopBreaker'
            ],
            'limiter' => [
                '/limiter/requestLimiter',
                '/limiter/requestLimiter2',
                '/limiter/paramLimiter?id=12',
            ]
        ];
    }

    /**
     * Mock request some api for test server
     *
     * @CommandMapping("ca")
     */
    public function checkAccess(): void
    {
        \bean('httpRouter')->each(function (Route $route) {
            $path = $route->getPath();

            // Skip some routes
            if ($route->getMethod() !== 'GET' || false !== \strpos($path, '{')) {
                return;
            }

            $command = sprintf('curl -I 127.0.0.1:18306%s', $path);
            Show::colored('> ' . $command);
            \exec($command);
        });
    }

    /**
     * @CommandMapping("err")
     * @throws ConsoleErrorException
     */
    public function error(): void
    {
        ConsoleErrorException::throw('this is an error message');
    }

    /**
     * @CommandMapping(desc="connect to an tcp server and allow send message interactive")
     * @CommandOption("host", short="H", desc="the tcp server host address", default="127.0.0.1", type="string")
     * @CommandOption("port", short="p", desc="the tcp server port number", default="18309", type="integer")
     *
     * @param Input  $input
     * @param Output $output
     */
    public function tcp(Input $input, Output $output): void
    {
        $cli  = new Client(\SWOOLE_SOCK_TCP);
        $host = $input->getSameOpt(['host', 'H'], '127.0.0.1');
        $port = $input->getSameOpt(['port', 'p'], 18309);

        if (!$ok = $cli->connect((string)$host, (int)$port, 5.0)) {
            $code = $cli->errCode;
            $msg  = socket_strerror($code);
            Show::error("Connect failed. Error($code): $msg");
            return;
        }

        $addr = $host . ':' . $port;
        $output->colored('Successful connect to tcp server ' . $addr, 'success');

        while (true) {
            if (!$msg = $input->read('> ')) {
                $output->liteWarning('Please input message for send');
                continue;
            }

            // Exit interactive terminal
            if ($msg === 'quit' || $msg === 'exit') {
                $output->colored('Quit, Bye!');
                break;
            }

            $cli->send($msg);

            $res = $cli->recv();
            $output->writef('Return: %s', $res);
        }

        $cli->close();
    }
}
