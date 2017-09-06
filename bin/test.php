<?php

$queryBuilder = new \Swoft\Db\Mysql\QueryBuilder();

$queryBuilder->insert("table")
    ->set("key", "value")
    ->set("key2", ":value")
    ->set("key3", "?1")
    ->setParameter("value","value")
    ->setParameter(1,"value")
    ->getResult();

$queryBuilder->update("table")
    ->where("key", 'stelin')
    ->set("key", "value")
    ->set("key2", ":value")
    ->set("key3", "?1")
    ->setParameter("value","value")
    ->setParameter(1,"value")
    ->getResult();


$queryBuilder->delete("table")
    ->where("key", ':stelin')
    ->setParameter("stelin","value")
    ->getResult();


$queryBuilder->select("*")
    ->from("tableName", 't')
    ->where("t.key", ':stelin')
    ->orderBy("name", \Swoft\Db\AbstractQueryBuilder::ORDER_BY_ASC)
    ->setParameter("stelin","value")
    ->getResult();

$queryBuilder->select("*")
    ->from("tableName", 't')
    ->where("t.key", ':stelin')
    ->orderBy("name", \Swoft\Db\AbstractQueryBuilder::ORDER_BY_ASC)
    ->setParameter("stelin","value")
    ->getResult();





