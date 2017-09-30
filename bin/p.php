<?php

$pdo = new PDO("mysql:dbname=test;host=127.0.0.1", "root", "123456");
$stm = $pdo->prepare("select * from count where uid=?");
$stm->execute([1 => 424]);

var_dump($stm->fetchAll());
