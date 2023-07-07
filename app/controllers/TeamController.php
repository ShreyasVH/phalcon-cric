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

    public function getAll()
    {
        $page = $this->request->getQuery('page', 'int', 1);
        $limit = $this->request->getQuery('limit', 'int', 25);
        $teams = $this->teamService->getAll($page, $limit);
        $countryIds = array_map(function (Team $team) {
            return $team->country_id;
        }, $teams);
        $countries = $this->countryService->getByIds($countryIds);
        $countryMap = array_combine(array_map(function (Country $country) {
            return $country->id;
        }, $countries), $countries);


        $typeIds = array_map(function (Team $team) {
            return $team->type_id;
        }, $teams);
        $types = $this->teamTypeService->getByIds($typeIds);
        $typeMap = array_combine(array_map(function (TeamType $teamType) {
            return $teamType->id;
        }, $types), $types);


        $teamResponses = array_map(function (Team $team) use ($countryMap, $typeMap) {
            return TeamResponse::withTeamAndCountryAndType($team, CountryResponse::from_country($countryMap[$team->country_id]), TeamTypeResponse::from_team_type($typeMap[$team->type_id]));
        }, $teams);
        $totalCount = 0;
        if($page == 1)
        {
            $totalCount = $this->teamService->getTotalCount();
        }
        $paginatedResponse = new PaginatedResponse($totalCount, $teamResponses, $page, $limit);
        return $this->ok($paginatedResponse);
    }
}