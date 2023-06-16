<?php

namespace app\controllers;

use app\exceptions\NotFoundException;
use app\models\Country;
use app\models\Player;
use app\requests\players\CreateRequest;
use app\responses\CountryResponse;
use app\responses\PlayerResponse;
use app\responses\PaginatedResponse;
use app\services\CountryService;
use app\services\PlayerService;

class PlayerController extends BaseController
{
    protected PlayerService $playerService;
    protected CountryService $countryService;

    public function onConstruct()
    {
        $this->playerService = new PlayerService();
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

        $player = $this->playerService->create($createRequest);

        return $this->created(PlayerResponse::withPlayerAndCountry($player, CountryResponse::from_country($country)));
    }

    public function getAll()
    {
        $page = $this->request->getQuery('page', 'int', 1);
        $limit = $this->request->getQuery('limit', 'int', 25);
        $players = $this->playerService->getAll($page, $limit);
        $countryIds = array_map(function (Player $player) {
            return $player->country_id;
        }, $players);
        $countries = $this->countryService->getByIds($countryIds);
        $countryMap = array_combine(array_map(function (Country $country) {
            return $country->id;
        }, $countries), $countries);

        $playerResponses = array_map(function (Player $player) use ($countryMap) {
            return PlayerResponse::withPlayerAndCountry($player, CountryResponse::from_country($countryMap[$player->country_id]));
        }, $players);
        $totalCount = 0;
        if($page == 1)
        {
            $totalCount = $this->playerService->getTotalCount();
        }
        $paginatedResponse = new PaginatedResponse($totalCount, $playerResponses, $page, $limit);
        return $this->ok($paginatedResponse);
    }
}