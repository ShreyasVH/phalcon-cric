<?php


namespace app\services;


use app\models\BattingScore;
use app\repositories\BattingScoreRepository;

class BattingScoreService
{
    private BattingScoreRepository $_batting_score_repository;

    public function __construct()
    {
        $this->_batting_score_repository = new BattingScoreRepository();
    }

    public function add(array $batting_score_requests, $player_to_match_player_map)
    {
        return BattingScore::add($batting_score_requests, $player_to_match_player_map);
    }

    public function get_dismissal_stats(int $player_id)
    {
        return $this->_batting_score_repository->get_dismissal_stats($player_id);
    }

    public function get_batting_stats(int $player_id)
    {
        return $this->_batting_score_repository->get_batting_stats($player_id);
    }

}