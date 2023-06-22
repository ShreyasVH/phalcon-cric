<?php

namespace app\controllers;

use app\exceptions\BadRequestException;
use app\exceptions\ConflictException;
use app\exceptions\NotFoundException;
use app\models\Country;
use app\models\Series;
use app\models\SeriesTeamsMap;
use app\models\Team;
use app\models\TeamType;
use app\models\SeriesType;
use app\models\GameType;
use app\models\Tour;
use app\requests\series\CreateRequest;
use app\responses\CountryResponse;
use app\responses\PaginatedResponse;
use app\responses\TeamResponse;
use app\responses\TeamTypeResponse;
use app\responses\GameTypeResponse;
use app\responses\SeriesTypeResponse;
use app\responses\SeriesResponse;
use app\responses\TourResponse;
use app\services\CountryService;
use app\services\GameTypeService;
use app\services\SeriesService;
use app\services\SeriesTeamsMapService;
use app\services\SeriesTypeService;
use app\services\TeamService;
use app\services\TeamTypeService;
use app\services\TourService;
use Exception;

class SeriesController extends BaseController
{
    protected SeriesService $series_service;
    protected CountryService $country_service;
    protected SeriesTypeService $series_type_service;
    protected GameTypeService $game_type_service;
    protected TourService $tour_service;
    protected TeamService $team_service;
    protected TeamTypeService $team_type_service;
    protected SeriesTeamsMapService $series_teams_map_service;

    public function onConstruct()
    {
        $this->series_service = new SeriesService();
        $this->country_service = new CountryService();
        $this->series_type_service = new SeriesTypeService();
        $this->game_type_service = new GameTypeService();
        $this->tour_service = new TourService();
        $this->team_service = new TeamService();
        $this->team_type_service = new TeamTypeService();
        $this->series_teams_map_service = new SeriesTeamsMapService();
    }

    /**
     * @throws ConflictException
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public function create()
    {
        $create_request = CreateRequest::fromPostRequest($this->request->getJsonRawBody(true));

        $teams = $this->team_service->get_by_ids($create_request->teams);
        if(count($teams) != count(array_unique($create_request->teams)))
        {
            throw new NotFoundException('Team');
        }

        $team_type_ids = [];
        $country_ids = [];
        /** @var Team[] $teams */
        foreach($teams as $team)
        {
            $team_type_ids[] = $team->id;
            $country_ids[] = $team->country_id;
        }

        $country_ids[] = $create_request->homeCountryId;
        $countries = $this->country_service->getByIds($country_ids);
        $country_map = array_combine(array_map(function (Country $country) {
            return $country->id;
        }, $countries), $countries);

        $country = $country_map[$create_request->homeCountryId];
        if(null == $country)
        {
            throw new NotFoundException('Country');
        }

        /** @var Tour $tour */
        $tour = $this->tour_service->get_by_id($create_request->tourId);
        if(null == $tour)
        {
            throw new NotFoundException('Tour');
        }

        /** @var SeriesType $series_type */
        $series_type = $this->series_type_service->getById($create_request->typeId);
        if(null == $series_type)
        {
            throw new NotFoundException('Type');
        }

        /** @var GameType $game_type */
        $game_type = $this->game_type_service->getById($create_request->gameTypeId);
        if(null == $game_type)
        {
            throw new NotFoundException('Game type');
        }

        try
        {
            $this->db->begin();
            $series = $this->series_service->create($create_request);
            $this->series_teams_map_service->add($series->id, $create_request->teams);

            $this->db->commit();
        }
        catch (Exception $ex)
        {
            $this->db->rollback();
            throw $ex;
        }

        $team_types = $this->team_type_service->getByIds($team_type_ids);
        $team_type_map = array_combine(array_map(function (TeamType $team_type) {
            return $team_type->id;
        }, $team_types), $team_types);
        $team_responses = array_map(function (Team $team) use ($country_map, $team_type_map){
            return TeamResponse::withTeamAndCountryAndType($team, CountryResponse::from_country($country_map[$team->country_id]), TeamTypeResponse::from_team_type($team_type_map[$team->type_id]));
        }, $teams);

        return $this->created(SeriesResponse::withAllData($series, CountryResponse::from_country($country_map[$series->home_country_id]), TourResponse::from_tour($tour), SeriesTypeResponse::from_series_type($series_type), GameTypeResponse::from_game_type($game_type), $team_responses));
    }

    public function get_all()
    {
        $page = $this->request->getQuery('page', 'int', 1);
        $limit = $this->request->getQuery('limit', 'int', 25);
        /** @var Series[] $series_list */
        $series_list = $this->series_service->getAll($page, $limit);
        $total_count = 0;
        if($page == 1)
        {
            $total_count = $this->series_service->getTotalCount();
        }

        $country_ids = [];
        $series_type_ids = [];
        $game_type_ids = [];
        $tour_ids = [];
        $series_ids = [];

        foreach($series_list as $series)
        {
            $country_ids[] = $series->home_country_id;
            $series_type_ids[] = $series->type_id;
            $game_type_ids[] = $series->game_type_id;
            $tour_ids[] = $series->tour_id;
            $series_ids[] = $series->id;
        }

        /** @var SeriesType[] $series_types */
        $series_types = $this->series_type_service->get_by_ids($series_type_ids);
        $series_type_map = array_combine(array_map(function (SeriesType $series_type) {
            return $series_type->id;
        }, $series_types), $series_types);

        /** @var GameType[] $game_types */
        $game_types = $this->game_type_service->get_by_ids($game_type_ids);
        $game_type_map = array_combine(array_map(function (GameType $game_type) {
            return $game_type->id;
        }, $game_types), $game_types);

        $series_teams_maps = $this->series_teams_map_service->get_by_series_ids($series_ids);
        $team_ids = array_map(function (SeriesTeamsMap $series_teams_map) {
            return $series_teams_map->team_id;
        }, $series_teams_maps);

        /** @var Team[] $teams */
        $teams = $this->team_service->get_by_ids($team_ids);

        $team_type_ids = [];
        foreach($teams as $team)
        {
            $team_type_ids[] = $team->type_id;
            $country_ids[] = $team->country_id;
        }

        /** @var Country[] $countries */
        $countries = $this->country_service->getByIds($country_ids);
        $country_map = array_combine(array_map(function(Country $country) {
            return $country->id;
        }, $countries), $countries);

        /** @var TeamType[] $team_types */
        $team_types = $this->team_type_service->getByIds($team_type_ids);
        $team_type_map = array_combine(array_map(function(TeamType $team_type) {
            return $team_type->id;
        }, $team_types), $team_types);

        /** @var Tour[] $tours */
        $tours = $this->tour_service->get_by_ids($tour_ids);
        $tour_map = array_combine(array_map(function(Tour $tour) {
            return $tour->id;
        }, $tours), $tours);

        $team_responses = array_map(function(Team $team) use ($country_map, $team_type_map) {
            return TeamResponse::withTeamAndCountryAndType($team, CountryResponse::from_country($country_map[$team->country_id]), TeamTypeResponse::from_team_type($team_type_map[$team->type_id]));
        }, $teams);

        $series_responses = array_map(function (Series $series) use ($country_map, $tour_map, $series_type_map, $game_type_map, $team_responses) {
            return SeriesResponse::withAllData($series, CountryResponse::from_country($country_map[$series->home_country_id]), TourResponse::from_tour($tour_map[$series->tour_id]), SeriesTypeResponse::from_series_type($series_type_map[$series->type_id]), GameTypeResponse::from_game_type($game_type_map[$series->game_type_id]), $team_responses);
        }, $series_list);

        $paginatedResponse = new PaginatedResponse($total_count, $series_responses, $page, $limit);
        return $this->ok($paginatedResponse);
    }
}