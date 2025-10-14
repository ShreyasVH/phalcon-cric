<?php

namespace app\responses;

use app\models\GameType;
use app\models\Series;
use app\models\SeriesType;
use app\models\Tag;

class SeriesDetailedResponse
{
    public int $id;
    public string $name;
    public SeriesTypeResponse $type;
    public GameTypeResponse $gameType;
    public $startTime;
    public array $teams;
    /** @var MatchMiniResponse[]  */
    public array $matches;
    /** @var Tag[] */
    public array $tags;

    /**
     * SeriesDetailedResponse constructor.
     * @param Series $series
     * @param SeriesType $series_type
     * @param GameType $game_type
     * @param TeamResponse[] $teams
     * @param MatchMiniResponse[] $matches
     * @param Tag[] $tags
     */
    public function __construct(Series $series, SeriesType $series_type, GameType $game_type, array $teams, array $matches, array $tags)
    {
        $this->id = $series->id;
        $this->name = $series->name;
        $this->type = SeriesTypeResponse::from_series_type($series_type);
        $this->gameType = GameTypeResponse::from_game_type($game_type);
        $this->startTime = $series->start_time;
        $this->teams = $teams;
        $this->matches = $matches;
        $this->tags = $tags;
    }
}