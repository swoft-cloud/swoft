<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Migration;

use Swoft\Devtool\Annotation\Mapping\Migration;
use Swoft\Devtool\Migration\Migration as BaseMigration;

/**
 * Class AddMsg20190630164222
 *
 * @since 2.0
 *
 * @Migration(20190630164222)
 */
class User extends BaseMigration
{
    /**
     * @return void
     */
    public function up(): void
    {
        $sql = <<<sql
CREATE TABLE IF NOT  EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `age` int(11) NOT NULL DEFAULT '0',
  `password` varchar(100) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `user_desc` varchar(120) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `test_json` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
sql;
        $this->execute($sql);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $dropSql = <<<sql
drop table if exists `user`;
sql;
        $this->execute($dropSql);
    }
}
