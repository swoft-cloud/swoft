<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Database\Migration;

use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Db\Exception\DbException;
use Swoft\Db\Schema\Blueprint;
use Swoft\Devtool\Annotation\Mapping\Migration;
use Swoft\Devtool\Migration\Migration as BaseMigration;

/**
 * Class Count
 *
 * @since 2.0
 *
 * @Migration(time=20190913232743)
 */
class Count extends BaseMigration
{
    /**
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function up(): void
    {
        $this->schema->createIfNotExists('count', function (Blueprint $blueprint) {
            $blueprint->comment('user count comment ...');

            $blueprint->increments('id')->comment('primary');
            $blueprint->integer('user_id')->default('0')->comment('user table primary');
            $blueprint->integer('create_time')->default('0')->comment('create time');
            $blueprint->timestamp('update_time')->comment('update timestamp');

            $blueprint->index(['user_id', 'create_time']);

            $blueprint->engine  = 'Innodb';
            $blueprint->charset = 'utf8mb4';
        });
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     * @throws DbException
     */
    public function down(): void
    {
        $this->schema->dropIfExists('count');
    }
}
