<?php


namespace app\repositories;


use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Di\Injectable;

class BowlingFigureRepository extends Injectable
{
    private Mysql $_db;

    public function __construct()
    {
        $this->_db = $this->getDI()->get('db');
    }

    public function get_bowling_stats(int $player_id)
    {
        $stats_final = [];
        $query = 'SELECT COUNT(*) AS innings, SUM(balls) AS balls, SUM(maidens) AS maidens, SUM(runs) AS runs, SUM(wickets) AS wickets, gt.name AS gameType, COUNT(CASE WHEN (bf.wickets >= 5 and bf.wickets < 10) then 1 end) as fifers,  COUNT(CASE WHEN (bf.wickets = 10) then 1 end) as tenWickets FROM bowling_figures bf inner join match_player_map mpm on mpm.id = bf.match_player_id and mpm.player_id = ' . $player_id . ' INNER JOIN matches m ON m.id = mpm.match_id INNER JOIN series s ON s.id = m.series_id and m.is_official = 1 inner join teams t on t.id = mpm.team_id inner join team_types tt on tt.id = t.type_id and tt.name = \'INTERNATIONAL\' inner join game_types gt on gt.id = s.game_type_id GROUP BY gt.name';
        $sql_query = $this->_db->query($query);
        $result = $sql_query->fetchAll();

        foreach($result as $row)
        {
            $innings = $row['innings'];
            if($innings > 0)
            {
                $stats = [
                    'innings' => $innings,
                    'runs' => (int) $row['runs'],
                    'balls' => (int) $row['balls'],
                    'maidens' => $row['maidens'],
                    'wickets' => $row['wickets'],
                    'fifers' => $row['fifers'],
                    'tenWickets' => $row['tenWickets']
                ];

                $game_type = $row['gameType'];
                $stats_final[$game_type] = $stats;
            }
        }

        return $stats_final;
    }
}