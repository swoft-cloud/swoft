<?php

class A
{
    private $a = 10;

}

$rf = new ReflectionClass(A::class);
foreach ($rf->getProperties() as $property){
    $property->setAccessible(true);
    var_dump($property->getValue(new A()));
}
