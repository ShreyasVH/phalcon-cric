<?php


namespace app\services;


use app\models\WicketKeeper;

class WicketKeeperService
{
    public function add(array $player_ids, $player_to_match_player_map)
    {
        return WicketKeeper::add($player_ids, $player_to_match_player_map);
    }

    /**
     * @param int[] $match_player_ids
     * @return WicketKeeper[]
     */
    public function get_by_match_player_ids(array $match_player_ids): array
    {
        return WicketKeeper::get_by_match_player_ids($match_player_ids);
    }

    /**
     * @param array $match_player_ids
     */
    public function remove(array $match_player_ids)
    {
        WicketKeeper::remove($match_player_ids);
    }
}