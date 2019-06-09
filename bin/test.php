<?php

$dns = 'mysql:dbname=test;host=172.17.0.4';
$dns = 'mysql:host=172.17.0.4;dbname=test';

$paramsStr = parse_url($dns, PHP_URL_PATH);
$paramsAry = explode(';', $paramsStr);

$params = [];
foreach ($paramsAry as $param) {
    [$key, $value] = explode('=', $param);
    $params[$key] = $value;
}

var_dump($params);