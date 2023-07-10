<?php


namespace app\responses;


use app\models\BattingScore;

class BattingScoreResponse
{
    public int $id;
    public PlayerMiniResponse $player;
    public int $runs;
    public int $balls;
    public int $fours;
    public int $sixes;
    public ?DismissalModeResponse $dismissalMode;
    public ?PlayerMiniResponse $bowler;
    public array $fielders;
    public int $innings;
    public ?int $number;

    public function __construct(BattingScore $batting_score, PlayerMiniResponse $player, ?DismissalModeResponse $dismissal_mode, ?PlayerMiniResponse $bowler, array $fielders)
    {
        $this->id = $batting_score->id;
        $this->player = $player;
        $this->runs = $batting_score->runs;
        $this->balls = $batting_score->balls;
        $this->fours = $batting_score->fours;
        $this->sixes = $batting_score->sixes;
        $this->dismissalMode = $dismissal_mode;
        $this->bowler = $bowler;
        $this->fielders = $fielders;
        $this->innings = $batting_score->innings;
        $this->number = $batting_score->number;
    }
}