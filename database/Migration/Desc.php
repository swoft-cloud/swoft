<?php declare(strict_types=1);


namespace Database\Migration;


use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Db\Exception\DbException;
use Swoft\Db\Schema\Blueprint;
use Swoft\Devtool\Annotation\Mapping\Migration;
use Swoft\Devtool\Migration\Migration as BaseMigration;

/**
 * Class Desc
 *
 * @since 2.0
 *
 * @Migration(time=20190913234407)
 */
class Desc extends BaseMigration
{
    /**
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function up(): void
    {
        $this->schema->createIfNotExists('desc', function (Blueprint $blueprint) {
            $blueprint->comment = 'user desc';

            $blueprint->increments('id');
            $blueprint->string('desc', 30);
        });
    }

    /**
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function down(): void
    {

        $this->schema->dropIfExists('desc');
    }
}
