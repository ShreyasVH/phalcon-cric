<?php

use Phalcon\Db\Column;
use Phalcon\Db\Exception;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class BattingScoresMigration_116
 */
class CricMigration_118 extends Migration
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
        $this->getConnection()->dropColumn(
            'tags_map',
            null,
            'entity_type'
        );

        $this->getConnection()->modifyColumn('tags', null, new Column(
            'type',
            [
                'type' => Column::TYPE_VARCHAR,
                'size' => 100,
                'notNull' => true,
                'after' => 'name'
            ]
        ));
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down(): void
    {
        $this->getConnection()->modifyColumn('tags', null, new Column(
            'type',
            [
                'type' => Column::TYPE_VARCHAR,
                'size' => 100,
                'notNull' => false,
                'after' => 'name'
            ]
        ));

        $this->getConnection()->addColumn(
            'tags_map',
            null,
            new Column(
                'entity_type',
                [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 100,
                    'notNull' => false,
                    'after' => 'id'
                ]
            )
        );

        $rows = $this->getConnection()->fetchAll(
            'SELECT * FROM tags WHERE type = :type',
            \Phalcon\Db\Enum::FETCH_ASSOC,
            [
                'type' => 'MATCH'
            ]
        );

        $match_tag_ids = array_map(function ($row) { return $row['id']; }, $rows);

        $this->getConnection()->update(
            'tags_map',
            ['entity_type'],
            ['MATCH'],
            "tag_id IN (" . implode(',', $match_tag_ids) . ")"
        );

        $rows = $this->getConnection()->fetchAll(
            'SELECT * FROM tags WHERE type = :type',
            \Phalcon\Db\Enum::FETCH_ASSOC,
            [
                'type' => 'SERIES'
            ]
        );

        $series_tag_ids = array_map(function ($row) { return $row['id']; }, $rows);

        $this->getConnection()->update(
            'tags_map',
            ['entity_type'],
            ['SERIES'],
            "tag_id IN (" . implode(',', $series_tag_ids) . ")"
        );

        $this->getConnection()->modifyColumn('tags_map', null, new Column(
            'entity_type',
            [
                'type' => Column::TYPE_VARCHAR,
                'size' => 100,
                'notNull' => true,
                'after' => 'id'
            ]
        ));
    }
}
