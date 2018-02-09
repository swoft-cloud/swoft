<?php

namespace App\Lib;

/**
 * The middleware interface service
 */
interface MdDemoInterface
{
    public function parentMiddleware();

    public function funcMiddleware();
}