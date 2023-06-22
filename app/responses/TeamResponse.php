<?php

namespace app\responses;

use app\models\Team;

class TeamResponse
{
    public int $id;
    public string $name;
    public CountryResponse $country;
    public TeamTypeResponse $type;

    public function __construct(int $id, string $name, CountryResponse $country, TeamTypeResponse $teamTypeResponse)
    {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->type = $teamTypeResponse;
    }

    public static function withTeamAndCountryAndType(Team $team, CountryResponse $country, TeamTypeResponse $type)
    {
        return new TeamResponse($team->id, $team->name, $country, $type);
    }
}