<?php

namespace app\controllers;

use app\models\Tour;
use app\requests\tours\CreateRequest;
use app\responses\PaginatedResponse;
use app\responses\TourResponse;
use app\services\TourService;
use Phalcon\Http\Response;

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

    public function get_all_for_year(int $year): Response
    {
        $page = $this->request->getQuery('page', 'int', 1);
        $limit = $this->request->getQuery('limit', 'int', 25);
        $tours = $this->tour_service->get_all_for_year($year, $page, $limit);
        $tour_responses = array_map(function (Tour $tour) {
            return TourResponse::from_tour($tour);
        }, $tours);
        $total_count = 0;
        if($page == 1)
        {
            $total_count = $this->tour_service->get_total_count_for_year($year);
        }
        $paginatedResponse = new PaginatedResponse($total_count, $tour_responses, $page, $limit);
        return $this->ok($paginatedResponse);
    }
}