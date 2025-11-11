<?php

namespace app\repositories;

use app\models\Player;
use app\requests\FilterRequest;
use app\requests\players\CreateRequest;
use app\responses\StatsResponse;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Di\Injectable;

class PlayerRepository extends Injectable
{
    private Mysql $_db;

    public function __construct()
    {
        $this->_db = $this->getDI()->get('db');
    }

    public function create(CreateRequest $create_request)
    {
        $player = Player::fromRequest($create_request);
        $player->save();
        return $player;
    }

    public function findByNameAndCountryIdAndDateOfBirth(string $name, int $countryId, $dateOfBirth)
    {
        return Player::findByNameAndCountryIdAndDateOfBirth($name, $countryId, $dateOfBirth);
    }

    public function getAll(int $page, int $limit)
    {
        return Player::getAll($page, $limit);
    }

    public function getTotalCount()
    {
        return Player::getTotalCount();
    }

    public function get_field_name_with_table_prefix(string $field): string
    {
        return match ($field) {
            "gameType" => "s.game_type_id",
            "stadium" => "m.stadium_id",
            "team" => "t.id",
            "opposingTeam" => "IF(t.id = m.team1_id, m.team2_id, m.team1_id)",
            "teamType" => "t.type_id",
            "country" => "p.country_id",
            "series" => "s.id",
            "year" => "YEAR(m.start_time)",
            "playerName" => "p.name",
            default => "",
        };
    }

    public function get_field_name_for_display(string $field): string
    {
        return match ($field) {
            "runs" => "runs",
            "balls" => "balls",
            "innings" => "innings",
            "notOuts" => "notouts",
            "fifties" => "fifties",
            "hundreds" => "hundreds",
            "highest" => "highest",
            "fours" => "fours",
            "sixes" => "sixes",
            "wickets" => "wickets",
            "maidens" => "maidens",
            "fifers" => "fifers",
            "tenWickets" => "tenWickets",
            "fielderCatches" => "fielderCatches",
            "keeperCatches" => "keeperCatches",
            "stumpings" => "stumpings",
            "runOuts" => "runOuts",
            default => "",
        };
    }

    public function get_batting_stats(FilterRequest $filter_request): StatsResponse
    {
        $stats_response = new StatsResponse();
        $stat_list = [];

        $query = "select p.id as playerId, p.name AS name, sum(bs.runs) AS `runs`, count(0) AS `innings`, sum(`bs`.`balls`) AS `balls`, sum(`bs`.`fours`) AS `fours`, sum(`bs`.`sixes`) AS `sixes`, max(`bs`.`runs`) AS `highest`, count((case when (`bs`.`dismissal_mode_id` is null) then 1 end)) AS `notouts`, count((case when ((`bs`.`runs` >= 50) and (`bs`.`runs` < 100)) then 1 end)) AS `fifties`, count((case when ((`bs`.`runs` >= 100)) then 1 end)) AS `hundreds` from batting_scores bs " .
            "inner join match_player_map mpm on mpm.id = bs.match_player_id " .
            "inner join players p on p.id = mpm.player_id " .
            "inner join matches m on m.id = mpm.match_id " .
            "inner join series s on s.id = m.series_id " .
            "inner join stadiums st on st.id = m.stadium_id " .
            "inner join teams t on t.id = mpm.team_id";

        $count_query = "select count(distinct p.id) as count from batting_scores bs " .
            "inner join match_player_map mpm on mpm.id = bs.match_player_id " .
            "inner join players p on p.id = mpm.player_id " .
            "inner join matches m on m.id = mpm.match_id " .
            "inner join series s on s.id = m.series_id " .
            "inner join stadiums st on st.id = m.stadium_id " .
            "inner join teams t on t.id = mpm.team_id";

        $where_query_parts = [];
        foreach($filter_request->filters as $field => $value_list)
        {
            $field_name_with_table_prefix = $this->get_field_name_with_table_prefix($field);
            if(!empty($field_name_with_table_prefix) && !empty($value_list))
            {
                $where_query_parts[] = $field_name_with_table_prefix . " in (" . implode(", ", $value_list) .  ")";
            }
        }

        foreach($filter_request->rangeFilters as $field => $range_values)
        {
            $field_name_with_table_prefix = $this->get_field_name_with_table_prefix($field);
            if(!empty($field_name_with_table_prefix) && !empty($range_values))
            {
                if(array_key_exists('from', $range_values))
                {
                    $where_query_parts[] = $field_name_with_table_prefix . " >= " . $range_values['from'];
                }

                if(array_key_exists('to', $range_values))
                {
                    $where_query_parts[] = $field_name_with_table_prefix . " >= " . $range_values['to'];
                }
            }
        }

        if(!empty($where_query_parts))
        {
            $count_query .= " where " . implode(" and ", $where_query_parts);
            $query .= " where " . implode(" and ", $where_query_parts);
        }

        $query .= " group by playerId";

        $sort_list = [];
        foreach($filter_request->sortMap as $field => $value)
        {
            $sort_field_name = $this->get_field_name_for_display($field);
            if(!empty($sort_field_name))
            {
                $sort_list[] = $sort_field_name . " " . $value;
            }
        }

        if(empty($sort_list))
        {
            $sort_list[] = $this->get_field_name_for_display("runs") . " desc";
        }
        $query .= " order by " . implode(", ", $sort_list);

        $query .= " limit " . min(30, $filter_request->count) . " offset " . $filter_request->offset;

        $sql_count_query = $this->_db->query($count_query);
        $count_result = $sql_count_query->fetchArray();
        $stats_response->count = $count_result['count'];

        $sql_query = $this->_db->query($query);
        $result = $sql_query->fetchAll();

        foreach($result as $row)
        {
            $innings = $row['innings'];
            if($innings > 0)
            {
                $stat_list[] = [
                    'id' => (string) $row['playerId'],
                    'name' => $row['name'],
                    'innings' => (string) $row['innings'],
                    'runs' => (string) $row['runs'],
                    'balls' => (string) $row['balls'],
                    'notOuts' => (string) $row['notouts'],
                    'fours' => (string) $row['fours'],
                    'sixes' => (string) $row['sixes'],
                    'highest' => (string) $row['highest'],
                    'fifties' => (string) $row['fifties'],
                    'hundreds' => (string) $row['hundreds']
                ];
            }
        }

        $stats_response->stats = $stat_list;

        return $stats_response;
    }

    public function get_bowling_stats(FilterRequest $filter_request): StatsResponse
    {
        $stats_response = new StatsResponse();
        $stat_list = [];

        $query = "select p.id as playerId, p.name AS name, sum(bf.wickets) AS wickets, sum(bf.runs) as runs, count(0) AS `innings`, sum(`bf`.`balls`) AS `balls`, sum(`bf`.`maidens`) AS `maidens`, count((case when ((`bf`.`wickets` >= 5) and (`bf`.`wickets` < 10)) then 1 end)) AS `fifers`, count((case when (`bf`.`wickets` = 10) then 1 end)) AS `tenWickets` from bowling_figures bf " .
            "inner join match_player_map mpm on mpm.id = bf.match_player_id " .
            "inner join players p on p.id = mpm.player_id " .
            "inner join matches m on m.id = mpm.match_id " .
            "inner join series s on s.id = m.series_id " .
            "inner join stadiums st on st.id = m.stadium_id " .
            "inner join teams t on t.id = mpm.team_id";

        $count_query = "select count(distinct p.id) as count from bowling_figures bf " .
            "inner join match_player_map mpm on mpm.id = bf.match_player_id " .
            "inner join players p on p.id = mpm.player_id " .
            "inner join matches m on m.id = mpm.match_id " .
            "inner join series s on s.id = m.series_id " .
            "inner join stadiums st on st.id = m.stadium_id " .
            "inner join teams t on t.id = mpm.team_id";

        $where_query_parts = [];
        foreach($filter_request->filters as $field => $value_list)
        {
            $field_name_with_table_prefix = $this->get_field_name_with_table_prefix($field);
            if(!empty($field_name_with_table_prefix) && !empty($value_list))
            {
                $where_query_parts[] = $field_name_with_table_prefix . " in (" . implode(", ", $value_list) .  ")";
            }
        }

        foreach($filter_request->rangeFilters as $field => $range_values)
        {
            $field_name_with_table_prefix = $this->get_field_name_with_table_prefix($field);
            if(!empty($field_name_with_table_prefix) && !empty($range_values))
            {
                if(array_key_exists('from', $range_values))
                {
                    $where_query_parts[] = $field_name_with_table_prefix . " >= " . $range_values['from'];
                }

                if(array_key_exists('to', $range_values))
                {
                    $where_query_parts[] = $field_name_with_table_prefix . " <= " . $range_values['to'];
                }
            }
        }

        if(!empty($where_query_parts))
        {
            $count_query .= " where " . implode(" and ", $where_query_parts);
            $query .= " where " . implode(" and ", $where_query_parts);
        }

        $query .= " group by playerId";

        $sort_list = [];
        foreach($filter_request->sortMap as $field => $value)
        {
            $sort_field_name = $this->get_field_name_for_display($field);
            if(!empty($sort_field_name))
            {
                $sort_list[] = $sort_field_name . " " . $value;
            }
        }

        if(empty($sort_list))
        {
            $sort_list[] = $this->get_field_name_for_display("wickets") . " desc";
        }
        $query .= " order by " . implode(", ", $sort_list);

        $query .= " limit " . min(30, $filter_request->count) . " offset " . $filter_request->offset;

        $sql_count_query = $this->_db->query($count_query);
        $count_result = $sql_count_query->fetchArray();
        $stats_response->count = $count_result['count'];

        $sql_query = $this->_db->query($query);
        $result = $sql_query->fetchAll();

        foreach($result as $row)
        {
            $innings = $row['innings'];
            if($innings > 0)
            {
                $stat_list[] = [
                    'id' => (string) $row['playerId'],
                    'name' => $row['name'],
                    'innings' => (string) $row['innings'],
                    'wickets' => (string) $row['wickets'],
                    'runs' => (string) $row['runs'],
                    'balls' => (string) $row['balls'],
                    'maidens' => (string) $row['maidens'],
                    'fifers' => (string) $row['fifers'],
                    'tenWickets' => (string) $row['tenWickets']
                ];
            }
        }

        $stats_response->stats = $stat_list;

        return $stats_response;
    }
}