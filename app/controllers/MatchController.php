<?php


namespace app\controllers;


use app\exceptions\NotFoundException;
use app\models\BattingScore;
use app\models\BowlingFigure;
use app\models\Captain;
use app\models\Country;
use app\models\DismissalMode;
use app\models\Extras;
use app\models\ExtrasType;
use app\models\FielderDismissal;
use app\models\Game;
use app\models\GameType;
use app\models\ManOfTheMatch;
use app\models\MatchPlayerMap;
use app\models\Player;
use app\models\ResultType;
use app\models\Series;
use app\models\Stadium;
use app\models\Team;
use app\models\TeamType;
use app\models\WicketKeeper;
use app\models\WinMarginType;
use app\models\Total;
use app\requests\matches\BowlingFigureRequest;
use app\requests\matches\CreateRequest;
use app\requests\matches\PlayerRequest;
use app\requests\matches\TotalRequestEntry;
use app\responses\BattingScoreResponse;
use app\responses\BowlingFigureResponse;
use app\responses\CountryResponse;
use app\responses\DismissalModeResponse;
use app\responses\ExtrasResponse;
use app\responses\ExtrasTypeResponse;
use app\responses\MatchResponse;
use app\responses\PlayerMiniResponse;
use app\responses\ResultTypeResponse;
use app\responses\StadiumResponse;
use app\responses\TeamResponse;
use app\responses\TeamTypeResponse;
use app\responses\WinMarginTypeResponse;
use app\services\BattingScoreService;
use app\services\BowlingFigureService;
use app\services\CaptainService;
use app\services\CountryService;
use app\services\DismissalModeService;
use app\services\ExtrasService;
use app\services\ExtrasTypeService;
use app\services\FielderDismissalService;
use app\services\GameTypeService;
use app\services\ManOfTheMatchService;
use app\services\MatchPlayerMapService;
use app\services\MatchService;
use app\services\PlayerService;
use app\services\ResultTypeService;
use app\services\SeriesService;
use app\services\StadiumService;
use app\services\TeamService;
use app\services\TeamTypeService;
use app\services\TotalsService;
use app\services\TourService;
use app\services\WicketKeeperService;
use app\services\WinMarginTypeService;
use Exception;

class MatchController extends BaseController
{
    protected MatchService $match_service;
    protected SeriesService $series_service;
    protected TourService $tour_service;
    protected TeamService $team_service;
    protected ResultTypeService $result_type_service;
    protected WinMarginTypeService $win_margin_type_service;
    protected StadiumService $stadium_service;
    protected CountryService $country_service;
    protected TeamTypeService $team_type_service;
    protected PlayerService $player_service;
    protected MatchPlayerMapService $match_player_map_service;
    protected BattingScoreService $batting_score_service;
    protected DismissalModeService $dismissal_mode_service;
    protected FielderDismissalService $fielder_dismissal_service;
    protected BowlingFigureService $bowling_figure_service;
    protected ExtrasTypeService $extras_type_service;
    protected ExtrasService $extras_service;
    protected ManOfTheMatchService $man_of_the_match_service;
    protected CaptainService $captain_service;
    protected WicketKeeperService $wicket_keeper_service;
    protected GameTypeService $game_type_service;
    protected TotalsService $totals_service;

    public function onConstruct()
    {
        $this->match_service = new MatchService();
        $this->series_service = new SeriesService();
        $this->tour_service = new TourService();
        $this->team_service = new TeamService();
        $this->result_type_service = new ResultTypeService();
        $this->win_margin_type_service = new WinMarginTypeService();
        $this->stadium_service = new StadiumService();
        $this->country_service = new CountryService();
        $this->team_type_service = new TeamTypeService();
        $this->player_service = new PlayerService();
        $this->match_player_map_service = new MatchPlayerMapService();
        $this->batting_score_service = new BattingScoreService();
        $this->dismissal_mode_service = new DismissalModeService();
        $this->fielder_dismissal_service = new FielderDismissalService();
        $this->bowling_figure_service = new BowlingFigureService();
        $this->extras_type_service = new ExtrasTypeService();
        $this->extras_service = new ExtrasService();
        $this->man_of_the_match_service = new ManOfTheMatchService();
        $this->captain_service = new CaptainService();
        $this->wicket_keeper_service = new WicketKeeperService();
        $this->game_type_service = new GameTypeService();
        $this->totals_service = new TotalsService();
    }

    /**
     * @throws \app\exceptions\ConflictException
     * @throws NotFoundException
     */
    public function create()
    {
        $create_request = new CreateRequest($this->request->getJsonRawBody(true));
//        echo json_encode($create_request, JSON_PRETTY_PRINT);die;

        /** @var Series $series */
        $series = $this->series_service->get_by_id($create_request->seriesId);
        if(null == $series)
        {
            throw new NotFoundException('Series');
        }

        /** @var GameType $game_type */
        $game_type = $this->game_type_service->getById($series->game_type_id);

        $country_ids = [
            $series->home_country_id
        ];

        $team_ids = [
            $create_request->team1Id,
            $create_request->team2Id
        ];

        /** @var Team[] $teams */
        $teams = $this->team_service->get_by_ids($team_ids);
        $team_map = [];
        foreach($teams as $team)
        {
            $team_map[$team->id] = $team;
            $country_ids[] = $team->country_id;
        }

        $team1 = $team_map[$create_request->team1Id];
        $team2 = $team_map[$create_request->team2Id];

        /** @var ResultType $result_type */
        $result_type = $this->result_type_service->getById($create_request->resultTypeId);
        if($result_type == null)
        {
            throw new NotFoundException('Result type');
        }

        /** @var WinMarginTypeResponse $win_margin_type_response */
        $win_margin_type_response = null;
        if(null != $create_request->winMarginTypeId)
        {
            /** @var WinMarginType $win_margin_type */
            $win_margin_type = $this->win_margin_type_service->getById($create_request->winMarginTypeId);
            if(null == $win_margin_type)
            {
                throw new NotFoundException('Win margin type');
            }
            $win_margin_type_response = WinMarginTypeResponse::from_win_margin_type($win_margin_type);
        }

        /** @var Stadium $stadium */
        $stadium = $this->stadium_service->get_by_id($create_request->stadiumId);
        if($stadium == null)
        {
            throw new NotFoundException('Stadium');
        }

        $player_team_map = [];
        $all_player_ids = [];
        /** @var PlayerRequest $player */
        foreach($create_request->players as $player)
        {
            $player_team_map[$player->id] = $player->teamId;
            $all_player_ids[] = $player->id;
        }

        /** @var PlayerRequest $player */
        foreach($create_request->bench as $player)
        {
            $player_team_map[$player->id] = $player->teamId;
            $all_player_ids[] = $player->id;
        }

        /** @var Player[] $all_players */
        $all_players = $this->player_service->get_by_ids($all_player_ids);
        $player_country_ids = array_map(function (Player $player) {
            return $player->country_id;
        }, $all_players);
        $player_map = array_combine(array_map(function (Player $player) {
            return $player->id;
        }, $all_players), $all_players);

        $country_ids[] = $team1->country_id;
        $country_ids[] = $team2->country_id;
        $country_ids[] = $stadium->country_id;
        $country_ids = array_merge($country_ids, $player_country_ids);
        $team_type_ids = [
            $team1->type_id,
            $team2->type_id
        ];
        $team_types = $this->team_type_service->getByIds($team_type_ids);
        $team_type_map = array_combine(array_map( function(TeamType $team_type) {
            return $team_type->id;
        }, $team_types), $team_types);

        $countries = $this->country_service->getByIds($country_ids);
        $country_map = array_combine(array_map(function(Country $country) {
            return $country->id;
        }, $countries), $countries);

        try
        {
            $this->db->begin();

            $match = $this->match_service->create($create_request);
            $match_player_list = $this->match_player_map_service->add($match->id, $all_player_ids, $player_team_map);
            $player_to_match_player_map = array_combine(array_map(function(MatchPlayerMap $match_player_map) {
                return $match_player_map->player_id;
            }, $match_player_list), array_map(function (MatchPlayerMap $match_player_map) {
                return $match_player_map->id;
            }, $match_player_list));

            /** @var BattingScore[] $batting_scores */
            $batting_scores = $this->batting_score_service->add($create_request->battingScores, $player_to_match_player_map);

            /** @var DismissalMode[] $dismissal_modes */
            $dismissal_modes = $this->dismissal_mode_service->get_all();
            $dismissal_mode_map = array_combine(array_map(function (DismissalMode $dismissal_mode) {
                return $dismissal_mode->id;
            }, $dismissal_modes), $dismissal_modes);

            $batting_score_map = array_combine(array_map(function(BattingScore $batting_score) {
                return $batting_score->match_player_id . '_' . $batting_score->innings;
            }, $batting_scores), $batting_scores);

            $score_fielder_map = [];
            $batting_score_responses = [];
            foreach($create_request->battingScores as $batting_score)
            {
                $key = $player_to_match_player_map[$batting_score->playerId] . '_' . $batting_score->innings;
                $batting_score_from_db = $batting_score_map[$key];

                $dismissal_mode_response = null;
                $fielders = [];
                $bowler = null;
                if(null != $batting_score->dismissalModeId)
                {
                    $dismissal_mode_response = new DismissalModeResponse($dismissal_mode_map[$batting_score->dismissalModeId]);

                    if(null != $batting_score->bowlerId)
                    {
                        $bowler_player = $player_map[$batting_score->bowlerId];
                        $bowler = PlayerMiniResponse::withPlayerAndCountry($bowler_player, CountryResponse::from_country($country_map[$bowler_player->country_id]));
                    }

                    if(null != $batting_score->fielderIds)
                    {
                        $fielders = array_map(function (int $fielder_id) use ($player_map, $country_map) {
                            $fielder_player = $player_map[$fielder_id];
                            return PlayerMiniResponse::withPlayerAndCountry($fielder_player, CountryResponse::from_country($country_map[$fielder_player->country_id]));
                        }, $batting_score->fielderIds);

                        $score_fielder_map[$batting_score_from_db->id] = $batting_score->fielderIds;
                    }
                }

                $batsman_player = $player_map[$batting_score->playerId];

                $batting_score_responses[] = new BattingScoreResponse(
                    $batting_score_from_db,
                    PlayerMiniResponse::withPlayerAndCountry($batsman_player, CountryResponse::from_country($country_map[$batsman_player->country_id])),
                    $dismissal_mode_response,
                    $bowler,
                    $fielders
                );
            }

            $this->fielder_dismissal_service->add($score_fielder_map, $player_to_match_player_map);

            $bowling_figures = $this->bowling_figure_service->add($create_request->bowlingFigures, $player_to_match_player_map);
            $bowling_figure_map = array_combine(array_map(function(BowlingFigure $bowling_figure) {
                return $bowling_figure->match_player_id . '_' . $bowling_figure->innings;
            }, $bowling_figures), $bowling_figures);

            $bowling_figure_responses = [];
            /** @var BowlingFigureRequest $bowling_figure */
            foreach($create_request->bowlingFigures as $bowling_figure_request)
            {
                $key = $player_to_match_player_map[$bowling_figure_request->playerId] . '_' . $bowling_figure_request->innings;
                $bowling_figure = $bowling_figure_map[$key];

                $bowler_player = $player_map[$bowling_figure_request->playerId];
                $bowling_figure_responses[] = new BowlingFigureResponse($bowling_figure, PlayerMiniResponse::withPlayerAndCountry($bowler_player, CountryResponse::from_country($country_map[$bowler_player->country_id])));
            }

            $extras_types = $this->extras_type_service->get_all();
            $extras_type_map = array_combine(array_map(function (ExtrasType $extras_type) {
                return $extras_type->id;
            }, $extras_types), $extras_types);

            $extras_list = $this->extras_service->add($match->id, $create_request->extras);
            $extras_responses = [];
            /** @var Extras $extras */
            foreach($extras_list as $extras)
            {
                $batting_team = $team_map[$extras->batting_team_id];
                $bowling_team = $team_map[$extras->bowling_team_id];

                $extras_responses[] = new ExtrasResponse(
                    $extras,
                    new ExtrasTypeResponse($extras_type_map[$extras->type_id]),
                    TeamResponse::withTeamAndCountryAndType($batting_team, CountryResponse::from_country($country_map[$batting_team->country_id]), TeamTypeResponse::from_team_type($team_type_map[$batting_team->type_id])),
                    TeamResponse::withTeamAndCountryAndType($bowling_team, CountryResponse::from_country($country_map[$bowling_team->country_id]), TeamTypeResponse::from_team_type($team_type_map[$bowling_team->type_id]))
                );
            }

            $this->man_of_the_match_service->add($create_request->manOfTheMatchList, $player_to_match_player_map);
            $this->captain_service->add($create_request->captains, $player_to_match_player_map);
            $this->wicket_keeper_service->add($create_request->wicketKeepers, $player_to_match_player_map);
            $match_id = $match->id;
            $this->totals_service->add(array_map(function (array $total) use ($match_id) {
                return Total::from_total_request_entry($match_id, new TotalRequestEntry($total));
            }, $create_request->totals));

            $this->db->commit();
        }
        catch(Exception $ex)
        {
            $this->db->rollback();
            throw $ex;
        }

        $team_player_map = [];
        foreach($all_players as $player)
        {
            $player_mini_response = PlayerMiniResponse::withPlayerAndCountry($player, CountryResponse::from_country($country_map[$player->country_id]));
            $team_id = $player_team_map[$player->id];
            if(!array_key_exists($team_id, $team_player_map))
            {
                $team_player_map[$team_id] = [];
            }
            $team_player_map[$team_id][] = $player_mini_response;
        }


        $match_response = new MatchResponse(
            $match,
            $series,
            $game_type,
            TeamResponse::withTeamAndCountryAndType($team1, CountryResponse::from_country($country_map[$team1->country_id]), TeamTypeResponse::from_team_type($team_type_map[$team1->type_id])),
            TeamResponse::withTeamAndCountryAndType($team2, CountryResponse::from_country($country_map[$team2->country_id]), TeamTypeResponse::from_team_type($team_type_map[$team2->type_id])),
            ResultTypeResponse::from_result_type($result_type),
            $win_margin_type_response,
            StadiumResponse::withStadiumAndCountry($stadium, CountryResponse::from_country($country_map[$stadium->country_id])),
            $team_player_map,
            $batting_score_responses,
            $bowling_figure_responses,
            $extras_responses,
            $create_request->manOfTheMatchList,
            $create_request->captains,
            $create_request->wicketKeepers
        );

        return $this->created($match_response);
    }

    /**
     * @throws NotFoundException
     */
    public function get_by_id(int $id)
    {
        /** @var Game $match */
        $match = $this->match_service->get_by_id($id);
        if(null == $match)
        {
            throw new NotFoundException('Match');
        }

        /** @var Series $series */
        $series = $this->series_service->get_by_id($match->series_id);
        if(null == $series)
        {
            throw new NotFoundException('Series');
        }

        /** @var GameType $game_type */
        $game_type = $this->game_type_service->getById($series->game_type_id);

        $country_ids = [];

        $team_ids = [
            $match->team1_id,
            $match->team2_id
        ];
        $teams = $this->team_service->get_by_ids($team_ids);
        $team_map = [];
        /** @var Team $team */
        foreach($teams as $team)
        {
            $team_map[$team->id] = $team;
            $country_ids[] = $team->country_id;
        }

        $team1 = $team_map[$match->team1_id];
        if(null == $team1)
        {
            throw new NotFoundException("Team 1");
        }

        $team2 = $team_map[$match->team2_id];
        if(null == $team2)
        {
            throw new NotFoundException("Team 2");
        }

        /** @var ResultType $result_type */
        $result_type = $this->result_type_service->getById($match->result_type_id);
        if(null == $result_type)
        {
            throw new NotFoundException('Result type');
        }

        $win_margin_type_response = null;
        if(null != $match->win_margin_type_id)
        {
            /** @var WinMarginType $win_margin_type */
            $win_margin_type = $this->win_margin_type_service->getById($match->win_margin_type_id);
            if(null == $win_margin_type)
            {
                throw new NotFoundException('Win margin type');
            }
            $win_margin_type_response = WinMarginTypeResponse::from_win_margin_type($win_margin_type);
        }

        /** @var Stadium $stadium */
        $stadium = $this->stadium_service->get_by_id($match->stadium_id);
        if(null == $stadium)
        {
            throw new NotFoundException('Stadium');
        }

        $match_player_maps = $this->match_player_map_service->get_by_match_id($id);
        $player_ids = [];
        $match_player_to_player_map = [];
        $match_player_ids = [];
        $player_to_team_map = [];
        foreach($match_player_maps as $match_player_map)
        {
            $player_ids[] = $match_player_map->player_id;
            $match_player_to_player_map[$match_player_map->id] = $match_player_map->player_id;
            $match_player_ids[] = $match_player_map->id;
            $player_to_team_map[$match_player_map->player_id] = $match_player_map->team_id;
        }

        /** @var Player[] $players */
        $players = $this->player_service->get_by_ids($player_ids);
        $player_country_ids = array_map(function (Player $player) {
            return $player->country_id;
        }, $players);

        $country_ids[] = $stadium->country_id;
        $country_ids = array_merge($country_ids, $player_country_ids);
        $team_type_ids = [
            $team1->type_id,
            $team2->type_id
        ];
        $team_types = $this->team_type_service->getByIds($team_type_ids);
        $team_type_map = array_combine(array_map(function(TeamType $team_type) {
            return $team_type->id;
        }, $team_types), $team_types);

        /** @var Country[] $countries */
        $countries = $this->country_service->getByIds($country_ids);
        $country_map = array_combine(array_map(function(Country $country) {
            return $country->id;
        }, $countries), $countries);

        $player_map = [];
        $team_player_map = [];
        foreach($players as $player)
        {
            $player_mini_response = PlayerMiniResponse::withPlayerAndCountry($player, CountryResponse::from_country($country_map[$player->country_id]));
            $player_map[$player->id] = $player_mini_response;
            $team_id = $player_to_team_map[$player->id];
            if(!array_key_exists($team_id, $team_player_map))
            {
                $team_player_map[$team_id] = [];
            }
            $team_player_map[$team_id][] = $player_mini_response;
        }

        $man_of_the_match_list = $this->man_of_the_match_service->get_by_match_player_ids($match_player_ids);
        $captains = $this->captain_service->get_by_match_player_ids($match_player_ids);
        $wicket_keepers = $this->wicket_keeper_service->get_by_match_player_ids($match_player_ids);
        $batting_scores = $this->batting_score_service->get_by_match_player_ids($match_player_ids);
        $dismissal_modes = $this->dismissal_mode_service->get_all();
        $dismissal_mode_map = array_combine(array_map(function(DismissalMode $dismissal_mode) {
            return $dismissal_mode->id;
        }, $dismissal_modes), $dismissal_modes);
        $fielder_dismissals = $this->fielder_dismissal_service->get_by_match_player_ids($match_player_ids);
        $fielder_dismissal_map = array_reduce(
            $fielder_dismissals,
            function ($carry, FielderDismissal $fielder_dismissal) {
                $score_id = $fielder_dismissal->score_id;

                if (!array_key_exists($score_id, $carry)) {
                    $carry[$score_id] = [];
                }

                $carry[$score_id][] = $fielder_dismissal;

                return $carry;
            },
            []
        );

        $batting_score_responses = [];
        foreach($batting_scores as $batting_score)
        {
            $batsman_player = $player_map[$match_player_to_player_map[$batting_score->match_player_id]];

            /** @var DismissalModeResponse $dismissal_mode_response */
            $dismissal_mode_response = null;
            if(null != $batting_score->dismissal_mode_id)
            {
                $dismissal_mode_response = new DismissalModeResponse($dismissal_mode_map[$batting_score->dismissal_mode_id]);
            }

            $bowler_player = null;
            if(null != $batting_score->bowler_id)
            {
                $bowler_player = $player_map[$match_player_to_player_map[$batting_score->bowler_id]];
            }

            $fielders = [];
            if(array_key_exists($batting_score->id, $fielder_dismissal_map))
            {
                $fielder_dismissal_list = $fielder_dismissal_map[$batting_score->id];
                $fielders = array_map(function (FielderDismissal $fielder_dismissal) use ($player_map, $match_player_to_player_map) {
                    return $player_map[$match_player_to_player_map[$fielder_dismissal->match_player_id]];
                }, $fielder_dismissal_list);
            }

            $batting_score_responses[] = new BattingScoreResponse(
                $batting_score,
                $batsman_player,
                $dismissal_mode_response,
                $bowler_player,
                $fielders
            );
        }

        $bowling_figures = $this->bowling_figure_service->get_by_match_player_ids($match_player_ids);
        $bowling_figure_responses = array_map(function (BowlingFigure $bowling_figure) use ($player_map, $match_player_to_player_map) {
            $bowler_player = $player_map[$match_player_to_player_map[$bowling_figure->match_player_id]];
            return new BowlingFigureResponse($bowling_figure, $bowler_player);
        }, $bowling_figures);

        $extras_types = $this->extras_type_service->get_all();
        $extras_type_map = array_combine(array_map(function (ExtrasType $extras_type) {
            return $extras_type->id;
        }, $extras_types), $extras_types);
        $extras_list = $this->extras_service->get_by_match_id($id);
        $extras_responses = array_map(function (Extras $extras) use ($extras_type_map, $team_map, $country_map, $team_type_map) {
            $extras_type_response = new ExtrasTypeResponse($extras_type_map[$extras->type_id]);
            /** @var Team $batting_team */
            $batting_team = $team_map[$extras->batting_team_id];
            /** @var Team $bowling_team */
            $bowling_team = $team_map[$extras->bowling_team_id];
            return new ExtrasResponse(
                $extras,
                $extras_type_response,
                TeamResponse::withTeamAndCountryAndType($batting_team, CountryResponse::from_country($country_map[$batting_team->country_id]), TeamTypeResponse::from_team_type($team_type_map[$batting_team->type_id])),
                TeamResponse::withTeamAndCountryAndType($bowling_team, CountryResponse::from_country($country_map[$bowling_team->country_id]), TeamTypeResponse::from_team_type($team_type_map[$bowling_team->type_id]))
            );
        }, $extras_list);

        $match_response = new MatchResponse(
            $match,
            $series,
            $game_type,
            TeamResponse::withTeamAndCountryAndType($team1, CountryResponse::from_country($country_map[$team1->country_id]), TeamTypeResponse::from_team_type($team_type_map[$team1->type_id])),
            TeamResponse::withTeamAndCountryAndType($team2, CountryResponse::from_country($country_map[$team2->country_id]), TeamTypeResponse::from_team_type($team_type_map[$team2->type_id])),
            ResultTypeResponse::from_result_type($result_type),
            $win_margin_type_response,
            StadiumResponse::withStadiumAndCountry($stadium, CountryResponse::from_country($country_map[$stadium->country_id])),
            $team_player_map,
            $batting_score_responses,
            $bowling_figure_responses,
            $extras_responses,
            array_map(function(ManOfTheMatch $man_of_the_match) use ($player_map, $match_player_to_player_map) {
                return $match_player_to_player_map[$man_of_the_match->match_player_id];
            }, $man_of_the_match_list),
            array_map(function (Captain $captain) use ($player_map, $match_player_to_player_map) {
                return $match_player_to_player_map[$captain->match_player_id];
            }, $captains),
            array_map(function (WicketKeeper $wicket_keeper) use ($player_map, $match_player_to_player_map) {
                return $match_player_to_player_map[$wicket_keeper->match_player_id];
            }, $wicket_keepers)
        );

        return $this->ok($match_response);
    }

    /**
     * @throws NotFoundException
     * @throws Exception
     */
    public function remove(int $id)
    {
        /** @var Game $match */
        $match = $this->match_service->get_by_id($id);
        if (null == $match) {
            throw new NotFoundException('Match');
        }

        try
        {
            $this->db->begin();

            $match_player_maps = $this->match_player_map_service->get_by_match_id($id);
            $match_player_ids = array_map(function ($mpm) {
                return $mpm->id;
            }, $match_player_maps);
            $this->extras_service->remove($id);
            $this->captain_service->remove($match_player_ids);
            $this->wicket_keeper_service->remove($match_player_ids);
            $this->man_of_the_match_service->remove($match_player_ids);
            $this->fielder_dismissal_service->remove($match_player_ids);
            $this->batting_score_service->remove($match_player_ids);
            $this->bowling_figure_service->remove($match_player_ids);
            $this->match_player_map_service->remove($id);
            $this->match_service->remove($id);

            $this->db->commit();
        }
        catch(Exception $ex)
        {
            $this->db->rollback();
            throw $ex;
        }

        return $this->ok_with_message('Deleted successfully');
    }
}