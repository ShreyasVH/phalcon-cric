<?php

namespace app\controllers;

use app\exceptions\NotFoundException;
use app\requests\stadiums\CreateRequest;
use app\responses\CountryResponse;
use app\responses\StadiumResponse;
use app\responses\PaginatedResponse;
use app\responses\Response;
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
}