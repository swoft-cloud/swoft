<?php
mb_internal_encoding("UTF-8");
$hostname = gethostname();

if (strpos($hostname, 'dev')) {
    define('APP_ENV', 'develop');
} elseif($hostname === 'swoole') {
    define('APP_ENV', 'testing');
} else {
    define('APP_ENV', 'production');
}