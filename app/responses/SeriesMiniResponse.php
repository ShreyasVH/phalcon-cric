<?php


namespace app\responses;


use app\models\Series;

class SeriesMiniResponse
{
    public int $id;
    public string $name;
    public int $homeCountryId;
    public int $tourId;
    public int $typeId;
    public int $gameTypeId;
    public $startTime;

    public function __construct(Series $series)
    {
        $this->id = $series->id;
        $this->name = $series->name;
        $this->homeCountryId = $series->home_country_id;
        $this->tourId = $series->tour_id;
        $this->typeId = $series->type_id;
        $this->gameTypeId = $series->game_type_id;
        $this->startTime = $series->start_time;
    }
}