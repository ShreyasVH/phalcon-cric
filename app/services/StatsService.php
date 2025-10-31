<?php


namespace app\services;


use app\repositories\PlayerRepository;
use app\requests\FilterRequest;
use app\responses\StatsResponse;

class StatsService
{
    protected PlayerRepository $player_repository;

    public function __construct() {
        $this->player_repository = new PlayerRepository();
    }

    public function get_stats(FilterRequest $filter_request): StatsResponse
    {
        $stats_response = new StatsResponse();

        if("batting" === $filter_request->type)
        {
            $stats_response = $this->player_repository->get_batting_stats($filter_request);
        }

        return $stats_response;
    }
}