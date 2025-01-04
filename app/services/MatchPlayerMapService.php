<?php


namespace app\services;


use app\models\MatchPlayerMap;
use app\requests\players\MergeRequest;

class MatchPlayerMapService
{
    public function add(int $match_id, array $player_ids, $player_team_map)
    {
        return MatchPlayerMap::add($match_id, $player_ids, $player_team_map);
    }

    /**
     * @param int $match_id
     * @return MatchPlayerMap[]
     */
    public function get_by_match_id(int $match_id): array
    {
        return MatchPlayerMap::get_by_match_id($match_id);
    }

    /**
     * @param int $match_id
     */
    public function remove(int $match_id)
    {
        MatchPlayerMap::remove($match_id);
    }

    public function merge(MergeRequest $mergeRequest)
    {
        MatchPlayerMap::merge($mergeRequest);
    }
}