<?php

use Phalcon\Db\Column;
use Phalcon\Db\Exception;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class CricMigration_109
 */
class CricMigration_109 extends Migration
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
        $this->morphTable('series', [
            'columns' => [
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'first' => true
                    ]
                ),
                new Column(
                    'name',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 100,
                        'after' => 'id'
                    ]
                ),
                new Column(
                    'home_country_id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'name'
                    ]
                ),
                new Column(
                    'tour_id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'home_country_id'
                    ]
                ),
                new Column(
                    'type_id',
                    [
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'tour_id'
                    ]
                ),
                new Column(
                    'game_type_id',
                    [
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'type_id'
                    ]
                ),
                new Column(
                    'start_time',
                    [
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'after' => 'game_type_id'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_s_name_tour_game_type', ['name', 'tour_id', 'game_type_id'], 'UNIQUE'),
                new Index('home_country', ['home_country_id'], ''),
                new Index('tour', ['tour_id'], ''),
                new Index('type', ['type_id'], ''),
                new Index('game_type', ['game_type_id'], '')
            ],
            'references' => [
                new Reference(
                    'fk_series_home_country',
                    [
                        'referencedTable' => 'countries',
                        'columns' => ['home_country_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_series_tour',
                    [
                        'referencedTable' => 'tours',
                        'columns' => ['tour_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_series_type',
                    [
                        'referencedTable' => 'series_types',
                        'columns' => ['type_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_series_game_type',
                    [
                        'referencedTable' => 'game_types',
                        'columns' => ['game_type_id'],
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
        $this->morphTable('series', [
            'columns' => [
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'first' => true
                    ]
                ),
                new Column(
                    'name',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 50,
                        'after' => 'id'
                    ]
                ),
                new Column(
                    'home_country_id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'name'
                    ]
                ),
                new Column(
                    'tour_id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'home_country_id'
                    ]
                ),
                new Column(
                    'type_id',
                    [
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'tour_id'
                    ]
                ),
                new Column(
                    'game_type_id',
                    [
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'type_id'
                    ]
                ),
                new Column(
                    'start_time',
                    [
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'after' => 'game_type_id'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_s_name_tour_game_type', ['name', 'tour_id', 'game_type_id'], 'UNIQUE'),
                new Index('home_country', ['home_country_id'], ''),
                new Index('tour', ['tour_id'], ''),
                new Index('type', ['type_id'], ''),
                new Index('game_type', ['game_type_id'], '')
            ],
            'references' => [
                new Reference(
                    'fk_series_home_country',
                    [
                        'referencedTable' => 'countries',
                        'columns' => ['home_country_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_series_tour',
                    [
                        'referencedTable' => 'tours',
                        'columns' => ['tour_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_series_type',
                    [
                        'referencedTable' => 'series_types',
                        'columns' => ['type_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_series_game_type',
                    [
                        'referencedTable' => 'game_types',
                        'columns' => ['game_type_id'],
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
}
