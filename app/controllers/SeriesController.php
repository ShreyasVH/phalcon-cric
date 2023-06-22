<?php

namespace app\controllers;

use app\exceptions\BadRequestException;
use app\exceptions\ConflictException;
use app\exceptions\NotFoundException;
use app\models\Country;
use app\models\Team;
use app\models\TeamType;
use app\models\SeriesType;
use app\models\GameType;
use app\models\Series;
use app\models\Tour;
use app\requests\series\CreateRequest;
use app\responses\CountryResponse;
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
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

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
    protected TransactionManager $transaction_manager;

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
        $this->transaction_manager = new TransactionManager();
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

//        $transaction = $this->transaction_manager->get();
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
}