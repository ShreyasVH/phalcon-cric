<?php


namespace app\requests\matches;


class CreateRequest
{
    public int $seriesId;
    public int $team1Id;
    public int $team2Id;
    public ?int $tossWinnerId;
    public ?int $batFirstId;
    public int $resultTypeId;
    public ?int $winnerId;
    public ?int $winMargin;
    public ?int $winMarginTypeId;
    public int $stadiumId;
    public $startTime;
    public array $players = [];
    public array $bench = [];
    public array $extras = [];
    public array $battingScores = [];
    public array $bowlingFigures = [];
    public array $manOfTheMatchList = [];
    public array $captains = [];
    public array $wicketKeepers = [];
    public bool $isOfficial = true;

    public function __construct(array $create_request)
    {
        $this->seriesId = $create_request['seriesId'];
        $this->team1Id = $create_request['team1Id'];
        $this->team2Id = $create_request['team2Id'];
        if(array_key_exists('tossWinnerId', $create_request))
        {
            $this->tossWinnerId = $create_request['tossWinnerId'];
        }
        if(array_key_exists('batFirstId', $create_request))
        {
            $this->batFirstId = $create_request['batFirstId'];
        }
        $this->resultTypeId = $create_request['resultTypeId'];
        if(array_key_exists('winnerId', $create_request))
        {
            $this->winnerId = $create_request['winnerId'];
            $this->winMargin = $create_request['winMargin'];
            $this->winMarginTypeId = $create_request['winMarginTypeId'];
        }
        $this->stadiumId = $create_request['stadiumId'];
        $this->startTime = $create_request['startTime'];
        if(array_key_exists('isOfficial', $create_request) && null != $create_request['isOfficial'])
        {
            $this->isOfficial = $create_request['isOfficial'];
        }

        if(array_key_exists('players', $create_request) && is_array($create_request['players']))
        {
            $players = [];
            foreach($create_request['players'] as $player)
            {
                $players[] = new PlayerRequest($player);
            }
            $this->players = $players;
        }

        if(array_key_exists('bench', $create_request) && is_array($create_request['bench']))
        {
            $players = [];
            foreach($create_request['bench'] as $player)
            {
                $players[] = new PlayerRequest($player);
            }
            $this->bench = $players;
        }

        if(array_key_exists('battingScores', $create_request) && is_array($create_request['battingScores']))
        {
            $batting_scores = [];

            foreach($create_request['battingScores'] as $batting_score_request)
            {
                $batting_scores[] = new BattingScoreRequest($batting_score_request);
            }

            $this->battingScores = $batting_scores;
        }

        if(array_key_exists('bowlingFigures', $create_request) && is_array($create_request['bowlingFigures']))
        {
            $bowling_figures = [];

            foreach($create_request['bowlingFigures'] as $bowling_figure_request)
            {
                $bowling_figures[] = new BowlingFigureRequest($bowling_figure_request);
            }

            $this->bowlingFigures = $bowling_figures;
        }

        if(array_key_exists('extras', $create_request) && is_array($create_request['extras']))
        {
            $extras = [];

            foreach($create_request['extras'] as $extras_request)
            {
                $extras[] = new ExtrasRequest($extras_request);
            }

            $this->extras = $extras;
        }

        if(array_key_exists('manOfTheMatchList', $create_request) && is_array($create_request['manOfTheMatchList']))
        {
            $this->manOfTheMatchList = $create_request['manOfTheMatchList'];
        }

        if(array_key_exists('captains', $create_request) && is_array($create_request['captains']))
        {
            $this->captains = $create_request['captains'];
        }

        if(array_key_exists('wicketKeepers', $create_request) && is_array($create_request['wicketKeepers']))
        {
            $this->wicketKeepers = $create_request['wicketKeepers'];
        }
    }

    public function validate()
    {

    }
}