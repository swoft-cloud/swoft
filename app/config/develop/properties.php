<?php
return \swoft\helpers\ArrayHelper::merge(
    require_once __DIR__ . "/../properties.php",
    [
        "version" => '1.0',
        'env'     => 'dev'
    ]
);