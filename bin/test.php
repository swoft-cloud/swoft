<?php
$r = new \ReflectionClass(\Redis::class);

$cms = [];
foreach ($r->getMethods() as $method) {
    $cms[] = strtolower($method->getName());
}

//var_dump($cms);

$rc = new \ReflectionClass(\RedisCluster::class);

$rcs = [];
foreach ($rc->getMethods() as $method) {
    $rcs[] = strtolower($method->getName());
}
//var_dump(count($rcs));

echo '[';
foreach (array_intersect($cms, $rcs) as $a){
    echo "'$a',".PHP_EOL;
}
echo ']';