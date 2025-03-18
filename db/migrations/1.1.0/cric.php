<?php

use Phalcon\Db\Column;
use Phalcon\Db\Exception;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class CricMigration_110
 */
class CricMigration_110 extends Migration
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
        $this->batchInsert('result_types', ['name']);
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down(): void
    {
        $result_types = explode(PHP_EOL, file_get_contents(dirname(__FILE__) . '/result_types.dat'));

        $this->getConnection()->delete(
            'result_types',
            "name IN (" . implode(', ', array_map(function ($result_type) {
                return json_encode($result_type);
            }, $result_types)) . ")"
        );
    }
}
