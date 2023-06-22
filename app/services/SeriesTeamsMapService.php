<?php


namespace app\services;


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
}