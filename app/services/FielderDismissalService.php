<?php


namespace app\services;


use app\models\FielderDismissal;

class FielderDismissalService
{
    public function add($score_fielders_maps, $player_to_match_player_map)
    {
        return FielderDismissal::add($score_fielders_maps, $player_to_match_player_map);
    }
}