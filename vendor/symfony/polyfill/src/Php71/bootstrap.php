<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Polyfill\Php71 as p;

if (PHP_VERSION_ID < 70100) {
    if (!function_exists('is_iterable')) {
        function is_iterable($var) { return p\Php71::is_iterable($var); }
    }
}
