<?php

use Phalcon\Db\Column;
use Phalcon\Db\Exception;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class CricMigration_102
 */
class CricMigration_102 extends Migration
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
        $this->getConnection()->modifyColumn('countries', null, new Column(
            'id',
            [
                'type' => Column::TYPE_BIGINTEGER,
                'unsigned' => true,
                'notNull' => true,
                'autoIncrement' => true,
                'size' => 1,
                'first' => true
            ]
        ),
            new Column(
                'id',
                [
                    'type' => Column::TYPE_BIGINTEGER,
                    'notNull' => true,
                    'autoIncrement' => true,
                    'size' => 1,
                    'first' => true
                ]
            )
        );


        $this->morphTable('stadiums', [
            'columns' => [
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'size' => 1,
                        'first' => true
                    ]
                ),
                new Column(
                    'name',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 200,
                        'after' => 'id'
                    ]
                ),
                new Column(
                    'city',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 100,
                        'after' => 'name'
                    ]
                ),
                new Column(
                    'state',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => false,
                        'size' => 100,
                        'after' => 'city'
                    ]
                ),
                new Column(
                    'country_id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'state'
                    ]
                ),
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_s_name_country', ['name', 'country_id'], 'UNIQUE'),
                new Index('country', ['country_id'], ''),
            ],
            'references' => [
                new Reference(
                    'fk_stadiums_country_id',
                    [
                        'referencedTable' => 'countries',
                        'columns' => ['country_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
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
        $this->getConnection()->dropTable('stadiums');

        $this->getConnection()->modifyColumn('countries', null, new Column(
            'id',
            [
                'type' => Column::TYPE_BIGINTEGER,
                'notNull' => true,
                'autoIncrement' => true,
                'size' => 1,
                'first' => true
            ]
        ),
            new Column(
                'id',
                [
                    'type' => Column::TYPE_BIGINTEGER,
                    'unsigned' => true,
                    'notNull' => true,
                    'autoIncrement' => true,
                    'size' => 1,
                    'first' => true
                ]
            )
        );
    }
}
