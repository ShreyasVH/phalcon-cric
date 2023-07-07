<?php


namespace app\responses;


use app\models\Extras;

class ExtrasResponse
{
    public int $id;
    public int $runs;
    public ExtrasTypeResponse $type;
    public TeamResponse $battingTeam;
    public TeamResponse $bowlingTeam;
    public int $innings;

    public function __construct(Extras $extras, ExtrasTypeResponse $extras_type, TeamResponse $batting_team, TeamResponse $bowling_team)
    {
        $this->id = $extras->id;
        $this->runs = $extras->runs;
        $this->type = $extras_type;
        $this->battingTeam = $batting_team;
        $this->bowlingTeam = $bowling_team;
        $this->innings = $extras->innings;
    }
}