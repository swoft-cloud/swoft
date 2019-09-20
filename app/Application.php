<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App;

use Swoft\SwoftApplication;
use function date_default_timezone_set;

/**
 * Class Application
 *
 * @since 2.0
 */
class Application extends SwoftApplication
{
    protected function beforeInit(): void
    {
        parent::beforeInit();

        // you can init php setting.
        date_default_timezone_set('Asia/Shanghai');
    }

    /**
     * @return array
     */
    public function getCLoggerConfig(): array
    {
        $config = parent::getCLoggerConfig();

        // False: Dont print log to terminal
        $config['enable'] = true;

        return $config;
    }
}
