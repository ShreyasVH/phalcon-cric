<?php

use Phalcon\Db\Column;
use Phalcon\Db\Exception;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class CricMigration_114
 */
class CricMigration_114 extends Migration
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
        $this->batchInsert('tags', ['name']);
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down(): void
    {
        $tags = explode(PHP_EOL, file_get_contents(dirname(__FILE__) . '/tags.dat'));

        $this->getConnection()->delete(
            'tags',
            "name IN (" . implode(', ', array_map(function ($tag) {
                return json_encode($tag);
            }, $tags)) . ")"
        );
    }
}
