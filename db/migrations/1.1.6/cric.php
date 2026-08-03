<?php

use Phalcon\Db\Column;
use Phalcon\Db\Exception;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class BattingScoresMigration_116
 */
class CricMigration_116 extends Migration
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
        $this->getConnection()->addColumn(
            'tags',
            null,
            new Column(
                'type',
                [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 100,
                    'notNull' => false,
                    'after' => 'name',
                ]
            )
        );

        $this->getConnection()->update(
            'tags',
                ['type'],
                ['SERIES'],
            "name IN ('WORLD_CUP', 'IPL', 'CHAMPIONS_TROPHY', 'BBL', 'ILT20', 'CHAMPIONS_LEAGUE', 'ASIA_CUP', 'WTC', 'CPL')"
        );
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down(): void
    {
        $this->getConnection()->dropColumn(
            'tags',
            null,
            'type'
        );
    }
}
