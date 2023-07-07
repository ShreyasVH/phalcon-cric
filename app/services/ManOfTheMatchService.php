<?php


namespace app\services;


use app\models\ManOfTheMatch;

class ManOfTheMatchService
{
    public function add(array $player_ids, $match_player_maps)
    {
        return ManOfTheMatch::add($player_ids, $match_player_maps);
    }
}