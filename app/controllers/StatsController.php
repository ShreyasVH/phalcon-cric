<?php

namespace app\controllers;

use app\requests\FilterRequest;
use app\responses\Response;
use app\responses\StatsResponse;
use app\services\StatsService;

class StatsController extends BaseController
{
    protected StatsService $stats_service;

    public function onConstruct()
    {
        $this->stats_service = new StatsService();
    }

    public function get_stats()
    {
        $filter_request = new FilterRequest($this->request->getJsonRawBody(true));

        return $this->ok($this->stats_service->get_stats($filter_request));
    }
}