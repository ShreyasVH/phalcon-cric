<?php


namespace app\services;


use app\models\ManOfTheSeries;

class ManOfTheSeriesService
{
    public function add(int $series_id, array $player_ids)
    {
        ManOfTheSeries::add($series_id, $player_ids);
    }

    public function get_by_series_ids(array $series_ids)
    {
        return ManOfTheSeries::get_by_series_ids($series_ids);
    }

    public function remove_players(int $series_id, array $player_ids)
    {
        ManOfTheSeries::remove_players($series_id, $player_ids);
    }

    public function remove(int $series_id)
    {
        ManOfTheSeries::remove($series_id);
    }
}