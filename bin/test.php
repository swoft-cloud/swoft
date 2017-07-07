<?php

// *.开头文件扩展匹配
$reg = '/^\{(.*)\}$/';
$str = "{stelin\}";

$result = preg_match($reg,$str, $match);
var_dump($result, $match);
exit();

class Breaker{
    public $state = null;
    public function __construct()
    {
        $this->state = new StateA($this);
    }

    public function mvToB()
    {
        $this->state = new StateB($this);
    }

    public function doCall()
    {
        $this->state->doCall();
    }

}
class State{
    /**
     * @var Breaker
     */
    public $breaker = null;
    function __construct($breaker)
    {
        $this->breaker = $breaker;
    }
}

class StateA extends State {
    public function doCall()
    {
        $this->breaker->mvToB();
        echo "stateA\n";
    }
}

class StateB extends State {
    public function doCall()
    {
        echo "stateB\n";
    }
}

$b = new Breaker();

$b->state->doCall();
$b->state->doCall();














//$a = new SplQueue();
//
//var_dump($a->shift());
exit();
// 全匹配

$str = "/login/index/";
$str = str_replace('/', '\/', $str);
$str = '/^'.$str.'$/';
var_dump(preg_match($str, '/login/index/'));
var_dump($result);

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