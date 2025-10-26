<?php

use Phalcon\Db\Column;
use Phalcon\Db\Exception;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class CricMigration_113
 */
class CricMigration_113 extends Migration
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
        $this->getConnection()->dropForeignKey('series_teams_map', null, 'fk_series_teams_map_series');
        $this->getConnection()->dropForeignKey('man_of_the_series', null, 'fk_mots_series');
        $this->getConnection()->dropForeignKey('matches', null, 'fk_m_series');

        $this->getConnection()->modifyColumn('series', null, new Column(
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
                'id',
                [
                    'type' => Column::TYPE_BIGINTEGER,
                    'unsigned' => true,
                    'notNull' => true,
                    'autoIncrement' => true,
                    'first' => true
                ]
            )
        );
        $this->getConnection()->modifyColumn('series_teams_map', null, new Column(
                'series_id',
                [
                    'type' => Column::TYPE_MEDIUMINTEGER,
                    'unsigned' => true,
                    'notNull' => true,
                    'after' => 'id'
                ]
            ),
            new Column(
                'series_id',
                [
                    'type' => Column::TYPE_BIGINTEGER,
                    'unsigned' => true,
                    'notNull' => true,
                    'after' => 'id'
                ]
            )
        );
        $this->getConnection()->modifyColumn('man_of_the_series', null, new Column(
                'series_id',
                [
                    'type' => Column::TYPE_MEDIUMINTEGER,
                    'unsigned' => true,
                    'notNull' => true,
                    'after' => 'id'
                ]
            ),
            new Column(
                'series_id',
                [
                    'type' => Column::TYPE_BIGINTEGER,
                    'unsigned' => true,
                    'notNull' => true,
                    'after' => 'id'
                ]
            )
        );
        $this->getConnection()->modifyColumn('matches', null, new Column(
            'series_id',
            [
                'type' => Column::TYPE_MEDIUMINTEGER,
                'unsigned' => true,
                'notNull' => true,
                'after' => 'id'
            ]
        ),
            new Column(
                'series_id',
                [
                    'type' => Column::TYPE_BIGINTEGER,
                    'unsigned' => true,
                    'notNull' => true,
                    'after' => 'id'
                ]
            )
        );

        $this->getConnection()->addForeignKey('series_teams_map', null, new Reference(
            'fk_series_teams_map_series',
            [
                'referencedTable' => 'series',
                'columns' => ['series_id'],
                'referencedColumns' => ['id'],
                'onUpdate' => 'NO ACTION',
                'onDelete' => 'NO ACTION'
            ]
        ));
        $this->getConnection()->addForeignKey('man_of_the_series', null, new Reference(
            'fk_mots_series',
            [
                'referencedTable' => 'series',
                'columns' => ['series_id'],
                'referencedColumns' => ['id'],
                'onUpdate' => 'NO ACTION',
                'onDelete' => 'NO ACTION'
            ]
        ));
        $this->getConnection()->addForeignKey('matches', null, new Reference(
            'fk_m_series',
            [
                'referencedTable' => 'series',
                'columns' => ['series_id'],
                'referencedColumns' => ['id'],
                'onUpdate' => 'NO ACTION',
                'onDelete' => 'NO ACTION'
            ]
        ));

        $this->morphTable('tags', [
            'columns' => [
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
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
                )
            ],
            'indexes' => [
                new Index('pk_tags', ['id'], 'PRIMARY'),
                new Index('uk_ta_name', ['name'], 'UNIQUE')
            ],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8mb4_0900_ai_ci',
            ],
        ]);

        $this->batchInsert('tags', ['name']);

        $this->morphTable('tags_map', [
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
                    'entity_type',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 100,
                        'after' => 'id'
                    ]
                ),
                new Column(
                    'entity_id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'entity_type'
                    ]
                ),
                new Column(
                    'tag_id',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'entity_id'
                    ]
                )
            ],
            'indexes' => [
                new Index('pk_tags_map', ['id'], 'PRIMARY'),
                new Index('uk_tm_type_id_tag', ['entity_type', 'entity_id', 'tag_id'], 'UNIQUE'),
                new Index('tm_tag', ['tag_id'], '')
            ],
            'references' => [
                new Reference(
                    'fk_tm_tag',
                    [
                        'referencedTable' => 'tags',
                        'columns' => ['tag_id'],
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
        $this->getConnection()->dropTable('tags_map');
        $this->getConnection()->dropTable('tags');

        $this->getConnection()->dropForeignKey('series_teams_map', null, 'fk_series_teams_map_series');
        $this->getConnection()->dropForeignKey('man_of_the_series', null, 'fk_mots_series');
        $this->getConnection()->dropForeignKey('matches', null, 'fk_m_series');

        $this->getConnection()->modifyColumn('series', null, new Column(
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
                'id',
                [
                    'type' => Column::TYPE_MEDIUMINTEGER,
                    'unsigned' => true,
                    'notNull' => true,
                    'autoIncrement' => true,
                    'first' => true
                ]
            )
        );
        $this->getConnection()->modifyColumn('series_teams_map', null, new Column(
            'series_id',
            [
                'type' => Column::TYPE_BIGINTEGER,
                'unsigned' => true,
                'notNull' => true,
                'after' => 'id'
            ]
        ),
            new Column(
                'series_id',
                [
                    'type' => Column::TYPE_MEDIUMINTEGER,
                    'unsigned' => true,
                    'notNull' => true,
                    'after' => 'id'
                ]
            )
        );
        $this->getConnection()->modifyColumn('man_of_the_series', null, new Column(
            'series_id',
            [
                'type' => Column::TYPE_BIGINTEGER,
                'unsigned' => true,
                'notNull' => true,
                'after' => 'id'
            ]
        ),
            new Column(
                'series_id',
                [
                    'type' => Column::TYPE_MEDIUMINTEGER,
                    'unsigned' => true,
                    'notNull' => true,
                    'after' => 'id'
                ]
            )
        );
        $this->getConnection()->modifyColumn('matches', null, new Column(
            'series_id',
            [
                'type' => Column::TYPE_BIGINTEGER,
                'unsigned' => true,
                'notNull' => true,
                'after' => 'id'
            ]
        ),
            new Column(
                'series_id',
                [
                    'type' => Column::TYPE_MEDIUMINTEGER,
                    'unsigned' => true,
                    'notNull' => true,
                    'after' => 'id'
                ]
            )
        );

        $this->getConnection()->addForeignKey('series_teams_map', null, new Reference(
            'fk_series_teams_map_series',
            [
                'referencedTable' => 'series',
                'columns' => ['series_id'],
                'referencedColumns' => ['id'],
                'onUpdate' => 'NO ACTION',
                'onDelete' => 'NO ACTION'
            ]
        ));
        $this->getConnection()->addForeignKey('man_of_the_series', null, new Reference(
            'fk_mots_series',
            [
                'referencedTable' => 'series',
                'columns' => ['series_id'],
                'referencedColumns' => ['id'],
                'onUpdate' => 'NO ACTION',
                'onDelete' => 'NO ACTION'
            ]
        ));
        $this->getConnection()->addForeignKey('matches', null, new Reference(
            'fk_m_series',
            [
                'referencedTable' => 'series',
                'columns' => ['series_id'],
                'referencedColumns' => ['id'],
                'onUpdate' => 'NO ACTION',
                'onDelete' => 'NO ACTION'
            ]
        ));
    }
}
