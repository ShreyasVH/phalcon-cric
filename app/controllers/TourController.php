<?php

namespace app\controllers;

use app\requests\tours\CreateRequest;
use app\responses\TourResponse;
use app\services\TourService;

class TourController extends BaseController
{
    protected TourService $tour_service;

    public function onConstruct()
    {
        $this->tour_service = new TourService();
    }

    public function create()
    {
        $create_request = CreateRequest::fromPostRequest($this->request->getJsonRawBody(true));
        $tour = $this->tour_service->create($create_request);

        return $this->created(TourResponse::from_tour($tour));
    }
}