<?php

namespace Swoft\Db;

/**
 * 查询器父类
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

    /**
     * 左括号
     */
    const BRACKET_OPEN = "(";

    /**
     * 右括号
     */
    const BRACKET_CLOSE = ")";

    /**
     * 修饰符in
     */
    const IN = "IN";

    /**
     * 修饰符not in
     */
    const NOT_IN = "NOT IN";

    /**
     * 修饰符like
     */
    const LIKE = "LIKE";

    /**
     * 修饰符in
     */
    const NOT_LIKE = "NOT LIKE";

    /**
     * 修饰符between
     */
    const BETWEEN = "BETWEEN";

    /**
     * 修饰符not between
     */
    const NOT_BETWEEN = "NOT BETWEEN";

    /**
     * 内连接
     */
    const INNER_JOIN = "INNER JOIN";

    /**
     * 左连接
     */
    const LEFT_JOIN = "LEFT JOIN";

    /**
     * 右连接
     */
    const RIGHT_JOIN = "RIGHT JOIN";

    /**
     * 逻辑运算符and
     */
    const LOGICAL_AND = "AND";

    /**
     * 逻辑运算符or
     */
    const LOGICAL_OR = "OR";

    /**
     * is判断语句
     */
    const IS = "IS";

    /**
     * is not 判断语句
     */
    const IS_NOT = "IS NOT";

    /**
     * SQL语句
     */
    use Statement;

    /**
     * 插入表名
     *
     * @var string
     */
    private $insert;

    /**
     * 更新表名
     *
     * @var string
     */
    private $update;

    /**
     * 是否是delete
     *
     * @var bool
     */
    private $delete = false;

    /**
     * select语句
     *
     * @var array
     */
    private $select = [];

    /**
     * set语句
     *
     * @var array
     */
    private $set = [];


    /**
     * from语句
     *
     * @var array
     */
    private $from = [];

    /**
     * join语句
     *
     * @var array
     */
    private $join = [];

    /**
     * where语句
     *
     * @var array
     */
    private $where = [];

    /**
     * group by语句
     *
     * @var array
     */
    private $groupBy = [];

    /**
     * having语句
     *
     * @var array
     */
    private $having = [];

    /**
     * order by 语句
     *
     * @var array
     */
    private $orderBy = [];

    /**
     * limit 语句
     *
     * @var array
     */
    private $limit = [];

    /**
     * 参数集合
     *
     * @var array
     */
    private $parameters = [];


    public function __construct()
    {

    }

    /**
     * insert语句
     *
     * @param string $tableName
     *
     * @return AbstractQueryBuilder
     */
    public function insert(string $tableName)
    {
        $this->insert = $tableName;
        return $this;
    }

    /**
     * update语句
     *
     * @param string $tableName
     *
     * @return AbstractQueryBuilder
     */
    public function update(string $tableName)
    {
        $this->update = $tableName;
        return $this;
    }

    /**
     * delete语句
     *
     * @return AbstractQueryBuilder
     */
    public function delete()
    {
        $this->delete = true;
        return $this;
    }

    /**
     * select语句
     *
     * @param string $column
     * @param string $alias
     *
     * @return AbstractQueryBuilder
     */
    public function select(string $column, string $alias = null)
    {
        $this->select[$column] = $alias;
        return $this;
    }


    /**
     * from语句
     *
     * @param string      $table
     * @param string|null $alias
     *
     * @return AbstractQueryBuilder
     */
    public function from(string $table, string $alias = null)
    {
        $this->from['table'] = $table;
        $this->from['alias'] = $alias;
        return $this;
    }

    /**
     * inner join语句
     *
     * @param string       $table
     * @param string|array $criteria
     * @param string       $alias
     *
     * @return AbstractQueryBuilder
     */
    public function innerJoin(string $table, $criteria = null, string $alias = null)
    {
        $this->join($table, $criteria, self::INNER_JOIN, $alias);
        return $this;
    }

    /**
     * left join语句
     *
     * @param string       $table
     * @param string|array $criteria
     * @param string       $alias
     *
     * @return AbstractQueryBuilder
     */
    public function leftJoin(string $table, $criteria = null, string $alias = null)
    {
        $this->join($table, $criteria, self::LEFT_JOIN, $alias);
        return $this;
    }

    /**
     * right join语句
     *
     * @param string       $table
     * @param string|array $criteria
     * @param string       $alias
     *
     * @return AbstractQueryBuilder
     */
    public function rightJoin(string $table, $criteria = null, string $alias = null)
    {
        $this->join($table, $criteria, self::RIGHT_JOIN, $alias);
        return $this;
    }

    /**
     * where语句
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @param string $connector
     *
     * @return AbstractQueryBuilder
     */
    public function where(string $column, $value, $operator = self::OPERATOR_EQ, $connector = self::LOGICAL_AND)
    {
        $this->criteria($this->where, $column, $value, $operator, $connector);
        return $this;
    }

    /**
     * where and 语句
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     *
     * @return AbstractQueryBuilder
     */
    public function andWhere(string $column, $value, $operator = self::OPERATOR_EQ)
    {
        $this->criteria($this->where, $column, $value, $operator, self::LOGICAL_AND);
        return $this;
    }

    /**
     * where条件中，括号开始(左括号)
     *
     * @param string $connector
     *
     * @return AbstractQueryBuilder
     */
    public function openWhere($connector = self::LOGICAL_AND) {
        return $this->bracketCriteria($this->where, self::BRACKET_OPEN, $connector);
    }

    /**
     * where条件中，括号结束(右括号)
     *
     * @return AbstractQueryBuilder
     */
    public function closeWhere() {
        return $this->bracketCriteria($this->where, self::BRACKET_CLOSE);
    }

    /**
     * where or 语句
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     *
     * @return AbstractQueryBuilder
     */
    public function orWhere($column, $value, $operator = self::OPERATOR_EQ)
    {
        $this->criteria($this->where, $column, $value, $operator, self::LOGICAL_OR);
        return $this;
    }

    /**
     * where in 语句
     *
     * @param string $column
     * @param array  $values
     * @param string $connector
     *
     * @return AbstractQueryBuilder
     */
    public function whereIn(string $column, array $values, string $connector = self::LOGICAL_AND)
    {
        $this->criteria($this->where, $column, $values, self::IN, $connector);
        return $this;
    }

    /**
     * where not in 语句
     *
     * @param string $column
     * @param array  $values
     * @param string $connector
     *
     * @return AbstractQueryBuilder
     */
    public function whereNotIn(string $column, array $values, string $connector = self::LOGICAL_AND)
    {
        $this->criteria($this->where, $column, $values, self::NOT_IN, $connector);
        return $this;
    }

    /**
     * between语句
     *
     * @param string $column
     * @param mixed  $min
     * @param mixed  $max
     * @param string $connector
     *
     * @return AbstractQueryBuilder
     */
    public function whereBetween(string $column, $min, $max, string $connector = self::LOGICAL_AND)
    {
        $this->criteria($this->where, $column, array($min, $max), self::BETWEEN, $connector);
        return $this;
    }

    /**
     * not between语句
     *
     * @param string $column
     * @param mixed  $min
     * @param mixed  $max
     * @param string $connector
     *
     * @return AbstractQueryBuilder
     */
    public function whereNotBetween(string $column, $min, $max, string $connector = self::LOGICAL_AND)
    {
        $this->criteria($this->where, $column, array($min, $max), self::NOT_BETWEEN, $connector);
        return $this;
    }

    /**
     * having语句
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @param string $connector
     *
     * @return AbstractQueryBuilder
     */
    public function having(string $column, $value, string $operator = self::OPERATOR_EQ, string $connector = self::LOGICAL_AND)
    {
        $this->criteria($this->having, $column, $value, $operator, $connector);
        return $this;
    }

    /**
     * having and 语句
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     *
     * @return AbstractQueryBuilder
     */
    public function andHaving(string $column, $value, string $operator = self::OPERATOR_EQ)
    {
        $this->criteria($this->having, $column, $value, $operator, self::LOGICAL_AND);
        return $this;
    }

    /**
     * having or 语句
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     *
     * @return AbstractQueryBuilder
     */
    public function orHaving(string $column, $value, string $operator = self::OPERATOR_EQ)
    {
        $this->criteria($this->having, $column, $value, $operator, self::LOGICAL_OR);
        return $this;
    }

    /**
     * having in 语句
     *
     * @param string $column
     * @param array  $values
     * @param string $connector
     *
     * @return AbstractQueryBuilder
     */
    public function havingIn(string $column, array $values, string $connector = self::LOGICAL_AND)
    {
        $this->criteria($this->having, $column, $values, self::IN, $connector);
        return $this;
    }

    /**
     * having not in 语句
     *
     * @param string $column
     * @param array  $values
     * @param string $connector
     *
     * @return AbstractQueryBuilder
     */
    public function havingNotIn(string $column, array $values, string $connector = self::LOGICAL_AND)
    {
        $this->criteria($this->having, $column, $values, self::NOT_IN, $connector);
        return $this;
    }

    /**
     * having between语句
     *
     * @param string $column
     * @param mixed  $min
     * @param mixed  $max
     * @param string $connector
     *
     * @return AbstractQueryBuilder
     */
    public function havingBetween(string $column, $min, $max, string $connector = self::LOGICAL_AND)
    {
        $this->criteria($this->having, $column, array($min, $max), self::BETWEEN, $connector);
        return $this;
    }

    /**
     * having not between语句
     *
     * @param string $column
     * @param mixed  $min
     * @param mixed  $max
     * @param string $connector
     *
     * @return AbstractQueryBuilder
     */
    public function havingNotBetween(string $column, $min, $max, string $connector = self::LOGICAL_AND)
    {
        $this->criteria($this->having, $column, array($min, $max), self::NOT_BETWEEN, $connector);
        return $this;
    }

    /**
     * having，括号开始(左括号)
     *
     * @param string $connector
     *
     * @return AbstractQueryBuilder
     */
    public function openHaving($connector = self::LOGICAL_AND) {
        return $this->bracketCriteria($this->having, self::BRACKET_OPEN, $connector);
    }

    /**
     * having，括号开始(右括号)
     *
     * @return AbstractQueryBuilder
     */
    public function closeHaving() {
        return $this->bracketCriteria($this->having, self::BRACKET_CLOSE);
    }

    /**
     * group by语句
     *
     * @param string $column
     * @param string $order
     *
     * @return AbstractQueryBuilder
     */
    public function groupBy(string $column, string $order = null)
    {
        $this->groupBy[] = array(
            'column' => $column,
            'order'  => $order
        );
        return $this;
    }

    /**
     * order by语句
     *
     * @param string $column
     * @param string $order
     *
     * @return AbstractQueryBuilder
     */
    public function orderBy(string $column, string $order = self::ORDER_BY_ASC)
    {
        $this->orderBy[] = array(
            'column' => $column,
            'order'  => $order
        );

        return $this;
    }

    /**
     * limit语句
     *
     * @param int $limit
     * @param int $offset
     *
     * @return AbstractQueryBuilder
     */
    public function limit(int $limit, $offset = 0)
    {
        $this->limit['limit'] = $limit;
        $this->limit['offset'] = $offset;
        return $this;
    }

    /**
     * set语句
     *
     * @param mixed $column
     * @param mixed $value
     *
     * @return AbstractQueryBuilder
     */
    public function set($column, $value = null)
    {
        if (!is_array($column)) {
            $this->set[] = array(
                'column' => $column,
                'value'  => $value
            );
            return $this;
        }

        foreach ($column as $columnName => $columnValue) {
            $this->set($columnName, $columnValue);
        }

        return $this;
    }

    /**
     * 设置参数
     *
     * @param mixed  $key
     * @param mixed  $value
     * @param string $type
     *
     * @return AbstractQueryBuilder
     */
    public function setParameter($key, $value, $type = null)
    {
        if(!is_int($key)){
            $key = ":".$key;
        }

        if($type == "string" || ($type == null && is_string($value))){
            $value = '"'.$value.'"';
        }
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * 括号条件组拼
     *
     * @param array  $criteria
     * @param string $bracket
     * @param string $connector
     *
     * @return AbstractQueryBuilder
     */
    private function bracketCriteria(array &$criteria, string $bracket = self::BRACKET_OPEN, string $connector = self::LOGICAL_AND)
    {
        $criteria[] = array(
            'bracket'   => $bracket,
            'connector' => $connector
        );

        return $this;
    }

    /**
     * join数据组装
     *
     * @param string       $table
     * @param string|array $criteria
     * @param string       $type
     * @param string       $alias
     *
     * @return AbstractQueryBuilder
     */
    private function join(string $table, $criteria = null, string $type = self::INNER_JOIN, string $alias = null)
    {
        // 是否存在判断...

        if (is_string($criteria)) {
            $criteria = array($criteria);
        }

        $this->join[] = array(
            'table'    => $table,
            'criteria' => $criteria,
            'type'     => $type,
            'alias'    => $alias
        );
        return $this;
    }

    /**
     * 条件组装
     *
     * @param array  $criteria
     * @param string $column
     * @param mixed $value
     * @param string $operator
     * @param string $connector
     *
     * @return AbstractQueryBuilder
     */
    private function criteria(
        array &$criteria,
        string $column,
        $value,
        string $operator = self::OPERATOR_EQ,
        string $connector = self::LOGICAL_AND
    ) {
        $criteria[] = array(
            'column'    => $column,
            'value'     => $value,
            'operator'  => $operator,
            'connector' => $connector,
        );
        return $this;
    }
}