<?php


namespace app\repositories;


use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Di\Injectable;

class FielderDismissalRepository extends Injectable
{
    private Mysql $_db;

    public function __construct()
    {
        $this->_db = $this->getDI()->get('db');
    }

    public function get_fielding_stats(int $player_id)
    {
        $stats_final = [];

        $query = 'select dm.name as dismissalMode, count(*) as count, gt.name as gameType from fielder_dismissals fd inner join match_player_map mpm on mpm.id = fd.match_player_id inner join batting_scores bs on bs.id = fd.score_id and mpm.player_id = ' . $player_id . ' inner join dismissal_modes dm on dm.id = bs.dismissal_mode_id inner join matches m on m.id = mpm.match_id and m.is_official = 1 inner join series s on s.id = m.series_id inner join teams t on t.id = mpm.team_id inner join team_types tt on tt.id = t.type_id and tt.name = \'International\' inner join game_types gt on gt.id = s.game_type_id group by gt.name, dm.name';
        $sql_query = $this->_db->query($query);
        $result = $sql_query->fetchAll();

        foreach($result as $row)
        {
            $game_type = $row['gameType'];
            $dismissal_mode = $row['dismissalMode'];
            $count = $row['count'];
            if(!array_key_exists($game_type, $stats_final))
            {
                $stats_final[$game_type] = [];
            }
            $stats_final[$game_type][$dismissal_mode] = $count;
        }

        return $stats_final;
    }
}