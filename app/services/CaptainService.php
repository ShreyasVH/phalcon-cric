<?php


namespace app\services;


use app\models\Captain;

class CaptainService
{
    public function add(array $player_ids, $player_to_match_player_map)
    {
        Captain::add($player_ids, $player_to_match_player_map);
    }

    /**
     * @param int[] $match_player_ids
     * @return Captain[]
     */
    public function get_by_match_player_ids(array $match_player_ids): array
    {
        return Captain::get_by_match_player_ids($match_player_ids);
    }
}