<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace AppTest\Testing;

use Swoft\SwoftApplication;
use function array_merge;

/**
 * Class TestApplication
 *
 * @since 2.0
 */
class TestApplication extends SwoftApplication
{
    /**
     * @param string $basePath
     * @param array  $config
     *
     * @return SwoftApplication
     */
    public static function new(string $basePath, array $config = []): SwoftApplication
    {
        return new static($basePath, $config);
    }

    /**
     * Class constructor.
     *
     * @param string $basePath
     * @param array  $config
     */
    public function __construct(string $basePath, array $config = [])
    {
        // RUN_SERVER_TEST=ws,tcp,http
        if (!defined('RUN_SERVER_TEST')) {
            define('RUN_SERVER_TEST', (string)getenv('RUN_SERVER_TEST'));
        }

        if (RUN_SERVER_TEST) {
            printf("ENV RUN_SERVER_TEST=%s\n", RUN_SERVER_TEST);
        }

        // tests: disable run console application
        $this->setStartConsole(false);

        $config = array_merge([
            'basePath'            => $basePath,
            'beanFile'            => $basePath . '/app/bean.php',
            'disabledAutoLoaders' => [
                // \App\AutoLoader::class => 1,
            ],
        ], $config);

        parent::__construct($config);
    }

    public function getCLoggerConfig(): array
    {
        $config = parent::getCLoggerConfig();

        // Dont print log to terminal
        // $config['enable'] = false;
        $config['levels'] = 'error';

        return $config;
    }
}
