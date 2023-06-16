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
}