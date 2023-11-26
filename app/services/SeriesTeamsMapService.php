<?php


namespace app\services;


use app\models\SeriesTeamsMap;
use app\repositories\SeriesTeamsMapRepository;

class SeriesTeamsMapService
{
    protected SeriesTeamsMapRepository $series_teams_map_repository;

    public function __construct()
    {
        $this->series_teams_map_repository = new SeriesTeamsMapRepository();
    }

    public function add(int $series_id, array $team_ids)
    {
        $this->series_teams_map_repository->add($series_id, $team_ids);
    }

    public function get_by_series_ids(array $series_ids)
    {
        return $this->series_teams_map_repository->get_by_series_ids($series_ids);
    }

    public function remove_players(int $series_id, array $team_ids)
    {
        SeriesTeamsMap::remove_players($series_id, $team_ids);
    }

    public function remove(int $series_id)
    {
        SeriesTeamsMap::remove($series_id);
    }
}