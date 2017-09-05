<?php

namespace App\Models\Entity;

use Doctrine\Common\Annotations\Annotation\Enum;
use Swoft\Di\Annotation\Column;
use Swoft\Di\Annotation\Entity;
use Swoft\Di\Annotation\Id;
use Swoft\Di\Annotation\Required;
use Swoft\Di\Annotation\Table;

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
class User
{
    /**
     * 主键ID
     *
     * @Id()
     * @Column(name="id", type="int")
     * @var int
     */
    private $id;

    /**
     * 名称
     *
     * @Column(name="name", type="string", length=20)
     * @Required()
     * @var string
     */
    private $name;


    /**
     * 年龄
     *
     * @Column(name="age", type="int")
     * @var int
     */
    private $age = 0;

    /**
     * 性别
     *
     * @Column(name="sex", type="int")
     * @Required()
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

}