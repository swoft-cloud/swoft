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
$b = new A();

var_dump(spl_object_hash($a), spl_object_hash($b));