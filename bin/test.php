<?php

$a = new SplQueue();

var_dump($a->shift());
exit();
// 全匹配

$str = "/login/index/";
$str = str_replace('/', '\/', $str);
$str = '/^'.$str.'$/';
var_dump(preg_match($str, '/login/index/'));

exit();

// * 结尾，路径匹配
$reg = '/^(.*)\*$/';
$str = "/a/*";

$result = preg_match($reg,$str, $match);
var_dump($match);
if($result){
    $prefix = $match[1];
    $prefix = str_replace('/', '\/', $prefix);
    $reg2 = '/^'.$prefix.'/';
}

var_dump(preg_match($reg2, '/a/ab.html'));

exit();

// *.开头文件扩展匹配
$reg = '/^\*\.([a-z-A-Z-0-9]*)$/';
$str = "*.html";

$result = preg_match($reg,$str, $match);
if($result){
    $reg2 = '/.*\.'.$match[1].'/';
}

var_dump(preg_match($reg2, '/afa/afafa/fafa.htm'));