<?php

namespace app\controllers;

use app\exceptions\NotFoundException;
use app\models\Country;
use app\models\Stadium;
use app\requests\stadiums\CreateRequest;
use app\responses\CountryResponse;
use app\responses\StadiumResponse;
use app\responses\PaginatedResponse;
use app\services\CountryService;
use app\services\StadiumService;

class StadiumController extends BaseController
{
    protected StadiumService $stadiumService;
    protected CountryService $countryService;

    public function onConstruct()
    {
        $this->stadiumService = new StadiumService();
        $this->countryService = new CountryService();
    }

    public function create()
    {
        $createRequest = CreateRequest::fromPostRequest($this->request->getJsonRawBody(true));

        $country = $this->countryService->getById($createRequest->countryId);
        if(null == $country)
        {
            throw new NotFoundException('Country');
        }

        $stadium = $this->stadiumService->create($createRequest);

        return $this->created(StadiumResponse::withStadiumAndCountry($stadium, CountryResponse::from_country($country)));
    }

    public function getAll()
    {
        $page = $this->request->getQuery('page', 'int', 1);
        $limit = $this->request->getQuery('limit', 'int', 25);
        $stadiums = $this->stadiumService->getAll($page, $limit);
        $countryIds = array_map(function (Stadium $stadium) {
            return $stadium->country_id;
        }, $stadiums);
        $countries = $this->countryService->getByIds($countryIds);
        $countryMap = array_combine(array_map(function (Country $country) {
            return $country->id;
        }, $countries), $countries);

        $stadiumResponses = array_map(function (Stadium $stadium) use ($countryMap) {
            return StadiumResponse::withStadiumAndCountry($stadium, CountryResponse::from_country($countryMap[$stadium->country_id]));
        }, $stadiums);
        $totalCount = 0;
        if($page == 1)
        {
            $totalCount = $this->stadiumService->getTotalCount();
        }
        $paginatedResponse = new PaginatedResponse($totalCount, $stadiumResponses, $page, $limit);
        return $this->ok($paginatedResponse);
    }
}