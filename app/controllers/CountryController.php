<?php

namespace app\controllers;

use app\requests\countries\CreateRequest;
use app\responses\CountryResponse;
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

        return Response::withData(CountryResponse::from_country($country));
    }

    public function searchByName($name)
    {
        $countries = $this->country_service->searchByName($name);
//        var_dump($countries);die;
        return Response::withData(array_map(function ($country) {
//            var_dump($country);die;
            return CountryResponse::from_country($country);
        }, $countries));
    }
}