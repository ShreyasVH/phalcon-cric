<?php


namespace app\services;


use app\models\ManOfTheMatch;

class ManOfTheMatchService
{
    public function add(array $player_ids, $match_player_maps)
    {
        return ManOfTheMatch::add($player_ids, $match_player_maps);
    }

    /**
     * @param int[] $match_player_ids
     * @return ManOfTheMatch[]
     */
    public function get_by_match_player_ids(array $match_player_ids): array
    {
        return ManOfTheMatch::get_by_match_player_ids($match_player_ids);
    }
}