<?php

namespace app\responses;

use app\models\TeamType;

class TeamTypeResponse
{
    public int $id;
    public string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function from_team_type(TeamType $teamType)
    {
        return new TeamTypeResponse($teamType->id, $teamType->name);
    }
}