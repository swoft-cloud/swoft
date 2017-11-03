<?php

namespace Swoft\Server\Booting;

/**
 * @uses      Bootable
 * @version   2017-11-02
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface Bootable
{

    public function bootstrap();

}