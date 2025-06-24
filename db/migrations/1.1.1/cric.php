<?php

use Phalcon\Db\Column;
use Phalcon\Db\Exception;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class CricMigration_111
 */
class CricMigration_111 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     * @throws Exception
     */
    public function morph(): void
    {

    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up(): void
    {
        $connection = $this->getConnection();

        $connection->dropIndex('stadiums', null, 'uk_s_name_country');

        $connection->addIndex(
            'stadiums',
            null,
            new Index(
                'uk_s_name_country_city',
                ['name', 'country_id', 'city'],
                'UNIQUE'
            )
        );
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down(): void
    {
        $connection = $this->getConnection();

        $connection->dropIndex('stadiums', null, 'uk_s_name_country_city');

        $connection->addIndex(
            'stadiums',
            null,
            new Index(
                'uk_s_name_country',
                ['name', 'country_id'],
                'UNIQUE'
            )
        );
    }
}
