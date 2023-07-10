<?php


namespace app\services;


use app\models\BowlingFigure;
use app\repositories\BowlingFigureRepository;

class BowlingFigureService
{
    private BowlingFigureRepository $_bowling_figure_repository;
    public function __construct()
    {
        $this->_bowling_figure_repository = new BowlingFigureRepository();
    }

    public function add(array $bowling_figure_requests, $player_to_match_player_map)
    {
        return BowlingFigure::add($bowling_figure_requests, $player_to_match_player_map);
    }

    public function get_bowling_stats($player_id)
    {
        return $this->_bowling_figure_repository->get_bowling_stats($player_id);
    }
}