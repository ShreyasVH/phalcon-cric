<?php

namespace app\controllers;

use app\exceptions\NotFoundException;
use app\models\Country;
use app\models\Player;
use app\requests\players\CreateRequest;
use app\responses\BattingStats;
use app\responses\BowlingStats;
use app\responses\CountryResponse;
use app\responses\FieldingStats;
use app\responses\PlayerMiniResponse;
use app\responses\PaginatedResponse;
use app\responses\PlayerResponse;
use app\services\BattingScoreService;
use app\services\BowlingFigureService;
use app\services\CountryService;
use app\services\FielderDismissalService;
use app\services\PlayerService;

class PlayerController extends BaseController
{
    protected PlayerService $playerService;
    protected CountryService $countryService;
    protected BattingScoreService $_batting_score_service;
    protected BowlingFigureService $_bowling_figure_service;
    protected FielderDismissalService $_fielder_dismissal_service;

    public function onConstruct()
    {
        $this->playerService = new PlayerService();
        $this->countryService = new CountryService();
        $this->_batting_score_service = new BattingScoreService();
        $this->_bowling_figure_service = new BowlingFigureService();
        $this->_fielder_dismissal_service = new FielderDismissalService();
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

        return $this->created(PlayerMiniResponse::withPlayerAndCountry($player, CountryResponse::from_country($country)));
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
            return PlayerMiniResponse::withPlayerAndCountry($player, CountryResponse::from_country($countryMap[$player->country_id]));
        }, $players);
        $totalCount = 0;
        if($page == 1)
        {
            $totalCount = $this->playerService->getTotalCount();
        }
        $paginatedResponse = new PaginatedResponse($totalCount, $playerResponses, $page, $limit);
        return $this->ok($paginatedResponse);
    }

    public function get_by_id(int $id)
    {
        /** @var Player $player */
        $player = $this->playerService->get_by_id($id);
        if(null == $player)
        {
            throw new NotFoundException('Player');
        }

        $player_response = PlayerResponse::withPlayer($player);
        /** @var Country $country */
        $country = $this->countryService->getById($player->country_id);
        $player_response->country = CountryResponse::from_country($country);

        $dismissal_stats = $this->_batting_score_service->get_dismissal_stats($id);
        $player_response->dismissalStats = $dismissal_stats;

        $dismissal_count_map = [];
        foreach($dismissal_stats as $game_type => $game_type_dismissal_stats)
        {
            $dismissal_count = 0;

            foreach($game_type_dismissal_stats as $dismissal_mode => $count)
            {
                $dismissal_count += $count;
            }

            $dismissal_count_map[$game_type] = $dismissal_count;
        }

        $basic_batting_stats = $this->_batting_score_service->get_batting_stats($id);
        if(!empty($basic_batting_stats))
        {
            $batting_stats_map = [];

            foreach($basic_batting_stats as $game_type => $batting_stats)
            {
                $batting_stats = new BattingStats($batting_stats);

                if(($dismissal_count_map[$game_type] ?? 0) > 0)
                {
                    $batting_stats->average = $batting_stats->runs / $dismissal_count_map[$game_type];
                }

                if($batting_stats->balls > 0)
                {
                    $batting_stats->strikeRate = $batting_stats->runs * 100 / $batting_stats->balls;
                }

                $batting_stats_map[$game_type] = $batting_stats;
            }

            $player_response->battingStats = $batting_stats_map;
        }

        $basic_bowling_stats = $this->_bowling_figure_service->get_bowling_stats($id);
        if(!empty($basic_bowling_stats))
        {
            $bowling_stats_final = [];

            foreach($basic_bowling_stats as $game_type => $game_type_bowling_stats)
            {
                $bowling_stats = new BowlingStats($game_type_bowling_stats);

                if($bowling_stats->balls > 0)
                {
                    $bowling_stats->economy = $bowling_stats->runs * 6 / $bowling_stats->balls;

                    if($bowling_stats->wickets > 0)
                    {
                        $bowling_stats->average = $bowling_stats->runs / $bowling_stats->wickets;
                        $bowling_stats->strikeRate = $bowling_stats->balls / $bowling_stats->wickets;
                    }
                }

                $bowling_stats_final[$game_type] = $bowling_stats;
            }

            $player_response->bowlingStats = $bowling_stats_final;
        }

        $fielding_stats_map = $this->_fielder_dismissal_service->get_fielding_stats($id);
        if(!empty($fielding_stats_map))
        {
            $fielding_stats_map_final = [];
            foreach($fielding_stats_map as $game_type => $fielding_stats)
            {
                $fielding_stats_map_final[$game_type] = new FieldingStats($fielding_stats);
            }

            $player_response->fieldingStats = $fielding_stats_map_final;
        }

        return $this->ok($player_response);
    }
}