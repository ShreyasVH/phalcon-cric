<?php

use Phalcon\Db\Column;
use Phalcon\Db\Exception;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class CricMigration_120
 */
class CricMigration_120 extends Migration
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
        $this->morphTable('ballwise_details', [
            'columns' => [
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'first' => true
                    ]
                ),
                new Column(
                    'batsman_match_player_id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'id'
                    ]
                ),
                new Column(
                    'bowler_match_player_id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'batsman_match_player_id'
                    ]
                ),
                new Column(
                    'innings',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'bowler_match_player_id'
                    ]
                ),
                new Column(
                    'ball',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'innings'
                    ]
                ),
                new Column(
                    'dismissal',
                    [
                        'type' => Column::TYPE_BOOLEAN,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'ball'
                    ]
                ),
                new Column(
                    'total_runs',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'dismissal'
                    ]
                ),
                new Column(
                    'batsman_runs',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'total_runs'
                    ]
                ),
                new Column(
                    'bowler_runs',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'batsman_runs'
                    ]
                ),
                new Column(
                    'extras_runs',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'bowler_runs'
                    ]
                ),
                new Column(
                    'extras_type',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'default' => '',
                        'size' => 100,
                        'after' => 'extras_runs'
                    ]
                ),
                new Column(
                    'timestamp',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'extras_type'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_bd_timestamp', ['bowler_match_player_id', 'timestamp'], 'UNIQUE'),
            ],
            'references' => [
                new Reference(
                    'fk_bd_batsman',
                    [
                        'referencedTable' => 'match_player_map',
                        'columns' => ['batsman_match_player_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_bd_bowler',
                    [
                        'referencedTable' => 'match_player_map',
                        'columns' => ['bowler_match_player_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                )
            ],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8mb4_0900_ai_ci',
            ],
        ]);
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down(): void
    {
        $this->getConnection()->dropTable('ballwise_details');
    }
}
