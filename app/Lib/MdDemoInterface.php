<?php

/*
 * This file is part of Swoft.
 * (c) Swoft <group@swoft.org>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Lib;

/**
 * The middleware interface service
 */
interface MdDemoInterface
{
    public function parentMiddleware();

    public function funcMiddleware();
}