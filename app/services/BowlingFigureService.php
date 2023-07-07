<?php


namespace app\services;


use app\models\BowlingFigure;

class BowlingFigureService
{
    public function add(array $bowling_figure_requests, $player_to_match_player_map)
    {
        return BowlingFigure::add($bowling_figure_requests, $player_to_match_player_map);
    }
}