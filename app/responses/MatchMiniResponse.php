<?php


namespace app\responses;


use app\models\Game;
use app\models\ResultType;
use app\models\WinMarginType;

class MatchMiniResponse
{
    public int $id;
    public TeamResponse $team1;
    public TeamResponse $team2;
    public ResultTypeResponse $resultType;
    public ?TeamResponse $winner = null;
    public ?int $winMargin = null;
    public ?WinMarginTypeResponse $winMarginType = null;
    public StadiumResponse $stadium;
    public $startTime;

    public function __construct(Game $match, TeamResponse $team1, TeamResponse $team2, ResultType $result_type, ?WinMarginType $win_margin_type, StadiumResponse $stadium)
    {
        $this->id = $match->id;
        $this->team1 = $team1;
        $this->team2 = $team2;
        $team_map = [
            $team1->id => $team1,
            $team2->id => $team2
        ];

        if(null != $match->winner_id)
        {
            $this->winner = $team_map[$match->winner_id];
            if(null != $match->win_margin)
            {
                $this->winMargin = $match->win_margin;
                $this->winMarginType = WinMarginTypeResponse::from_win_margin_type($win_margin_type);
            }
        }

        $this->resultType = ResultTypeResponse::from_result_type($result_type);
        $this->stadium = $stadium;
        $this->startTime = $match->start_time;
    }
}