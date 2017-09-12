<?php

namespace Swoft\Db;

use Swoft\App;
use Swoft\Db\Validator\IValidator;
use Swoft\Di\Annotation\Bean;
use Swoft\Di\BeanFactory;
use Swoft\Exception\ValidatorException;

/**
 *
 *
 * @uses      Executor
 * @version   2017年09月08日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Executor
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param object $entity
     * @param bool   $defer
     *
     * @return mixed
     */
    public function save($entity, $defer)
    {
        list($table, $idColumn, $idValue, $fields) = $this->getFields($entity, 1);
        $this->queryBuilder->insert($table);

        foreach ($fields as $column => $value) {
            $this->queryBuilder->set($column, $value);
        }
        return $this->getResult($defer);
    }


    public function delete($entity, $defer)
    {
        list($table, $idColumn, $idValue, $fields) = $this->getFields($entity, 3);
        $this->queryBuilder->delete()->from($table);
        foreach ($fields as $column => $value) {
            $this->queryBuilder->where($column, $value);
        }

        return $this->getResult($defer);
    }

    public function deleteById($className, $id, $defer)
    {
        list($table, $id, $idColumn, $fields) = $this->getClassMetaData($className);
        $this->queryBuilder->delete()->from($table)->where($idColumn, $id);
        return $this->getResult($defer);
    }

    public function deleteByIds($className, array $ids, $defer)
    {
        list($table, $id, $idColumn, $fields) = $this->getClassMetaData($className);
        $this->queryBuilder->delete()->from($table)->whereIn($idColumn, $ids);
        return $this->getResult($defer);
    }

    public function update($entity, $defer)
    {
        list($table, $idColumn, $idValue, $fields) = $this->getFields($entity, 2);
        $this->queryBuilder->update($table)->where($idColumn, $idValue);
        foreach ($fields as $column => $value) {
            $this->queryBuilder->set($column, $value);
        }
        return $this->getResult($defer);
    }

    public function find($entity)
    {
        list($tableName, $fields) = $this->getClassMetadata($entity);
        $this->queryBuilder->select('*');
        foreach ($fields as $column => $value) {
            $this->queryBuilder->where($column, $value);
        }
        return $this->queryBuilder;
    }

    /**
     * @param $id
     *
     * @return QueryBuilder
     */
    public function findById($className, $id)
    {

        list($tableName, $columnId) = $this->getTable($className);
        $query = $this->queryBuilder->select("*")->from($tableName)->where($columnId, $id)->limit(1);
        return $query;
    }

    /**
     * @param $ids
     *
     * @return QueryBuilder
     */
    public function findByIds($className, array $ids)
    {
        list($tableName, $columnId) = $this->getTable($className);
        $query = $this->queryBuilder->select("*")->from($tableName)->whereIn($columnId, $ids)->limit(1);
        return $query;
    }

    /**
     * @param bool $defer
     *
     * @return mixed
     */
    private function getResult($defer = false)
    {
        if ($defer) {
            return $this->queryBuilder->getDefer();
        }
        return $this->queryBuilder->getResult();
    }

    private function getFields($entity, $type = 1)
    {
        $changeFields = [];
        list($table, $id, $idColumn, $fields) = $this->getClassMetaData($entity);
        $idValue = null;
        foreach ($fields as $proName => $proAry) {
            $default = $proAry['default'];
            $type = $proAry['type'];
            $column = $proAry['column'];

            $proValue = $this->getEntityProValue($entity, $proName);

            $this->validate($proAry, $proValue);

            if ($type == 1 && $id == $proName && $default == $proValue) {
                continue;
            }

            $isUpate = $default == $proValue || $proName == $id;
            if ($type == 2 && $isUpate) {
                continue;
            }

            if ($type == 3 && $default == $proValue) {
                continue;
            }

            if ($idColumn == $column) {
                $idValue = $proValue;
            }
            $changeFields[$column] = $proValue;
        }

        return [$table, $idColumn, $idValue, $changeFields];
    }

    private function validate(array $columnAry, $propertyValue)
    {
        $type = $columnAry['type'];
        $length = $columnAry['length']?? -1;
        $column = $columnAry['column'];
        $validates = $columnAry['validates'];
        $required = $columnAry['required']?? false;

        if($propertyValue === null && $required){
            throw new ValidatorException("数据字段验证失败，column=".$column."字段必须设置值");
        }

        $validator = [
            'name'  => ucfirst($type),
            'value' => [$length]
        ];
        array_unshift($validates, $validator);

        foreach ($validates as $vald) {
            $name = $vald['name'];
            $params = $vald['value'];
            $beanName = 'Validator' . $name;
            if (!BeanFactory::hasBean($beanName)) {
                continue;
            }

            /* @var IValidator $objValidator*/
            $objValidator = App::getBean($beanName);
            $objValidator->validate($column, $propertyValue, $params);
        }
    }

    private function getEntityProValue($entity, $proName)
    {
        $getterMethod = "get" . ucfirst($proName);
        if (!method_exists($entity, $getterMethod)) {
            throw new \InvalidArgumentException("实体对象属性getter方法不存在，properName=" . $proName);
        }
        $proValue = $entity->$getterMethod();
        return $proValue;
    }

    private function getClassMetaData($entity)
    {
        if (!is_object($entity) && !class_exists($entity)) {
            throw new \InvalidArgumentException("实体不是对象");
        }
        $className = is_string($entity) ? $entity : get_class($entity);
        $entities = App::getEntities();
        if (!isset($entities[$className]['table']['name'])) {
            throw new \InvalidArgumentException("对象不是实体对象，className=" . $className);
        }

        return $this->getTable($className);
    }

    private function getTable($className)
    {
        $entities = App::getEntities();
        $tableName = $entities[$className]['table']['name'];
        $tableId = $entities[$className]['table']['id'];
        $idColumn = $entities[$className]['column'][$tableId];
        $fields = $entities[$className]['field'];
        return [$tableName, $tableId, $idColumn, $fields];
    }
}