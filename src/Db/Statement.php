<?php

namespace Swoft\Db;

/**
 * SQL语句
 *
 * @uses      Statement
 * @version   2017年09月07日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
trait Statement
{
    public function getInsert()
    {
        return $this->insert;
    }

    public function getUpdate()
    {
        return $this->update;
    }

    public function isSelect()
    {
        return !empty($this->select);
    }

    public function isQuerySql()
    {
        return !empty($this->sql);
    }

    public function isUpdate()
    {
        return !empty($this->update);
    }

    public function isInsert()
    {
        return !empty($this->insert);
    }

    public function isDelete()
    {
        return !empty($this->delete);
    }

    private function isDeleteTableFrom()
    {
        return $this->delete === true;
    }

    public function getStatement()
    {
        $statement = "";
        if($this->isQuerySql()){
            $statement = $this->sql;
        }elseif ($this->isSelect()) {
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

    private function getSelectStatement()
    {
        $statement = "";
        if (!$this->isSelect()) {
            return $statement;
        }

        $statement .= $this->getSelectString();

        if ($this->from) {
            $statement .= " " . $this->getFromString();
        }

        if ($this->where) {
            $statement .= " " . $this->getWhereString();
        }

        if ($this->groupBy) {
            $statement .= " " . $this->getGroupByString();
        }

        if ($this->having) {
            $statement .= " " . $this->getHavingString();
        }

        if ($this->orderBy) {
            $statement .= " " . $this->getOrderByString();
        }

        if ($this->limit) {
            $statement .= " " . $this->getLimitString();
        }

        return $statement;
    }

    public function getSelectString()
    {
        $statement = "";
        if (empty($this->select)) {
            return $statement;
        }

        foreach ($this->select as $column => $alias) {
            $statement .= $column;
            if ($alias != null) {
                $statement .= " AS " . $alias;
            }
            $statement .= ", ";
        }

        $statement = substr($statement, 0, -2);
        if (!empty($statement)) {
            $statement = "SELECT " . $statement;
        }

        return $statement;
    }

    public function getFromString()
    {

        $statement = "";
        if (empty($this->from)) {
            return $statement;
        }

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

    public function getJoinString()
    {

        $statement = "";
        foreach ($this->join as $i => $join) {
            $type = $join['type'];
            $table = $join['table'];
            $alias = $join['alias'];
            $criteria = $join['criteria'];

            $statement .= " " . $type . " " . $table;

            if ($alias != null) {
                $statement .= " AS " . $alias;
            }

            // join条件
            if ($criteria != null) {
                if($alias != null){
                    $table = $alias;
                }
                $statement = $this->getJoinCriteria($i, $table, $statement, $criteria);
            }
        }

        $statement = trim($statement);
        return $statement;
    }

    public function getJoinCriteria($joinIndex, string $table, string $statement, $criteria)
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

    private function getJoinCriteriaUsingPreviousTable($joinIndex, $table, $column)
    {
        $joinCriteria = "";
        $previousJoinIndex = $joinIndex - 1;

        if (array_key_exists($previousJoinIndex, $this->join)) {
            // 上一个join存在
            $previousTable = $this->join[$previousJoinIndex]['table'];
            if($this->join[$previousJoinIndex]['alias'] != null){
                $previousTable = $this->join[$previousJoinIndex]['alias'];
            }
        } elseif ($this->isSelect()) {
            // 查询
            $previousTable = $this->getFrom();
            $alias = $this->getFromAlias();
            if($alias != null){
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

    public function getFrom()
    {
        return $this->from['table'];
    }

    public function getFromAlias()
    {
        return $this->from['alias'];
    }

    public function getWhereString()
    {
        $statement = $this->getCriteriaString($this->where);

        if (!empty($statement)) {
            $statement = "WHERE " . $statement;
        }

        return $statement;
    }

    private function getCriteriaString(array &$criteria)
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
            $useConnector = true;
            $value = $this->getCriteriaWithoutBracket($criterion['operator'], $criterion['value']);
            $statement .= $criterion['column'] . " " . $criterion['operator'] . " " . $value;
        }
        return $statement;
    }

    private function getQuoteValue($value){
        if(is_string($value)){
            $value = '"'.$value.'"';
        }
        return $value;
    }

    public function getCriteriaWithoutBracket(string $operator, $criterionVaue)
    {

        switch ($operator) {
            case self::BETWEEN:
            case self::NOT_BETWEEN:
                $value = $this->getQuoteValue($criterionVaue[0]) . " " . self::LOGICAL_AND . " " . $this->getQuoteValue($criterionVaue[1]);
                break;

            case self::IN:
            case self::NOT_IN:
                $value = self::BRACKET_OPEN;
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

    public function getGroupByString()
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

    public function getHavingString()
    {
        $statement = $this->getCriteriaString($this->having);
        if (!empty($statement)) {
            $statement = "HAVING " . $statement;
        }
        return $statement;
    }

    public function getOrderByString()
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

    public function getLimitString()
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

    private function getInsertStatement()
    {
        $statement = "";

        if (!$this->isInsert()) {
            return $statement;
        }

        $statement .= $this->getInsertString();

        if ($this->set) {
            $statement .= " " . $this->getSetString();
        }

        return $statement;
    }

    public function getSetString()
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

    public function getInsertString()
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

    public function getUpdateString()
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

    private function getUpdateStatement($usePlaceholders = true)
    {
        $statement = "";

        if (!$this->isUpdate()) {
            return $statement;
        }

        $statement .= $this->getUpdateString();

        if ($this->set) {
            $statement .= " " . $this->getSetString();
        }

        if ($this->where) {
            $statement .= " " . $this->getWhereString();
        }

        // ORDER BY and LIMIT are only applicable when updating a single table.
        if (!$this->join) {
            if ($this->orderBy) {
                $statement .= " " . $this->getOrderByString();
            }

            if ($this->limit) {
                $statement .= " " . $this->getLimitString();
            }
        }

        return $statement;
    }

    private function getDeleteStatement()
    {
        $statement = "";

        if (!$this->isDelete()) {
            return $statement;
        }

        $statement .= $this->getDeleteString();

        if ($this->from) {
            $statement .= " " . $this->getFromString();
        }

        if ($this->where) {
            $statement .= " " . $this->getWhereString();
        }

        // ORDER BY and LIMIT are only applicable when deleting from a single
        // table.
        if ($this->isDeleteTableFrom()) {
            if ($this->orderBy) {
                $statement .= " " . $this->getOrderByString();
            }

            if ($this->limit) {
                $statement .= " " . $this->getLimitString();
            }
        }

        return $statement;
    }

    public function getDeleteString()
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
}

