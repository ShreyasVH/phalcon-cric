<?php

namespace app\controllers;

use app\exceptions\NotFoundException;
use app\models\Country;
use app\models\Team;
use app\models\TeamType;
use app\requests\teams\CreateRequest;
use app\responses\CountryResponse;
use app\responses\TeamResponse;
use app\responses\TeamTypeResponse;
use app\responses\PaginatedResponse;
use app\services\CountryService;
use app\services\TeamService;
use app\services\TeamTypeService;

class TeamController extends BaseController
{
    protected TeamService $teamService;
    protected CountryService $countryService;
    protected TeamTypeService $teamTypeService;

    public function onConstruct()
    {
        $this->teamService = new TeamService();
        $this->countryService = new CountryService();
        $this->teamTypeService = new TeamTypeService();
    }

    public function create()
    {
        $createRequest = CreateRequest::fromPostRequest($this->request->getJsonRawBody(true));

        $country = $this->countryService->getById($createRequest->countryId);
        if(null == $country)
        {
            throw new NotFoundException('Country');
        }

        $teamType = $this->teamTypeService->getById($createRequest->typeId);
        if(null == $teamType)
        {
            throw new NotFoundException('Team type');
        }

        $team = $this->teamService->create($createRequest);

        return $this->created(TeamResponse::withTeamAndCountryAndType($team, CountryResponse::from_country($country), TeamTypeResponse::from_team_type($teamType)));
    }
}