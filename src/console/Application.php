<?php
namespace swoft\console;

use swoft\console\controllers\ServerController;

/**
 * Class Application
 * @package slimExt\base
 */
class Application extends \inhere\console\App
{
    /**
     * @var array
     */
    protected static $bootstraps = [
        'commands' => [
            // AssetPublishCommand::class,
        ],
        'controllers' => [
            ServerController::class,
            // GeneratorController::class,
        ],
    ];

    protected function init()
    {
        parent::init();

        $this->loadBootstrapCommands();
    }

    /**
     * loadBuiltInCommands
     */
    public function loadBootstrapCommands()
    {
        /** @var \inhere\console\Command $command */
        foreach ((array)static::$bootstraps['commands'] as $command) {
            $this->command($command::getName(), $command);
        }

        /** @var \inhere\console\Controller $controller */
        foreach ((array)static::$bootstraps['controllers'] as $controller) {
            $this->controller($controller::getName(), $controller);
        }
    }
}
