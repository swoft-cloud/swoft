<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Parse express language
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

$data = ['life' => 10, 'universe' => 10, 'everything' => 22];


$el = new ExpressionLanguage();
var_dump($el->evaluate(
    "name~':'~(uid+bid)",
    [
        'name' => 'swoft',
        'uid' => 12,
        'bid' => 11
    ]
));