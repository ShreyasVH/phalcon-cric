<?php


namespace app\responses;


use app\models\GameType;
use app\models\Series;

class SeriesMiniResponse
{
    public int $id;
    public string $name;
    public int $homeCountryId;
    public int $tourId;
    public int $typeId;
    public GameTypeResponse $gameType;
    public $startTime;

    public function __construct(Series $series, GameType $game_type)
    {
        $this->id = $series->id;
        $this->name = $series->name;
        $this->homeCountryId = $series->home_country_id;
        $this->tourId = $series->tour_id;
        $this->typeId = $series->type_id;
        $this->gameType = GameTypeResponse::from_game_type($game_type);
        $this->startTime = $series->start_time;
    }
}