<?php
\Swoole\Runtime::enableCoroutine();

$http = new Swoole\Http\Server("0.0.0.0", 88);
$http->on('request', function ($request, $response) {
    $pdo = new \PDO('mysql:dbname=test;host=172.17.0.2', 'root', 'swoft123456');

    for ($i = 0; $i < 30000; $i++) {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('select * from `user` where `user`.`id` = 22 limit 1');
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $result = $stmt->fetchAll();

//    var_dump($result);

        $pdo->rollBack();
    }


    $response->end("<h1>Hello Swoole. #" . rand(1000, 9999) . "</h1>");
});
$http->start();

class Pool
{
    private static $pdo;

    /**
     * @return PDO
     */
    public static function getPdo(): \PDO
    {
        if (!empty(self::$pdo)) {
            return self::$pdo;
        }

        self::$pdo = new \PDO('mysql:dbname=test;host=172.17.0.2', 'root', 'swoft123456');

        return self::$pdo;
    }
}