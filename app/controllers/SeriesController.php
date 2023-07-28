<?php

namespace app\controllers;

use app\exceptions\BadRequestException;
use app\exceptions\ConflictException;
use app\exceptions\NotFoundException;
use app\models\Country;
use app\models\ManOfTheSeries;
use app\models\Player;
use app\models\Series;
use app\models\SeriesTeamsMap;
use app\models\Team;
use app\models\TeamType;
use app\models\SeriesType;
use app\models\GameType;
use app\models\Tour;
use app\requests\series\CreateRequest;
use app\requests\series\UpdateRequest;
use app\responses\CountryResponse;
use app\responses\PaginatedResponse;
use app\responses\PlayerMiniResponse;
use app\responses\TeamResponse;
use app\responses\TeamTypeResponse;
use app\responses\GameTypeResponse;
use app\responses\SeriesTypeResponse;
use app\responses\SeriesResponse;
use app\responses\TourMiniResponse;
use app\services\CountryService;
use app\services\GameTypeService;
use app\services\ManOfTheSeriesService;
use app\services\PlayerService;
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
    protected ManOfTheSeriesService $man_of_the_series_service;
    protected PlayerService $player_service;

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
        $this->man_of_the_series_service = new ManOfTheSeriesService();
        $this->player_service = new PlayerService();
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
            $team_type_ids[] = $team->type_id;
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

        return $this->created(SeriesResponse::withAllData($series, CountryResponse::from_country($country_map[$series->home_country_id]), TourMiniResponse::from_tour($tour), SeriesTypeResponse::from_series_type($series_type), GameTypeResponse::from_game_type($game_type), $team_responses, []));
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

        /** @var ManOfTheSeries[] $man_of_the_series_list */
        $man_of_the_series_list = $this->man_of_the_series_service->get_by_series_ids($series_ids);
        $player_ids = array_map(function(ManOfTheSeries $man_of_the_series) {
            return $man_of_the_series->player_id;
        }, $man_of_the_series_list);
        /** @var Player[] $players */
        $players = $this->player_service->get_by_ids($player_ids);
        $player_country_ids = array_map(function(Player $player) {
            return $player->country_id;
        }, $players);
        $country_ids = array_merge($country_ids, $player_country_ids);

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

        $player_responses = array_map(function(Player $player) use ($country_map) {
            return PlayerMiniResponse::withPlayerAndCountry($player, CountryResponse::from_country($country_map[$player->country_id]));
        }, $players);

        $series_responses = array_map(function (Series $series) use ($country_map, $tour_map, $series_type_map, $game_type_map, $team_responses, $player_responses) {
            return SeriesResponse::withAllData($series, CountryResponse::from_country($country_map[$series->home_country_id]), TourMiniResponse::from_tour($tour_map[$series->tour_id]), SeriesTypeResponse::from_series_type($series_type_map[$series->type_id]), GameTypeResponse::from_game_type($game_type_map[$series->game_type_id]), $team_responses, $player_responses);
        }, $series_list);

        $paginatedResponse = new PaginatedResponse($total_count, $series_responses, $page, $limit);
        return $this->ok($paginatedResponse);
    }

    /**
     * @throws NotFoundException
     * @throws Exception
     */
    public function update(int $id)
    {
        $update_request = UpdateRequest::fromPostRequest($this->request->getJsonRawBody(true));

        /** @var Series $existing_series */
        $existing_series = $this->series_service->get_by_id($id);
        if(null == $existing_series)
        {
            throw new NotFoundException('Series');
        }

        $teams_to_delete = [];
        $teams_to_add = [];
        $man_of_the_series_to_delete = [];
        $man_of_the_series_to_add = [];
        /** @var SeriesTeamsMap[] $series_teams_maps */
        $series_teams_maps = $this->series_teams_map_service->get_by_series_ids([$id]);
        $existing_team_ids = [];
        foreach($series_teams_maps as $series_teams_map)
        {
            $existing_team_ids[] = $series_teams_map->team_id;
            if(null != $update_request->teams && !in_array($series_teams_map->team_id, $update_request->teams))
            {
                $teams_to_delete[] = $series_teams_map->team_id;
            }
        }

        /** @var Team[] $teams */
        if(null != $update_request->teams)
        {
            $teams = $this->team_service->get_by_ids($update_request->teams);
            if(count($teams) != count(array_unique($update_request->teams)))
            {
                throw new NotFoundException('Team');
            }

            $teams_to_add = array_filter($update_request->teams, function(int $team_id) use ($existing_team_ids) {
                return !in_array($team_id, $existing_team_ids);
            });
        }
        else
        {
            $teams = $this->team_service->get_by_ids($existing_team_ids);
        }

        $team_type_ids = [];
        $country_ids = [];
        foreach($teams as $team)
        {
            $team_type_ids[] = $team->type_id;
            $country_ids[] = $team->country_id;
        }

        if(null != $update_request->homeCountryId)
        {
            $country_ids[] = $update_request->homeCountryId;
        }
        else
        {
            $country_ids[] = $existing_series->home_country_id;
        }

        /** @var ManOfTheSeries[] $man_of_the_series_list */
        $man_of_the_series_list = $this->man_of_the_series_service->get_by_series_ids([$id]);
        $existing_player_ids = [];
        foreach($man_of_the_series_list as $man_of_the_series)
        {
            $existing_player_ids[] = $man_of_the_series->player_id;
            if(null != $update_request->manOfTheSeriesList && !in_array($man_of_the_series->player_id, $update_request->manOfTheSeriesList))
            {
                $man_of_the_series_to_delete[] = $man_of_the_series->player_id;
            }
        }

        if(null != $update_request->manOfTheSeriesList)
        {
            $players = $this->player_service->get_by_ids($update_request->manOfTheSeriesList);
            if(count($players) != count(array_unique($update_request->manOfTheSeriesList)))
            {
                throw new NotFoundException('Player');
            }

            $man_of_the_series_to_add = array_filter($update_request->manOfTheSeriesList, function(int $player_id) use ($existing_player_ids) {
                return !in_array($player_id, $existing_player_ids);
            });
        }
        else
        {
            $players = $this->player_service->get_by_ids($existing_player_ids);
        }

        $player_country_ids = array_map(function(Player $player) {
            return $player->country_id;
        }, $players);
        $country_ids = array_merge($country_ids, $player_country_ids);

        $countries = $this->country_service->getByIds($country_ids);
        $country_map = array_combine(array_map(function(Country $country) {
            return $country->id;
        }, $countries), $countries);

        if(null != $update_request->homeCountryId)
        {
            $home_country_id = $update_request->homeCountryId;
        }
        else
        {
            $home_country_id = $existing_series->home_country_id;
        }
        $country = $country_map[$home_country_id];
        if(empty($country))
        {
            throw new NotFoundException('Home country');
        }

        if(null != $update_request->tourId)
        {
            $tour_id = $update_request->tourId;
        }
        else
        {
            $tour_id = $existing_series->tour_id;
        }
        /** @var Tour $tour */
        $tour = $this->tour_service->get_by_id($tour_id);
        if(empty($tour))
        {
            throw new NotFoundException('Tour');
        }

        if(null != $update_request->typeId)
        {
            $series_type_id = $update_request->typeId;
        }
        else
        {
            $series_type_id = $existing_series->type_id;
        }
        /** @var SeriesType $series_type */
        $series_type = $this->series_type_service->getById($series_type_id);
        if(empty($series_type))
        {
            throw new NotFoundException('Type');
        }

        if(null != $update_request->gameTypeId)
        {
            $game_type_id = $update_request->gameTypeId;
        }
        else
        {
            $game_type_id = $existing_series->game_type_id;
        }
        /** @var GameType $game_type */
        $game_type = $this->game_type_service->getById($game_type_id);
        if(empty($game_type))
        {
            throw new NotFoundException('Game type');
        }

        try
        {
            $this->db->begin();

            $this->series_service->update($existing_series, $update_request);
            $this->series_teams_map_service->add($id, $teams_to_add);
            $this->series_teams_map_service->remove($id, $teams_to_delete);
            $this->man_of_the_series_service->add($id, $man_of_the_series_to_add);
            $this->man_of_the_series_service->remove($id, $man_of_the_series_to_delete);

            $this->db->commit();
        }
        catch(Exception $ex)
        {
            $this->db->rollback();
            throw $ex;
        }

        $team_types = $this->team_type_service->getByIds($team_type_ids);
        $team_type_map = array_combine(array_map(function(TeamType $team_type) {
            return $team_type->id;
        }, $team_types), $team_types);

        $team_responses = array_map(function(Team $team) use($country_map, $team_type_map) {
            return TeamResponse::withTeamAndCountryAndType($team, CountryResponse::from_country($country_map[$team->country_id]), TeamTypeResponse::from_team_type($team_type_map[$team->type_id]));
        }, $teams);

        $player_responses = array_map(function(Player $player) use ($country_map) {
            return PlayerMiniResponse::withPlayerAndCountry($player, CountryResponse::from_country($country_map[$player->country_id]));
        }, $players);

        return $this->ok(SeriesResponse::withAllData($existing_series, CountryResponse::from_country($country), TourMiniResponse::from_tour($tour), SeriesTypeResponse::from_series_type($series_type), GameTypeResponse::from_game_type($game_type), $team_responses, $player_responses));
    }
}