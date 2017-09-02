<?php

namespace Swoft\Db;

/**
 *
 *
 * @uses      IEntityManager
 * @version   2017年09月01日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
interface IEntityManager
{
    public function create($isMaster = false);

    public function beginTransaction();

    public function commit();

    public function rollback();

    public function createQuery($sql = '');

    public function createQueryBuilder();

    public function save($entity);

    public function update($entity);

    public function delete($entity);

    public function findByPk($entity, ...$params);

    public function find($entity);
}