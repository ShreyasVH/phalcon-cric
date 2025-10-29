<?php

namespace app\controllers;

use app\requests\FilterRequest;
use app\responses\Response;
use app\responses\StatsResponse;

class StatsController extends BaseController
{

    public function onConstruct()
    {

    }

    public function get_stats()
    {
        $create_request = new FilterRequest($this->request->getJsonRawBody(true));

        return $this->ok(new StatsResponse());
    }
}