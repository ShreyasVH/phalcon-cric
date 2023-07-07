<?php


namespace app\services;


use app\models\BattingScore;

class BattingScoreService
{
    public function add(array $batting_score_requests, $player_to_match_player_map)
    {
        return BattingScore::add($batting_score_requests, $player_to_match_player_map);
    }

}