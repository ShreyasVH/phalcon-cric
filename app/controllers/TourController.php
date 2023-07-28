<?php

namespace app\controllers;

use app\exceptions\NotFoundException;
use app\models\GameType;
use app\models\Series;
use app\models\Tour;
use app\requests\tours\CreateRequest;
use app\responses\PaginatedResponse;
use app\responses\SeriesMiniResponse;
use app\responses\TourMiniResponse;
use app\responses\TourResponse;
use app\services\GameTypeService;
use app\services\SeriesService;
use app\services\TourService;
use Phalcon\Http\Response;

class TourController extends BaseController
{
    protected TourService $tour_service;
    protected SeriesService $series_service;
    protected GameTypeService $game_type_service;

    public function onConstruct()
    {
        $this->tour_service = new TourService();
        $this->series_service = new SeriesService();
        $this->game_type_service = new GameTypeService();
    }

    public function create()
    {
        $create_request = CreateRequest::fromPostRequest($this->request->getJsonRawBody(true));
        $tour = $this->tour_service->create($create_request);

        return $this->created(TourMiniResponse::from_tour($tour));
    }

    public function get_all_for_year(int $year): Response
    {
        $page = $this->request->getQuery('page', 'int', 1);
        $limit = $this->request->getQuery('limit', 'int', 25);
        $tours = $this->tour_service->get_all_for_year($year, $page, $limit);
        $tour_responses = array_map(function (Tour $tour) {
            return TourMiniResponse::from_tour($tour);
        }, $tours);
        $total_count = 0;
        if($page == 1)
        {
            $total_count = $this->tour_service->get_total_count_for_year($year);
        }
        $paginatedResponse = new PaginatedResponse($total_count, $tour_responses, $page, $limit);
        return $this->ok($paginatedResponse);
    }

    public function get_all_years()
    {
        $years = $this->tour_service->get_all_years();
        return $this->ok($years);
    }

    /**
     * @throws NotFoundException
     */
    public function get_by_id(int $id): Response
    {
        /** @var Tour $tour */
        $tour = $this->tour_service->get_by_id($id);
        if(null == $tour)
        {
            throw new NotFoundException('Tour');
        }

        $tour_response = TourResponse::from_tour($tour);
        $series_list = $this->series_service->get_by_tour_id($id);

        $game_type_ids = array_map(function (Series $series) {
            return $series->game_type_id;
        }, $series_list);
        $game_types = $this->game_type_service->get_by_ids($game_type_ids);
        $game_type_map = array_combine(array_map(function (GameType $game_type) {
            return $game_type->id;
        }, $game_types), $game_types);

        $series_mini_responses = array_map(function (Series $series) use ($game_type_map) {
            return new SeriesMiniResponse($series, $game_type_map[$series->game_type_id]);
        }, $series_list);
        $tour_response->seriesList = $series_mini_responses;

        return $this->ok($tour_response);
    }
}