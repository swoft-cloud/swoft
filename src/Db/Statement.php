<?php

namespace Swoft\Db;

/**
 * SQL语句组装
 *
 * @uses      Statement
 * @version   2017年09月07日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
trait Statement
{
    /**
     * 组拼SQL
     *
     * @return string
     */
    public function getStatement()
    {
        $statement = "";
        if ($this->isQuerySql()) {
            $statement = $this->sql;
        } elseif ($this->isSelect()) {
            $statement = $this->getSelectStatement();
        } elseif ($this->isInsert()) {
            $statement = $this->getInsertStatement();
        } elseif ($this->isUpdate()) {
            $statement = $this->getUpdateStatement();
        } elseif ($this->isDelete()) {
            $statement = $this->getDeleteStatement();
        }
        return $statement;
    }

    /**
     * select语句
     *
     * @return string
     */
    protected function getSelectStatement()
    {
        $statement = "";
        if (!$this->isSelect()) {
            return $statement;
        }

        // select语句
        $statement .= $this->getSelectString();

        // from语句
        if ($this->from) {
            $statement .= " " . $this->getFromString();
        }

        // where语句
        if ($this->where) {
            $statement .= " " . $this->getWhereString();
        }

        // groupBy语句
        if ($this->groupBy) {
            $statement .= " " . $this->getGroupByString();
        }

        // having语句
        if ($this->having) {
            $statement .= " " . $this->getHavingString();
        }

        // orderBy语句
        if ($this->orderBy) {
            $statement .= " " . $this->getOrderByString();
        }

        // limit语句
        if ($this->limit) {
            $statement .= " " . $this->getLimitString();
        }

        return $statement;
    }

    /**
     * insert语句
     *
     * @return string
     */
    protected function getInsertStatement()
    {
        $statement = "";
        if (!$this->isInsert()) {
            return $statement;
        }

        // insert语句
        $statement .= $this->getInsertString();

        // set语句
        if ($this->set) {
            $statement .= " " . $this->getSetString();
        }

        return $statement;
    }

    /**
     * update语句
     *
     * @return string
     */
    protected function getUpdateStatement()
    {
        $statement = "";
        if (!$this->isUpdate()) {
            return $statement;
        }

        // update语句
        $statement .= $this->getUpdateString();

        // set语句
        if ($this->set) {
            $statement .= " " . $this->getSetString();
        }

        // where语句
        if ($this->where) {
            $statement .= " " . $this->getWhereString();
        }

        // orderBy语句
        if ($this->orderBy) {
            $statement .= " " . $this->getOrderByString();
        }

        // limit语句
        if ($this->limit) {
            $statement .= " " . $this->getLimitString();
        }

        return $statement;
    }

    /**
     * delete语句
     *
     * @return string
     */
    protected function getDeleteStatement()
    {
        $statement = "";
        if (!$this->isDelete()) {
            return $statement;
        }

        // delete语句
        $statement .= $this->getDeleteString();

        // from语句
        if ($this->from) {
            $statement .= " " . $this->getFromString();
        }

        // where语句
        if ($this->where) {
            $statement .= " " . $this->getWhereString();
        }

        // orderBy语句
        if ($this->orderBy) {
            $statement .= " " . $this->getOrderByString();
        }

        // limit语句
        if ($this->limit) {
            $statement .= " " . $this->getLimitString();
        }

        return $statement;
    }

    /**
     * select语句
     *
     * @return string
     */
    protected function getSelectString()
    {
        $statement = "";
        if (empty($this->select)) {
            return $statement;
        }

        // 字段组拼
        foreach ($this->select as $column => $alias) {
            $statement .= $column;
            if ($alias != null) {
                $statement .= " AS " . $alias;
            }
            $statement .= ", ";
        }

        //select组拼
        $statement = substr($statement, 0, -2);
        if (!empty($statement)) {
            $statement = "SELECT " . $statement;
        }

        return $statement;
    }

    /**
     * from语句
     *
     * @return string
     */
    public function getFromString()
    {

        $statement = "";
        if (empty($this->from)) {
            return $statement;
        }

        // from语句
        $statement .= $this->getFrom();
        $fromAlias = $this->getFromAlias();

        if ($fromAlias != null) {
            $statement .= " AS " . $fromAlias;
        }
        // join语句
        $statement .= " " . $this->getJoinString();
        $statement = rtrim($statement);

        if (!empty($statement)) {
            $statement = "FROM " . $statement;
        }

        return $statement;
    }

    /**
     * join语句
     *
     * @return string
     */
    protected function getJoinString()
    {
        $statement = "";
        foreach ($this->join as $i => $join) {

            // join信息
            $type = $join['type'];
            $table = $join['table'];
            $alias = $join['alias'];
            $criteria = $join['criteria'];

            // join类型
            $statement .= " " . $type . " " . $table;
            if ($alias != null) {
                $statement .= " AS " . $alias;
            }

            // join条件
            if ($criteria != null) {
                if ($alias != null) {
                    $table = $alias;
                }
                $statement = $this->getJoinCriteria($i, $table, $statement, $criteria);
            }
        }

        $statement = trim($statement);
        return $statement;
    }

    /**
     * join条件
     *
     * @param int    $joinIndex
     * @param string $table
     * @param string $statement
     * @param  array $criteria
     *
     * @return string
     */
    protected function getJoinCriteria(int $joinIndex, string $table, string $statement, array $criteria)
    {
        $statement .= " ON ";
        foreach ($criteria as $x => $criterion) {
            // 多个条件连接使用and逻辑符号
            if ($x != 0) {
                $statement .= " " . self::LOGICAL_AND . " ";
            }

            // 条件里面不包含'='符号,默认关联上一个join表
            if (strpos($criterion, '=') === false) {
                $statement .= $this->getJoinCriteriaUsingPreviousTable($joinIndex, $table, $criterion);
                continue;
            }
            $statement .= $criterion;
        }
        return $statement;
    }

    /**
     * 前一个join条件
     *
     * @param int    $joinIndex
     * @param string $table
     * @param string $column
     *
     * @return string
     */
    protected function getJoinCriteriaUsingPreviousTable(int $joinIndex, string $table, string $column)
    {
        $joinCriteria = "";
        $previousJoinIndex = $joinIndex - 1;

        if (array_key_exists($previousJoinIndex, $this->join)) {
            // 上一个join存在
            $previousTable = $this->join[$previousJoinIndex]['table'];
            if ($this->join[$previousJoinIndex]['alias'] != null) {
                $previousTable = $this->join[$previousJoinIndex]['alias'];
            }
        } elseif ($this->isSelect()) {
            // 查询
            $previousTable = $this->getFrom();
            $alias = $this->getFromAlias();
            if ($alias != null) {
                $previousTable = $alias;
            }
        } elseif ($this->isUpdate()) {
            // 更新
            $previousTable = $this->getUpdate();
        } else {
            $previousTable = false;
        }

        // 上一个inner关联存在
        if ($previousTable) {
            $joinCriteria .= $previousTable . ".";
        }

        $joinCriteria .= $column . " " . self::OPERATOR_EQ . " " . $table . "." . $column;

        return $joinCriteria;
    }

    /**
     * where语句
     *
     * @return string
     */
    protected function getWhereString()
    {
        $statement = $this->getCriteriaString($this->where);

        if (!empty($statement)) {
            $statement = "WHERE " . $statement;
        }

        return $statement;
    }

    /**
     * where条件
     *
     * @param array $criteria
     *
     * @return string
     */
    protected function getCriteriaString(array &$criteria)
    {
        $statement = "";
        $useConnector = false;

        foreach ($criteria as $i => $criterion) {
            // 是括号符
            if (array_key_exists('bracket', $criterion)) {
                if (strcmp($criterion['bracket'], self::BRACKET_OPEN) == 0) {
                    if ($useConnector) {
                        $statement .= " " . $criterion['connector'] . " ";
                    }
                    $useConnector = false;
                } else {
                    $useConnector = true;
                }

                $statement .= $criterion['bracket'];
                continue;
            }

            if ($useConnector) {
                $statement .= " " . $criterion['connector'] . " ";
            }

            // 没有括号
            $useConnector = true;
            $value = $this->getCriteriaWithoutBracket($criterion['operator'], $criterion['value']);
            $statement .= $criterion['column'] . " " . $criterion['operator'] . " " . $value;
        }
        return $statement;
    }

    /**
     * 没有括号条件处理
     *
     * @param string $operator
     * @param  mixed $criterionVaue
     *
     * @return bool|string
     */
    protected function getCriteriaWithoutBracket(string $operator, $criterionVaue)
    {
        switch ($operator) {
            case self::BETWEEN:
            case self::NOT_BETWEEN:
                $end = $this->getQuoteValue($criterionVaue[1]);
                $start = $this->getQuoteValue($criterionVaue[0]);
                $value = $start . " " . self::LOGICAL_AND . " " . $end;
                break;

            case self::IN:
            case self::NOT_IN:
                $value = self::BRACKET_OPEN;
                // 数组处理
                foreach ($criterionVaue as $criterionValue) {
                    $criterionValue = $this->getQuoteValue($criterionValue);
                    $value .= $criterionValue . ", ";
                }
                $value = substr($value, 0, -2);
                $value .= self::BRACKET_CLOSE;
                break;
            case self::IS:
            case self::IS_NOT:
                $value = $criterionVaue;
                $value = $this->getQuoteValue($value);
                break;
            default:
                $value = $criterionVaue;
                $value = $this->getQuoteValue($value);
                break;
        }
        return $value;
    }

    /**
     * group语句
     *
     * @return string
     */
    protected function getGroupByString()
    {
        $statement = "";
        foreach ($this->groupBy as $groupBy) {
            $statement .= $groupBy['column'];
            if ($groupBy['order']) {
                $statement .= " " . $groupBy['order'];
            }
            $statement .= ", ";
        }

        $statement = substr($statement, 0, -2);
        if (!empty($statement)) {
            $statement = "GROUP BY " . $statement;
        }

        return $statement;
    }

    /**
     * having语句
     *
     * @return string
     */
    protected function getHavingString()
    {
        $statement = $this->getCriteriaString($this->having);
        if (!empty($statement)) {
            $statement = "HAVING " . $statement;
        }
        return $statement;
    }

    /**
     * orderBy语句
     *
     * @return string
     */
    protected function getOrderByString()
    {
        $statement = "";
        foreach ($this->orderBy as $orderBy) {
            $statement .= $orderBy['column'] . " " . $orderBy['order'] . ", ";
        }

        $statement = substr($statement, 0, -2);
        if (!empty($statement)) {
            $statement = "ORDER BY " . $statement;
        }

        return $statement;
    }

    /**
     * limit语句
     *
     * @return string
     */
    protected function getLimitString()
    {
        $statement = "";
        if (!$this->limit) {
            return $statement;
        }
        $statement .= $this->limit['limit'];

        if ($this->limit['offset'] !== 0) {
            $statement .= " OFFSET " . $this->limit['offset'];
        }

        if (!empty($statement)) {
            $statement = "LIMIT " . $statement;
        }

        return $statement;
    }

    /**
     * set语句
     *
     * @return string
     */
    protected function getSetString()
    {
        $statement = "";
        foreach ($this->set as $set) {
            $statement .= $set['column'] . " " . self::OPERATOR_EQ . " " . $this->getQuoteValue($set['value']) . ", ";
        }

        $statement = substr($statement, 0, -2);
        if (!empty($statement)) {
            $statement = "SET " . $statement;
        }

        return $statement;
    }

    /**
     * insert语句
     *
     * @return string
     */
    protected function getInsertString()
    {
        $statement = "";
        if (!$this->insert) {
            return $statement;
        }

        $statement .= $this->getInsert();
        if (!empty($statement)) {
            $statement = "INSERT " . $statement;
        }

        return $statement;
    }

    /**
     * update语句
     *
     * @return string
     */
    protected function getUpdateString()
    {
        $statement = "";
        if (!$this->update) {
            return $statement;
        }

        $statement .= $this->getUpdate();

        // join条件
        $statement .= " " . $this->getJoinString();
        $statement = rtrim($statement);
        if (!empty($statement)) {
            $statement = "UPDATE " . $statement;
        }

        return $statement;
    }

    /**
     * delete语句
     *
     * @return string
     */
    protected function getDeleteString()
    {
        $statement = "";

        if (!$this->delete && !$this->isDeleteTableFrom()) {
            return $statement;
        }

        if (is_array($this->delete)) {
            $statement .= implode(', ', $this->delete);
        }

        if (($statement || $this->isDeleteTableFrom())) {
            $statement = "DELETE " . $statement;
            $statement = trim($statement);
        }
        return $statement;
    }

    /**
     * 字符串转换
     *
     * @param $value
     *
     * @return string
     */
    protected function getQuoteValue($value)
    {
        if (is_string($value)) {
            $value = '"' . $value . '"';
        }
        return $value;
    }

    /**
     * insert表
     *
     * @return string
     */
    protected function getInsert()
    {
        return $this->insert;
    }

    /**
     * update表
     *
     * @return mixed
     */
    protected function getUpdate()
    {
        return $this->update;
    }

    /**
     * 是否是select
     *
     * @return bool
     */
    protected function isSelect()
    {
        return !empty($this->select);
    }

    /**
     * 是否是查询SQL
     *
     * @return bool
     */
    protected function isQuerySql()
    {
        return !empty($this->sql);
    }

    /**
     * 是否是insert
     *
     * @return bool
     */
    protected function isInsert()
    {
        return !empty($this->insert);
    }

    /**
     * 是否是删除
     *
     * @return bool
     */
    protected function isDelete()
    {
        return !empty($this->delete);
    }

    /**
     * 是否是删除from
     *
     * @return bool
     */
    protected function isDeleteTableFrom()
    {
        return $this->delete === true;
    }

    /**
     * 是否是update
     *
     * @return bool
     */
    protected function isUpdate()
    {
        return !empty($this->update);
    }

    /**
     * from表
     *
     * @return string
     */
    protected function getFrom()
    {
        return $this->from['table'];
    }

    /**
     * 别名
     *
     * @return string
     */
    protected function getFromAlias()
    {
        return $this->from['alias'];
    }
}

