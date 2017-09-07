<?php
use Swoft\Db\Mysql\QueryBuilder;
require_once __DIR__. '/bootstrap.php';

$queryBuilder = new \Swoft\Db\Mysql\QueryBuilder();
$sql = $queryBuilder
    ->select("*")
    ->from("A", 'a')
    ->innerJoin("B", 'id', 'b')
    ->innerJoin("C", ['id','name', 'a.age=b.age'], 'c')
    ->leftJoin("D", 'id', 'd')
    ->leftJoin("E", ['id','name'], 'e')
    ->rightJoin("F", 'id', 'f')
    ->rightJoin("G", ['id','name'], 'g')
    ->where("name", "stelin")
    ->openWhere(QueryBuilder::LOGICAL_OR)
    ->where("name2", 'name2', QueryBuilder::OPERATOR_GT, QueryBuilder::LOGICAL_OR)
    ->where("name3", 'name3', QueryBuilder::OPERATOR_GT, QueryBuilder::LOGICAL_AND)
    ->andWhere("sex", 1, QueryBuilder::OPERATOR_GT)
    ->whereIn("hoby", [1,2,3],QueryBuilder::LOGICAL_OR)
    ->whereNotIn("hoby2", ['stelin', 'stelin'],QueryBuilder::LOGICAL_OR)
    ->closeWhere()
    ->where("name4", 'name4', QueryBuilder::OPERATOR_GT, QueryBuilder::LOGICAL_OR)
    ->whereBetween("date", 12, 19)
    ->whereBetween("date2", 'a', 'c', QueryBuilder::LOGICAL_OR)
    ->groupBy("C.id", QueryBuilder::ORDER_BY_ASC)
    ->groupBy("D.name", QueryBuilder::ORDER_BY_DESC)
    ->having("name", "stelin")
    ->openhaving(QueryBuilder::LOGICAL_OR)
    ->having("name2", 'name2', QueryBuilder::OPERATOR_GT, QueryBuilder::LOGICAL_OR)
    ->having("name3", 'name3', QueryBuilder::OPERATOR_GT, QueryBuilder::LOGICAL_AND)
    ->andhaving("sex", 1, QueryBuilder::OPERATOR_GT)
    ->havingIn("hoby", [1,2,3],QueryBuilder::LOGICAL_OR)
    ->havingNotIn("hoby2", ['stelin', 'stelin'],QueryBuilder::LOGICAL_OR)
    ->closehaving()
    ->having("name4", 'name4', QueryBuilder::OPERATOR_GT, QueryBuilder::LOGICAL_OR)
    ->havingBetween("date", 12, 19)
    ->havingBetween("date2", 'a', 'c', QueryBuilder::LOGICAL_OR)
    ->orderBy("a.id")
    ->orderBy("c.id", QueryBuilder::ORDER_BY_DESC)
    ->limit(10, 100)
    ->getResult();

$queryBuilder = new \Swoft\Db\Mysql\QueryBuilder();
$sql = $queryBuilder
    ->update("a")
    ->set('id', 1)
    ->set('name', 'name')
    ->where("id", 10)
    ->getResult();

$queryBuilder = new \Swoft\Db\Mysql\QueryBuilder();
$sql = $queryBuilder
    ->insert("a")
    ->set('id', 1)
    ->set('name', 'name')
    ->getResult();

$queryBuilder = new \Swoft\Db\Mysql\QueryBuilder();
$sql = $queryBuilder
    ->delete()
    ->from("user")
    ->where('a', 'a')
    ->where('b', 1)
    ->getResult();

echo $sql."\n";




