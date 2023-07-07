<?php


namespace app\services;


use app\models\WicketKeeper;

class WicketKeeperService
{
    public function add(array $player_ids, $player_to_match_player_map)
    {
        return WicketKeeper::add($player_ids, $player_to_match_player_map);
    }
}