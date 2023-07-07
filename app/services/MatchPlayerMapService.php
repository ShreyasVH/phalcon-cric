<?php


namespace app\services;


use app\models\MatchPlayerMap;

class MatchPlayerMapService
{
    public function add(int $match_id, array $player_ids, $player_team_map)
    {
        return MatchPlayerMap::add($match_id, $player_ids, $player_team_map);
    }
}