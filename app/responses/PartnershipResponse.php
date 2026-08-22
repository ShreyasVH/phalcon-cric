<?php


namespace app\responses;


use app\models\Partnership;

class PartnershipResponse
{
    public int $id;
    public int $innings;
    public int $wicket;
    public int $runs;
    public int $balls;
    public bool $ended;
    public PlayerContribution $player1;
    public PlayerContribution $player2;

    public function __construct(Partnership $partnership, PlayerMiniResponse $player_1, PlayerMiniResponse $player_2, )
    {
        $this->id = $partnership->id;
        $this->innings = $partnership->innings;
        $this->wicket = $partnership->wicket;
        $this->runs = $partnership->runs;
        $this->balls = $partnership->balls;
        $this->ended = $partnership->ended;
        $this->player1 = new PlayerContribution($player_1, $partnership->runs_1, $partnership->balls_1);
        $this->player2 = new PlayerContribution($player_2, $partnership->runs_2, $partnership->balls_2);
    }
}

class PlayerContribution
{
    public PlayerMiniResponse $player;
    public int $runs;
    public int $balls;

    public function __construct(PlayerMiniResponse $player, int $runs, int $balls)
    {
        $this->player = $player;
        $this->runs = $runs;
        $this->balls = $balls;
    }
}