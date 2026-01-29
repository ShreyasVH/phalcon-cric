<?php


namespace app\responses;


use app\models\Game;
use app\models\GameType;
use app\models\Series;

class MatchResponse
{
    public int $id;
    public SeriesMiniResponse $series;
    public TeamResponse $team1;
    public TeamResponse $team2;
    public ?TeamResponse $tossWinner = null;
    public ?TeamResponse $batFirst = null;
    public ResultTypeResponse $resultType;
    public ?TeamResponse $winner = null;
    public ?int $winMargin = null;
    public ?WinMarginTypeResponse $winMarginType = null;
    public StadiumResponse $stadium;
    public $startTime;
    public array $players;
    public array $battingScores;
    public array $bowlingFigures;
    public array $extras;
    public array $manOfTheMatchList;
    public array $captains;
    public array $wicketKeepers;
    public array $tags;

    public function __construct(Game $match, Series $series, GameType $game_type, TeamResponse $team1, TeamResponse $team2, ResultTypeResponse $result_type, ?WinMarginTypeResponse $win_margin_type_response, StadiumResponse $stadium, $players, array $batting_scores, array $bowling_figures, array $extras, array $man_of_the_match_list, array $captains, array $wicket_keepers, array $tags)
    {
        $this->id = $match->id;
        $this->series = new SeriesMiniResponse($series, $game_type);
        $this->team1 = $team1;
        $this->team2 = $team2;
        $team_map = [
            $team1->id => $team1,
            $team2->id => $team2
        ];

        if(null != $match->toss_winner_id)
        {
            $this->tossWinner = $team_map[$match->toss_winner_id];
            $this->batFirst = $team_map[$match->bat_first_id];
        }

        if(null != $match->winner_id)
        {
            $this->winner = $team_map[$match->winner_id];
            $this->winMargin = $match->win_margin;
            $this->winMarginType = $win_margin_type_response;
        }

        $this->resultType = $result_type;
        $this->stadium = $stadium;
        $this->startTime = $match->start_time;
        $this->players = $players;

        $player_map = [];
        foreach($players as $team_id => $player_list)
        {
            foreach($player_list as $player)
            {
                $player_map[$player->id] = $player;
            }
        }

        $this->battingScores = $batting_scores;
        $this->bowlingFigures = $bowling_figures;
        $this->extras = $extras;
        $this->manOfTheMatchList = array_map(function ($player_id) use ($player_map) {
            return $player_map[$player_id];
        }, $man_of_the_match_list);

        $this->captains = array_map(function ($player_id) use ($player_map) {
            return $player_map[$player_id];
        }, $captains);

        $this->wicketKeepers = array_map(function ($player_id) use ($player_map) {
            return $player_map[$player_id];
        }, $wicket_keepers);

        $this->tags = $tags;
    }
}