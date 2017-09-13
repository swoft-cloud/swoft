<?php
return \Swoft\Helper\ArrayHelper::merge(
    require_once __DIR__ . "/../properties.php",
    [
        "version" => '1.0',
        'env'     => 'dev'
    ]
);
