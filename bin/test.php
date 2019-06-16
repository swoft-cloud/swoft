<?php

trait  Tr
{
    public function traitMethod()
    {

    }
}

class A
{
    use Tr;

    public function method()
    {

    }
}

$a = new ReflectionClass(A::class);
foreach ($a->getTraits() as $traitClass){
    var_dump($traitClass->getMethods());
}