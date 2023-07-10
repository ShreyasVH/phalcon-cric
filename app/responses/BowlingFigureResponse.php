<?php


namespace app\responses;


use app\models\BowlingFigure;

class BowlingFigureResponse
{
    public int $id;
    public PlayerMiniResponse $player;
    public int $balls;
    public int $maidens;
    public int $runs;
    public int $wickets;
    public int $innings;

    public function __construct(BowlingFigure $bowling_figure, PlayerMiniResponse $player)
    {
        $this->id = $bowling_figure->id;
        $this->player = $player;
        $this->balls = $bowling_figure->balls;
        $this->maidens = $bowling_figure->maidens;
        $this->runs = $bowling_figure->runs;
        $this->wickets = $bowling_figure->wickets;
        $this->innings = $bowling_figure->innings;
    }
}