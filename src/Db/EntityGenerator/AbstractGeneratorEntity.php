<?php

namespace Swoft\Db\EntityGenerator;

/**
 * 抽象生成实体操作类
 *
 * @uses      AbstractGeneratorEntity
 * @version   2017年11月06日
 * @author    caiwh <471113744@qq.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractGeneratorEntity
{
    /**
     * @var array $uses 模板use
     */
    protected $uses = [
        'Swoft\Db\Model',
        'Swoft\Bean\Annotation\Column',
        'Swoft\Bean\Annotation\Entity',
        'Swoft\Bean\Annotation\Enum',
        'Swoft\Bean\Annotation\Id',
        'Swoft\Bean\Annotation\Required',
        'Swoft\Bean\Annotation\Table',
        'Swoft\Db\Types'
    ];

    /**
     * 实体基类
     */
    protected $extends = 'Model';

    /**
     * @var string $entity 实体命名
     */
    protected $entity = null;

    /**
     * @var string $entityName 实体中文名
     */
    protected $entityName = null;

    /**
     * @var string $entityClass 实体类名
     */
    protected $entityClass = null;

    /**
     * @var string $entityDate 实体类创建时间
     */
    protected $entityDate = null;

    /**
     * @var string $fields 字段
     */
    protected $fields = null;

    /**
     * @var string 字段setter
     */
    protected $setter = null;

    /**
     * @var string 字段getter
     */
    protected $getter = null;

    /**
     * @var $dbHandler 数据库连接句柄
     */
    protected $dbHandler = null;

    public function __construct($dbConnect)
    {
        $this->dbHandler = $dbConnect;
    }

    /**
     * 解析属性
     * @param string $entity     实体
     * @param        $entityName 实体注释名称
     * @param array  $fields     字段
     */
    protected function parseProperty(string $entity, $entityName , array $fields)
    {
        $this->entity = $entity;
        $this->entityName = $entityName;
        $this->entityClass = ucwords($this->entity);
        $this->entityDate = date('Y年m月d日');
        $this->fields = $fields;

        $param = [
            $this->uses,
            $this->extends,
            $this->entity,
            $this->entityName,
            $this->entityClass,
            $this->entityDate,
            $this->fields
        ];

        $sgGenerator = new SetGetGenerator();
        $sgGenerator(...$param);
    }
}
