<?php
$pdo = new PDO("mysql:dbname=test;host=127.0.0.1","root","123456");
$result = $pdo->query("select * from user");
while($row = $result -> fetch()){
    var_dump($row);
}
