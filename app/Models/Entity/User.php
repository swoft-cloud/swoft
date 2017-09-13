<?php

namespace App\Models\Entity;

use Swoft\Db\Model;
use Swoft\Di\Annotation\Column;
use Swoft\Di\Annotation\Entity;
use Swoft\Di\Annotation\Enum;
use Swoft\Di\Annotation\Id;
use Swoft\Di\Annotation\Required;
use Swoft\Di\Annotation\Table;
use Swoft\Db\Types;

/**
 *
 * @Entity()
 * @Table(name="user")
 * @uses      User
 * @version   2017年08月23日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class User extends Model
{
    /**
     * 主键ID
     *
     * @Id()
     * @Column(name="id", type=Types::INT)
     * @var int
     */
    private $id;

    /**
     * 名称
     *
     * @Column(name="name", type=Types::STRING, length=20)
     * @Required()
     * @var string
     */
    private $name;


    /**
     * 年龄
     *
     * @Column(name="age", type=Types::INT)
     * @var int
     */
    private $age = 0;

    /**
     * 性别
     *
     * @Column(name="sex", type="int")
     * @Enum(value={1,0})
     * @var int
     */
    private $sex = 0;


    /**
     * 描述
     *
     * @Column(name="description", type="string")
     * @var string
     */
    private $desc = "";


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age)
    {
        $this->age = $age;
    }

    /**
     * @return int
     */
    public function getSex(): int
    {
        return $this->sex;
    }

    /**
     * @param int $sex
     */
    public function setSex(int $sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }

    /**
     * @param string $desc
     */
    public function setDesc(string $desc)
    {
        $this->desc = $desc;
    }
}
