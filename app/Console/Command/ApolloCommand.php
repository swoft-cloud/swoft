<?php declare(strict_types=1);


namespace App\Console\Command;

use ReflectionException;
use Swoft\Apollo\Config;
use Swoft\Apollo\Exception\ApolloException;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Co;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Log\Helper\CLog;

/**
 * Class ApolloCommand
 *
 * @since 2.0
 *
 * @Command("apollo")
 */
class ApolloCommand
{
    /**
     * @Inject()
     *
     * @var Config
     */
    private $config;

    /**
     * @CommandMapping(name="index")
     *
     * @throws ReflectionException
     * @throws ApolloException
     * @throws ContainerException
     */
    public function index(): void
    {
        $namespaces = [
            'application'
        ];

        $this->config->listen($namespaces, [$this, 'updateConfigFile']);
    }

    /**
     * @param array $data
     */
    public function updateConfigFile(array $data): void
    {
        foreach ($data as $namespace => $namespaceData) {
            $configFile = sprintf('@config/%s.php', $namespace);

            $configKVs = $namespaceData['configurations'] ?? '';
            $content   = '<?php return ' . var_export($configKVs, true) . ';';
            Co::writeFile(alias($configFile), $content, FILE_NO_DEFAULT_CONTEXT);

            CLog::info('Apollo 配置文件更新成功！');
        }
    }
}