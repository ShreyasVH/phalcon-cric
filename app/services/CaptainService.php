<?php


namespace app\services;


use app\models\Captain;

class CaptainService
{
    public function add(array $player_ids, $player_to_match_player_map)
    {
        Captain::add($player_ids, $player_to_match_player_map);
    }
}