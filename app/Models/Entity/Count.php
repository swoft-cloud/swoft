<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Models\Entity;

use Swoft\Db\Bean\Annotation\Column;
use Swoft\Db\Bean\Annotation\Entity;
use Swoft\Db\Bean\Annotation\Id;
use Swoft\Db\Bean\Annotation\Table;
use Swoft\Db\Model;
use Swoft\Db\Types;

/**
 * 计数表实体
 *
 * @Entity()
 * @Table("count")
 * @uses      Count
 * @version   2017年09月15日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Count extends Model
{
    /**
     * 用户ID
     *
     * @Column(name="uid", type=Types::INT)
     * @Id()
     * @var null|int
     */
    private $uid;

    /**
     * 粉丝数
     *
     * @Column(name="fans", type=Types::NUMBER)
     * @var int
     */
    private $fans = 0;

    /**
     * 关注数
     *
     * @Column("follows", type=Types::NUMBER)
     * @var int
     */
    private $follows = 0;

    /**
     * @return int|null
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param int|null $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return int
     */
    public function getFans(): int
    {
        return $this->fans;
    }

    /**
     * @param int $fans
     */
    public function setFans(int $fans)
    {
        $this->fans = $fans;
    }

    /**
     * @return mixed
     */
    public function getFollows()
    {
        return $this->follows;
    }

    /**
     * @param mixed $follows
     */
    public function setFollows($follows)
    {
        $this->follows = $follows;
    }
}