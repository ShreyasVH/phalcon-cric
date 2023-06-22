<?php

namespace app\responses;

use app\models\Series;

class SeriesResponse
{
    public int $id;
    public string $name;
    public CountryResponse $homeCountry;
    public TourResponse $tour;
    public SeriesTypeResponse $type;
    public GameTypeResponse $gameType;
    public $startTime;
    public array $teams;

    public function __construct(int $id, string $name, CountryResponse $country, TourResponse $tour_response, SeriesTypeResponse $series_type_response, GameTypeResponse $game_type_response, $start_time, array $teams)
    {
        $this->id = $id;
        $this->homeCountry = $country;
        $this->tour = $tour_response;
        $this->type = $series_type_response;
        $this->gameType = $game_type_response;
        $this->startTime = $start_time;
        $this->teams = $teams;
    }

    public static function withAllData(Series $series, CountryResponse $country, TourResponse $tour, SeriesTypeResponse $type, GameTypeResponse $game_type, array $teams)
    {
        return new SeriesResponse($series->id, $series->name, $country, $tour, $type, $game_type, $series->start_time, $teams);
    }
}