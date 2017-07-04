<?php
require_once __DIR__. '/../vendor/autoload.php';

$logPath = __DIR__."/../runtime/swoft/my_app.log";

ini_set('date.timezone','Asia/Shanghai');

$dateFormat = "Y/m/d H:i:s";
$output = "%datetime% [%level_name%] [%channel%] [logid:ac135959afa9004e8617] [445(ms)] [4(MB)] [/Web/vrOrder/Order] [%extra%] [status=200] [] profile[] counting[]\n";
// finally, create a formatter
$formatter = new \Monolog\Formatter\LineFormatter($output, $dateFormat);

// Create the logger
$logger = new \Monolog\Logger('my_logger');

$stream = new \Monolog\Handler\StreamHandler($logPath, \Monolog\Logger::DEBUG);
$stream->setFormatter($formatter);

// Now add some handlers
$logger->pushHandler($stream);

// You can now use your logger
$logger->info('My logger is now ready', array('username' => 'Seldaek'));
$logger->info('My logger is now ready', array('username' => 'Seldaek'));
$logger->info('My logger is now ready', array('username' => 'Seldaek'));
$logger->info('My logger is now ready', array('username' => 'Seldaek'));
$logger->info('My logger is now ready', array('username' => 'Seldaek'));
$logger->info('My logger is now ready', array('username' => 'Seldaek'));
$logger->info('My logger is now ready', array('username' => 'Seldaek'));
$logger->info('My logger is now ready', array('username' => 'Seldaek'));
$logger->info('My logger is now ready', array('username' => 'Seldaek'));


$logger2 = new \Monolog\Logger('my_logger2');

// Now add some handlers
$logger2->pushHandler(new \Monolog\Handler\StreamHandler($logPath, \Monolog\Logger::DEBUG));

// You can now use your logger
$logger2->info('My logger is now ready2');