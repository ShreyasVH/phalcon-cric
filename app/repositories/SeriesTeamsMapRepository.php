<?php


namespace app\repositories;


use app\models\SeriesTeamsMap;

class SeriesTeamsMapRepository
{
    public function add(int $series_id, array $team_ids)
    {
        SeriesTeamsMap::add($series_id, $team_ids);
    }
}