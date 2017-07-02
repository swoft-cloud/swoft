<?php
class A{
    public $count = 0;

    public function doFun()
    {
        $this->count++;
        echo "count=".$this->count."\n";
    }
}

$a = new A();

$a->doFun();
$a->doFun();
$a->doFun();
$a->doFun();
$a->doFun();
$a->doFun();