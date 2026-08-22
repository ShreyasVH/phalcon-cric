<?php

use Phalcon\Db\Column;
use Phalcon\Db\Exception;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class CricMigration_119
 */
class CricMigration_119 extends Migration
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
        $this->morphTable('partnerships', [
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
                    'innings',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'id'
                    ]
                ),
                new Column(
                    'wicket',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'innings'
                    ]
                ),
                new Column(
                    'runs',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'wicket'
                    ]
                ),
                new Column(
                    'balls',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'runs'
                    ]
                ),
                new Column(
                    'ended',
                    [
                        'type' => Column::TYPE_BOOLEAN,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'balls'
                    ]
                ),
                new Column(
                    'match_player_id_1',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'name'
                    ]
                ),
                new Column(
                    'runs_1',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'match_player_id_1'
                    ]
                ),
                new Column(
                    'balls_1',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'runs_1'
                    ]
                ),
                new Column(
                    'match_player_id_2',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'balls_2'
                    ]
                ),
                new Column(
                    'runs_2',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'match_player_id_2'
                    ]
                ),
                new Column(
                    'balls_2',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'runs_2'
                    ]
                ),
                new Column(
                    'primary_entry',
                    [
                        'type' => Column::TYPE_BOOLEAN,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'balls_2'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_p_players_innings_wicket', ['match_player_id_1', 'match_player_id_2', 'innings', 'wicket'], 'UNIQUE'),
            ],
            'references' => [
                new Reference(
                    'fk_p_match_player_1',
                    [
                        'referencedTable' => 'match_player_map',
                        'columns' => ['match_player_id_1'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_p_match_player_2',
                    [
                        'referencedTable' => 'match_player_map',
                        'columns' => ['match_player_id_2'],
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
        $this->getConnection()->dropTable('partnerships');
    }
}
