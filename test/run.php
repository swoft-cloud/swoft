<?php
/** For Swoole coroutine tests */

use PHPUnit\TextUI\Command;
use Swoole\Coroutine;
use Swoole\ExitException;

Coroutine::set([
    'log_level'   => SWOOLE_LOG_INFO,
    'trace_flags' => 0
]);

/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
if (version_compare('7.1.0', PHP_VERSION, '>')) {
    fwrite(STDERR,
        sprintf('This version of PHPUnit is supported on PHP 7.1 and PHP 7.2.' . PHP_EOL . 'You are using PHP %s (%s).' . PHP_EOL,
            PHP_VERSION, PHP_BINARY));
    die(1);
}

if (!ini_get('date.timezone')) {
    ini_set('date.timezone', 'UTC');
}

// add loader file
$__loader_file = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($__loader_file)) {
    define('PHPUNIT_COMPOSER_INSTALL', $__loader_file);
}

if (!defined('PHPUNIT_COMPOSER_INSTALL')) {
    fwrite(STDERR,
        "You need to set up the project dependencies using Composer:\n\n"
        . '        composer install' . PHP_EOL . PHP_EOL
        . 'You can learn all about Composer on https://getcomposer.org/.' . PHP_EOL);
    die(1);
}

if (array_reverse(explode('/', __DIR__))[0] ?? '' === 'test') {
    $vendor_dir = dirname(PHPUNIT_COMPOSER_INSTALL);
    $bin_unit   = "{$vendor_dir}/bin/phpunit";
    $unit_uint  = "{$vendor_dir}/phpunit/phpunit/phpunit";
    if (file_exists($bin_unit)) {
        @unlink($bin_unit);
        @symlink(__FILE__, $bin_unit);
    }
    if (file_exists($unit_uint)) {
        @unlink($unit_uint);
        @symlink(__FILE__, $unit_uint);
    }
}

if (!in_array('-c', $_SERVER['argv'], true)) {
    $_SERVER['argv'][] = '-c';
    $_SERVER['argv'][] = dirname(__DIR__) . '/phpunit.xml';
}

require PHPUNIT_COMPOSER_INSTALL;

$status = 0;
\Swoft\Co::run(function () {
    // Status
    global $status;

    try {
        $status = Command::main(false);
    } catch (ExitException $e) {
        $status = $e->getCode();
        echo 'ExitException: ' . $e->getMessage(), "\n";
    }
});

exit($status);
