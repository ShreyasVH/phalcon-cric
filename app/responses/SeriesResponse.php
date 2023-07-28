<?php

namespace app\responses;

use app\models\Series;

class SeriesResponse
{
    public int $id;
    public string $name;
    public CountryResponse $homeCountry;
    public TourMiniResponse $tour;
    public SeriesTypeResponse $type;
    public GameTypeResponse $gameType;
    public $startTime;
    public array $teams;
    public array $manOfTheSeriesList;

    public function __construct(int $id, string $name, CountryResponse $country, TourMiniResponse $tour_response, SeriesTypeResponse $series_type_response, GameTypeResponse $game_type_response, $start_time, array $teams, array $players)
    {
        $this->id = $id;
        $this->name = $name;
        $this->homeCountry = $country;
        $this->tour = $tour_response;
        $this->type = $series_type_response;
        $this->gameType = $game_type_response;
        $this->startTime = $start_time;
        $this->teams = $teams;
        $this->manOfTheSeriesList = $players;
    }

    public static function withAllData(Series $series, CountryResponse $country, TourMiniResponse $tour, SeriesTypeResponse $type, GameTypeResponse $game_type, array $teams, array $players)
    {
        return new SeriesResponse($series->id, $series->name, $country, $tour, $type, $game_type, $series->start_time, $teams, $players);
    }
}