<?php

namespace app\controllers;

use app\requests\countries\CreateRequest;
use app\responses\CountryResponse;
use app\responses\PaginatedResponse;
use app\responses\Response;
use app\services\CountryService;

class CountryController extends BaseController
{
    protected CountryService $country_service;

    public function onConstruct()
    {
        $this->country_service = new CountryService();
    }

    public function create()
    {
        $create_request = CreateRequest::fromPostRequest($this->request->getJsonRawBody(true));
        $country = $this->country_service->create($create_request);

        return $this->created(CountryResponse::from_country($country));
    }

    public function searchByName($name)
    {
        $countries = $this->country_service->searchByName($name);
        $countryResponses = array_map(function ($country) {
            return CountryResponse::from_country($country);
        }, $countries);
        return $this->ok($countryResponses);
    }

    public function getAll()
    {
        $page = $this->request->getQuery('page', 'int', 1);
        $limit = $this->request->getQuery('limit', 'int', 25);
        $countries = $this->country_service->getAll($page, $limit);
        $countryResponses = array_map(function ($country) {
            return CountryResponse::from_country($country);
        }, $countries);
        $totalCount = 0;
        if($page == 1)
        {
            $totalCount = $this->country_service->getTotalCount();
        }
        $paginatedResponse = new PaginatedResponse($totalCount, $countryResponses, $page, $limit);
        return $this->ok($paginatedResponse);
    }
}