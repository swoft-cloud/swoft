<?php

namespace Swoft\Db;

/**
 *
 *
 * @uses      AbstractQueryBuilder
 * @version   2017年09月02日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractQueryBuilder implements IQueryBuilder
{
    /**
     * 升序
     */
    const ORDER_BY_ASC = "ASC";

    /**
     * 降序
     */
    const ORDER_BY_DESC = "DESC";


    /**
     * 等于
     */
    const OPERATOR_EQ = "=";

    /**
     * 不等于
     */
    const OPERATOR_NE = "!=";

    /**
     * 小于
     */
    const OPERATOR_LT = "<";

    /**
     * 小于等于
     */
    const OPERATOR_LTE = "<=";

    /**
     * 大于
     */
    const OPERATOR_GT = ">";

    /**
     * 大于等于
     */
    const OPERATOR_GTE = ">=";


    public function __construct()
    {

    }

    /**
     * @param string $tableName
     *
     * @return AbstractQueryBuilder
     */
    public function insert(string $tableName)
    {
        return $this;
    }

    /**
     * @param string $tableName
     *
     * @return AbstractQueryBuilder
     */
    public function update(string $tableName)
    {
        return $this;
    }

    /**
     * @param string $tableName
     *
     * @return AbstractQueryBuilder
     */
    public function delete(string $tableName)
    {
        return $this;
    }

    /**
     * @param $columns
     *
     * @return AbstractQueryBuilder
     */
    public function select($columns)
    {
        return $this;
    }

    /**
     * @param      $table
     * @param null $alias
     *
     * @return AbstractQueryBuilder
     */
    public function from($table, $alias = null)
    {
        return $this;
    }

    public function innerJoin($table, $criteria = null, $alias = null) {
        return $this;
    }

    public function leftJoin($table, $criteria = null, $alias = null) {
        return $this;
    }

    public function rightJoin($table, $criteria = null, $alias = null) {
        return $this;
    }

    public function orderBy($column, $order = self::ORDER_BY_ASC){
        return $this;
    }

    /**
     * @param        $column
     * @param        $value
     * @param string $operator
     *
     * @return AbstractQueryBuilder
     */
    public function where($column, $value, $operator = self::OPERATOR_EQ){
        return $this;
    }

    /**
     * @param        $column
     * @param        $value
     * @param string $operator
     *
     * @return AbstractQueryBuilder
     */
    public function andWhere($column, $value, $operator = self::OPERATOR_EQ){
        return $this;
    }

    /**
     * @param        $column
     * @param        $value
     * @param string $operator
     *
     * @return AbstractQueryBuilder
     */
    public function orWhere($column, $value, $operator = self::OPERATOR_EQ){
        return $this;
    }

    /**
     * @param       $column
     * @param array $values
     *
     * @return AbstractQueryBuilder
     */
    public function whereIn($column, array $values) {
        return $this;
    }

    /**
     * @param       $column
     * @param array $values
     *
     * @return AbstractQueryBuilder
     */
    public function whereNotIn($column, array $values) {
        return $this;
    }

    public function whereBetween($column, $min, $max)
    {
        return $this;
    }

    public function whereNotBetween($column, $min, $max)
    {
        return $this;
    }

    public function groupBy($column, $order = null) {
        return $this;
    }

    public function having($column, $value, $operator = self::OPERATOR_EQ)
    {
        return $this;
    }

    public function andHaving($column, $value, $operator = self::OPERATOR_EQ) {
        return $this;
    }

    public function orHaving($column, $value, $operator = self::OPERATOR_EQ) {
        return $this;
    }

    public function havingIn($column, array $values){
        return $this;
    }

    public function havingNotIn($column, array $values){
        return $this;
    }

    public function havingBetween($column, $min, $max)
    {
        return $this;
    }


    public function havingNotBetween($column, $min, $max)
    {
        return $this;
    }

    public function limit($limit, $offset = 0)
    {
        return $this;
    }

    /**
     *
     * @return AbstractQueryBuilder
     */
    public function set($column, $value)
    {
        return $this;
    }

    public function setParameter($column, $value, $type = null)
    {
        return $this;
    }

    public function setParameters(array $parameters)
    {
        return $this;
    }

    public function getResult()
    {

    }
}