<?php

namespace Swoft\Server\Booting;

use Dotenv\Dotenv;
use Swoft\App;

/**
 * @uses      LoadEnv
 * @version   2017-11-02
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class LoadEnv implements Bootable
{

    public function bootstrap()
    {
        $file = '.env';
        $filePath = App::getAlias('@root') . DS . $file;
        if (file_exists($filePath) && is_readable($filePath)) {
            (new Dotenv(App::getAlias('@root'), $file))->load();
        }
    }
}