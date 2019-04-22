<?php

$fruits = array("apple","banana","pear");
$numbered = array("1","2","3","pear");
$cards = array_merge($fruits, $numbered);
print_r($cards);
print_r(array_unique($cards));