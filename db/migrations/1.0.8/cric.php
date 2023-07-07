<?php

use Phalcon\Db\Column;
use Phalcon\Db\Exception;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Migrations\Mvc\Model\Migration;

/**
 * Class CricMigration_108
 */
class CricMigration_108 extends Migration
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
        $this->morphTable('result_types', [
            'columns' => [
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
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
                        'size' => 100,
                        'after' => 'id'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_rt_name', ['name'], 'UNIQUE')
            ],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8mb4_0900_ai_ci',
            ],
        ]);

        $this->batchInsert('result_types', ['name']);

        $this->morphTable('win_margin_types', [
            'columns' => [
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
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
                        'size' => 100,
                        'after' => 'id'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_wmt_name', ['name'], 'UNIQUE')
            ],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8mb4_0900_ai_ci',
            ],
        ]);

        $this->batchInsert('win_margin_types', ['name']);

        $this->morphTable('matches', [
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
                    'series_id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'id'
                    ]
                ),
                new Column(
                    'team1_id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'series_id'
                    ]
                ),
                new Column(
                    'team2_id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'team1_id'
                    ]
                ),
                new Column(
                    'toss_winner_id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => false,
                        'default' => null,
                        'after' => 'team2_id'
                    ]
                ),
                new Column(
                    'bat_first_id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => false,
                        'default' => null,
                        'after' => 'toss_winner_id'
                    ]
                ),
                new Column(
                    'result_type_id',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'bat_first_id'
                    ]
                ),
                new Column(
                    'winner_id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => false,
                        'default' => null,
                        'after' => 'result_type_id'
                    ]
                ),
                new Column(
                    'win_margin',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => false,
                        'default' => null,
                        'after' => 'winner_id'
                    ]
                ),
                new Column(
                    'win_margin_type_id',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'unsigned' => true,
                        'notNull' => false,
                        'default' => null,
                        'after' => 'win_margin'
                    ]
                ),
                new Column(
                    'stadium_id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'winner_id'
                    ]
                ),
                new Column(
                    'start_time',
                    [
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'after' => 'stadium_id'
                    ]
                ),
                new Column(
                    'is_official',
                    [
                        'type' => Column::TYPE_BOOLEAN,
                        'notNull' => true,
                        'default' => true,
                        'after' => 'start_time'
                    ]
                ),
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_m_stadium_start', ['stadium_id', 'start_time'], 'UNIQUE'),
                new Index('series', ['series_id'], ''),
                new Index('team_1', ['team1_id'], ''),
                new Index('team_2', ['team2_id'], ''),
                new Index('toss_winner', ['toss_winner_id'], ''),
                new Index('bat_first', ['bat_first_id'], ''),
                new Index('result_type', ['result_type_id'], ''),
                new Index('winner', ['winner_id'], ''),
                new Index('win_margin_type', ['win_margin_type_id'], ''),
                new Index('stadium', ['stadium_id'], '')
            ],
            'references' => [
                new Reference(
                    'fk_m_series',
                    [
                        'referencedTable' => 'series',
                        'columns' => ['series_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_m_team_1',
                    [
                        'referencedTable' => 'teams',
                        'columns' => ['team1_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_m_team_2',
                    [
                        'referencedTable' => 'teams',
                        'columns' => ['team2_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_m_toss_winner',
                    [
                        'referencedTable' => 'teams',
                        'columns' => ['toss_winner_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_m_bat_first',
                    [
                        'referencedTable' => 'teams',
                        'columns' => ['bat_first_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_m_result_type',
                    [
                        'referencedTable' => 'result_types',
                        'columns' => ['result_type_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_m_winner',
                    [
                        'referencedTable' => 'teams',
                        'columns' => ['winner_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_m_win_margin_type',
                    [
                        'referencedTable' => 'win_margin_types',
                        'columns' => ['win_margin_type_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_m_stadium',
                    [
                        'referencedTable' => 'stadiums',
                        'columns' => ['stadium_id'],
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

        $this->morphTable('match_player_map', [
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
                    'match_id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'id'
                    ]
                ),
                new Column(
                    'player_id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'match_id'
                    ]
                ),
                new Column(
                    'team_id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'player_id'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_mpm_match_player_team', ['match_id', 'player_Id', 'team_id'], 'UNIQUE'),
                new Index('match', ['match_id'], ''),
                new Index('player', ['player_id'], ''),
                new Index('team', ['team_id'], '')
            ],
            'references' => [
                new Reference(
                    'fk_mpm_match',
                    [
                        'referencedTable' => 'matches',
                        'columns' => ['match_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_mpm_player',
                    [
                        'referencedTable' => 'players',
                        'columns' => ['player_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_mpm_team',
                    [
                        'referencedTable' => 'teams',
                        'columns' => ['team_id'],
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

        $this->morphTable('dismissal_modes', [
            'columns' => [
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
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
                        'size' => 100,
                        'after' => 'id'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_dm_name', ['name'], 'UNIQUE')
            ],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8mb4_0900_ai_ci',
            ],
        ]);

        $this->batchInsert('dismissal_modes', ['name']);

        $this->morphTable('batting_scores', [
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
                    'match_player_id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'name'
                    ]
                ),
                new Column(
                    'runs',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'default' => '0',
                        'after' => 'name'
                    ]
                ),
                new Column(
                    'balls',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'default' => '0',
                        'after' => 'runs'
                    ]
                ),
                new Column(
                    'fours',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'default' => '0',
                        'after' => 'balls'
                    ]
                ),
                new Column(
                    'sixes',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'default' => '0',
                        'after' => 'fours'
                    ]
                ),
                new Column(
                    'dismissal_mode_id',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'unsigned' => true,
                        'notNull' => false,
                        'after' => 'name'
                    ]
                ),
                new Column(
                    'bowler_id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'notNull' => false,
                        'after' => 'dismissal_mode_id'
                    ]
                ),
                new Column(
                    'innings',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'bowler_id'
                    ]
                ),
                new Column(
                    'number',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'unsigned' => true,
                        'notNull' => false,
                        'default' => null,
                        'after' => 'innings'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_bs_match_player_innings', ['match_player_id', 'innings'], 'UNIQUE'),
                new Index('match_player', ['match_player_id'], ''),
                new Index('dismissal_mode', ['dismissal_mode_id'], ''),
                new Index('bowler', ['bowler_id'], '')
            ],
            'references' => [
                new Reference(
                    'fk_bs_match_player',
                    [
                        'referencedTable' => 'match_player_map',
                        'columns' => ['match_player_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_bs_dismissal_mode',
                    [
                        'referencedTable' => 'dismissal_modes',
                        'columns' => ['dismissal_mode_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_bs_bowler',
                    [
                        'referencedTable' => 'match_player_map',
                        'columns' => ['bowler_id'],
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

        $this->morphTable('bowling_figures', [
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
                    'match_player_id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'name'
                    ]
                ),
                new Column(
                    'balls',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => false,
                        'default' => '0',
                        'after' => 'name'
                    ]
                ),
                new Column(
                    'maidens',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'unsigned' => true,
                        'notNull' => false,
                        'default' => '0',
                        'after' => 'runs'
                    ]
                ),
                new Column(
                    'runs',
                    [
                        'type' => Column::TYPE_SMALLINTEGER,
                        'unsigned' => true,
                        'notNull' => false,
                        'default' => '0',
                        'after' => 'balls'
                    ]
                ),
                new Column(
                    'wickets',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'unsigned' => true,
                        'notNull' => false,
                        'default' => '0',
                        'after' => 'fours'
                    ]
                ),
                new Column(
                    'innings',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'bowler_id'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_bf_match_player_innings', ['match_player_id', 'innings'], 'UNIQUE'),
                new Index('match_player', ['match_player_id'], '')
            ],
            'references' => [
                new Reference(
                    'fk_bf_match_player',
                    [
                        'referencedTable' => 'match_player_map',
                        'columns' => ['match_player_id'],
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

        $this->morphTable('fielder_dismissals', [
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
                    'score_id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'id'
                    ]
                ),
                new Column(
                    'match_player_id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'score_id'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_fd_score_player_team', ['score_id', 'match_player_id'], 'UNIQUE'),
                new Index('score', ['score_id'], ''),
                new Index('match_player', ['match_player_id'], '')
            ],
            'references' => [
                new Reference(
                    'fk_fd_score',
                    [
                        'referencedTable' => 'batting_scores',
                        'columns' => ['score_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_fd_match_player',
                    [
                        'referencedTable' => 'match_player_map',
                        'columns' => ['match_player_id'],
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

        $this->morphTable('extras_types', [
            'columns' => [
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
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
                        'size' => 100,
                        'after' => 'id'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_rt_name', ['name'], 'UNIQUE')
            ],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8mb4_0900_ai_ci',
            ],
        ]);

        $this->batchInsert('extras_types', ['name']);

        $this->morphTable('extras', [
            'columns' => [
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'autoIncrement' => true,
                        'notNull' => true,
                        'first' => true
                    ]
                ),
                new Column(
                    'match_id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'id'
                    ]
                ),
                new Column(
                    'type_id',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'match_id'
                    ]
                ),
                new Column(
                    'runs',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'type_id'
                    ]
                ),
                new Column(
                    'batting_team_id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'runs'
                    ]
                ),
                new Column(
                    'bowling_team_id',
                    [
                        'type' => Column::TYPE_BIGINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'batting_team_id'
                    ]
                ),
                new Column(
                    'innings',
                    [
                        'type' => Column::TYPE_TINYINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'bowling_team_id'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_e_match_type_batting_innings', ['match_id', 'type_id', 'batting_team_id', 'innings'], 'UNIQUE'),
                new Index('match', ['match_id'], ''),
                new Index('type', ['type_id'], ''),
                new Index('batting_team', ['batting_team_id'], ''),
                new Index('bowling_team', ['bowling_team_id'], '')
            ],
            'references' => [
                new Reference(
                    'fk_e_match',
                    [
                        'referencedTable' => 'matches',
                        'columns' => ['match_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_e_type',
                    [
                        'referencedTable' => 'extras_types',
                        'columns' => ['type_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_e_batting_team',
                    [
                        'referencedTable' => 'teams',
                        'columns' => ['batting_team_id'],
                        'referencedColumns' => ['id'],
                        'onUpdate' => 'NO ACTION',
                        'onDelete' => 'NO ACTION'
                    ]
                ),
                new Reference(
                    'fk_e_bowling_team',
                    [
                        'referencedTable' => 'teams',
                        'columns' => ['bowling_team_id'],
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
            ]
        ]);

        $this->morphTable('captains', [
            'columns' => [
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'autoIncrement' => true,
                        'notNull' => true,
                        'first' => true
                    ]
                ),
                new Column(
                    'match_player_id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'id'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_c_match_player', ['match_player_id'], 'UNIQUE'),
                new Index('match_player', ['match_player_id'], '')
            ],
            'references' => [
                new Reference(
                    'fk_c_match_player',
                    [
                        'referencedTable' => 'match_player_map',
                        'columns' => ['match_player_id'],
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
                'TABLE_COLLATION' => 'utf8mb4_0900_ai_ci'
            ]
        ]);

        $this->morphTable('wicket_keepers', [
            'columns' => [
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'autoIncrement' => true,
                        'notNull' => true,
                        'first' => true
                    ]
                ),
                new Column(
                    'match_player_id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'id'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_wk_match_player', ['match_player_id'], 'UNIQUE'),
                new Index('match_player', ['match_player_id'], '')
            ],
            'references' => [
                new Reference(
                    'fk_wk_match_player',
                    [
                        'referencedTable' => 'match_player_map',
                        'columns' => ['match_player_id'],
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
                'TABLE_COLLATION' => 'utf8mb4_0900_ai_ci'
            ]
        ]);

        $this->morphTable('man_of_the_match', [
            'columns' => [
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'autoIncrement' => true,
                        'notNull' => true,
                        'first' => true
                    ]
                ),
                new Column(
                    'match_player_id',
                    [
                        'type' => Column::TYPE_MEDIUMINTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'id'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('uk_motm_match_player', ['match_player_id'], 'UNIQUE'),
                new Index('match_player', ['match_player_id'], '')
            ],
            'references' => [
                new Reference(
                    'fk_motm_match_player',
                    [
                        'referencedTable' => 'match_player_map',
                        'columns' => ['match_player_id'],
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
                'TABLE_COLLATION' => 'utf8mb4_0900_ai_ci'
            ]
        ]);
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down(): void
    {
        $this->getConnection()->dropTable('man_of_the_match');
        $this->getConnection()->dropTable('wicket_keepers');
        $this->getConnection()->dropTable('captains');
        $this->getConnection()->dropTable('extras');
        $this->getConnection()->dropTable('extras_types');
        $this->getConnection()->dropTable('fielder_dismissals');
        $this->getConnection()->dropTable('bowling_figures');
        $this->getConnection()->dropTable('batting_scores');
        $this->getConnection()->dropTable('dismissal_modes');
        $this->getConnection()->dropTable('match_player_map');
        $this->getConnection()->dropTable('matches');
        $this->getConnection()->dropTable('win_margin_types');
        $this->getConnection()->dropTable('result_types');
    }
}
