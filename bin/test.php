<?php
//$str = sprintf('

//', \swoft\console\style\LiteStyle::render('0.0.0.0', \swoft\console\style\LiteStyle::BG_BLUE));




//exit();
$options = 'd';
var_dump($_SERVER['argv']);
var_dump(getopt($options));
exit();
// 1. *.html
// 2. /*
// 3. /a/b /b/c

$s = microtime(true);
$a = '*.html';
$b = '/*/a';
$c = "/a/b/c.html";



$a = str_replace(".", '\.', $a);
$b = str_replace(".", '\.', $b);
$c = str_replace(".", '\.', $c);

$a = str_replace("*", '.*', $a);
$b = str_replace("*", '.*', $b);
$c = str_replace("*", '.*', $c);

$a = str_replace("/", '\/', $a);
$b = str_replace("/", '\/', $b);
$c = str_replace("/", '\/', $c);



var_dump($a, $b, $c);
//var_dump(explode(".", $a));
//var_dump(explode(".", $b));
//var_dump(explode(".", $c));


$result = preg_match('/'.$c.'/', "/a/b/chtml", $match);
var_dump($result);

$a = '*.html';
$b = '/*/a';
$c = "/a/b/c.html";

$a = str_replace(".", '\.', $a);
$b = str_replace(".", '\.', $b);
$c = str_replace(".", '\.', $c);

$a = str_replace("*", '.*', $a);
$b = str_replace("*", '.*', $b);
$c = str_replace("*", '.*', $c);

$a = str_replace("/", '\/', $a);
$b = str_replace("/", '\/', $b);
$c = str_replace("/", '\/', $c);



var_dump($a, $b, $c);
//var_dump(explode(".", $a));
//var_dump(explode(".", $b));
//var_dump(explode(".", $c));


$result = preg_match('/'.$c.'/', "/a/b/chtml", $match);

var_dump($result);

$e = microtime(true);

var_dump($s, $e);
echo $e-$s;
