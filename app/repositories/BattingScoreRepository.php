<?php


namespace app\repositories;


use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Di\Injectable;

class BattingScoreRepository extends Injectable
{
    private Mysql $_db;

    public function __construct()
    {
        $this->_db = $this->getDI()->get('db');
    }

    public function get_dismissal_stats(int $player_id)
    {
        $stats = [];
        $query = 'SELECT dm.name AS dismissalMode, COUNT(*) AS count, gt.name as gameType FROM `batting_scores` bs INNER JOIN match_player_map mpm on mpm.id = bs.match_player_id inner join dismissal_modes dm ON mpm.player_id = ' . $player_id . ' AND bs.dismissal_mode_id IS NOT NULL and dm.id = bs.dismissal_mode_id and dm.name != \'Retired Hurt\' inner join matches m on m.id = mpm.match_id and m.is_official = 1 inner join series s on s.id = m.series_id inner join teams t on t.id = mpm.team_id inner join team_types tt on tt.id = t.type_id and tt.name = \'International\' inner join game_types gt on gt.id = s.game_type_id GROUP BY gt.name, bs.dismissal_mode_id';
        $sql_query = $this->_db->query($query);
        $result = $sql_query->fetchAll();

        foreach($result as $row)
        {
            $game_type = $row['gameType'];
            $dismissal_mode = $row['dismissalMode'];
            $count = $row['count'];
            if(!array_key_exists($game_type, $stats))
            {
                $stats[$game_type] = [];
            }
            $stats[$game_type][$dismissal_mode] = $count;
        }
        return $stats;
    }

    public function get_batting_stats(int $player_id)
    {
        $stats_final = [];
        $query = 'SELECT COUNT(*) AS innings, SUM(runs) AS runs, SUM(balls) AS balls, SUM(fours) AS fours, SUM(sixes) AS sixes, MAX(runs) AS highest, gt.name as gameType, count(CASE WHEN (bs.runs >= 50 and bs.runs < 100) then 1 end) as fifties, count(CASE WHEN (bs.runs >= 100 and bs.runs < 200) then 1 end) as hundreds, count(CASE WHEN (bs.runs >= 200 and bs.runs < 300) then 1 end) as twoHundreds, count(CASE WHEN (bs.runs >= 300 and bs.runs < 400) then 1 end) as threeHundreds, count(CASE WHEN (bs.runs >= 400 and bs.runs < 500) then 1 end) as fourHundreds FROM `batting_scores` bs inner join match_player_map mpm on mpm.player_id = ' . $player_id . ' and  mpm.id = bs.match_player_id inner join matches m on m.id = mpm.match_id and m.is_official = 1 inner join series s on s.id = m.series_id inner join teams t on t.id = mpm.team_id inner join team_types tt on tt.id = t.type_id and tt.name = \'International\' inner join game_types gt on gt.id = s.game_type_id group by gt.name';
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
                    'fours' => $row['fours'],
                    'sixes' => $row['sixes'],
                    'highest' => $row['highest'],
                    'fifties' => $row['fifties'],
                    'hundreds' => $row['hundreds'],
                    'twoHundreds' => $row['twoHundreds'],
                    'threeHundreds' => $row['threeHundreds'],
                    'fourHundreds' => $row['fourHundreds']
                ];

                $game_type = $row['gameType'];
                $stats_final[$game_type] = $stats;
            }
        }

        return $stats_final;
    }
}