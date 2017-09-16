<?php

list($a, $b) = $a;
exit();
$as = [
    ['key', 'value', 'type'],
    ['key', 'value']
];

foreach ($as as $a){
    list($key, $value, $type) = $a;
    var_dump($key, $value, $type);
}

exit();
$pdo = new PDO("mysql:dbname=test;host=127.0.0.1", "root", "123456");
$result = $pdo->query("select * from user");
while ($row = $result->fetch()) {
    var_dump($row);
}
