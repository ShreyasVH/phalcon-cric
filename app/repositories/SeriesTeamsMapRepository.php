<?php


namespace app\repositories;


use app\models\SeriesTeamsMap;

class SeriesTeamsMapRepository
{
    public function add(int $series_id, array $team_ids)
    {
        SeriesTeamsMap::add($series_id, $team_ids);
    }

    public function get_by_series_ids(array $series_ids)
    {
        return SeriesTeamsMap::get_by_series_ids($series_ids);
    }
}