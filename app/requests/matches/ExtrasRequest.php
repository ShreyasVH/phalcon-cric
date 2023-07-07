<?php


namespace app\requests\matches;


class ExtrasRequest
{
    public $runs;
    public $typeId;
    public $battingTeamId;
    public $bowlingTeamId;
    public $innings;

    public function __construct(array $create_request)
    {
        $this->runs = $create_request['runs'];
        $this->typeId = $create_request['typeId'];
        $this->battingTeamId = $create_request['battingTeamId'];
        $this->bowlingTeamId = $create_request['bowlingTeamId'];
        $this->innings = $create_request['innings'];
    }
}