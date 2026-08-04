<?php

use Phalcon\Db\Column;
use Phalcon\Db\Exception;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class BattingScoresMigration_116
 */
class CricMigration_117 extends Migration
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
        $this->getConnection()->update(
            'tags',
            ['type'],
            ['MATCH'],
            "name IN ('FINAL', 'SEMI_FINAL', 'QUARTER_FINAL', 'KNOCKOUT', 'ELIMINATOR', 'THIRD_PLACE', 'QUALIFIER', 'QUALIFIER_1', 'QUALIFIER_2', 'CHALLENGER')"
        );
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down(): void
    {
        $this->getConnection()->update(
            'tags',
            ['type'],
            [null],
            "name IN ('FINAL', 'SEMI_FINAL', 'QUARTER_FINAL', 'KNOCKOUT', 'ELIMINATOR', 'THIRD_PLACE', 'QUALIFIER', 'QUALIFIER_1', 'QUALIFIER_2', 'CHALLENGER')"
        );
    }
}
