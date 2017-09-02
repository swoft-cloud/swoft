<?php

function test($a, ...$params)
{
    var_dump($a, $params);
}


test(1,1,2,3,3);