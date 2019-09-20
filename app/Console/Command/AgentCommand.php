<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Console\Command;

use Swoft\Apollo\Config;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Co;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Exception\SwoftException;
use Swoft\Log\Helper\CLog;
use Swoft\WebSocket\Server\WebSocketServer;
use Throwable;

/**
 * Class AgentCommand
 *
 * @since 2.0
 *
 * @Command("agent", desc="this is an agent for Apllo config center")
 */
class AgentCommand
{
    /**
     * @Inject()
     *
     * @var Config
     */
    private $config;

    /**
     * @CommandMapping(name="index")
     */
    public function index(): void
    {
        $namespaces = [
            'application'
        ];

        while (true) {
            try {
                $this->config->listen($namespaces, [$this, 'updateConfigFile']);
            } catch (Throwable $e) {
                CLog::error('Config agent fail(%s %s %d)!', $e->getMessage(), $e->getFile(), $e->getLine());
            }
        }
    }

    /**
     * @param array $data
     *
     * @throws SwoftException
     */
    public function updateConfigFile(array $data): void
    {
        foreach ($data as $namespace => $namespaceData) {
            $configFile = sprintf('@config/%s.php', $namespace);

            $configKVs = $namespaceData['configurations'] ?? '';
            $content   = '<?php return ' . var_export($configKVs, true) . ';';
            Co::writeFile(alias($configFile), $content, FILE_NO_DEFAULT_CONTEXT);

            CLog::info('Apollo update success！');

            //            /** @var HttpServer $server */
            //            $server = bean('httpServer');
            //            $server->restart();

            //            /** @var ServiceServer $server */
            //            $server = bean('rpcServer');
            //            $server->restart();

            /** @var WebSocketServer $server */
            $server = bean('wsServer');
            $server->restart();
        }
    }
}
