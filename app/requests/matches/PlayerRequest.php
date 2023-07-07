<?php


namespace app\requests\matches;


class PlayerRequest
{
    public int $id;
    public int $teamId;

    public function __construct(array $create_request)
    {
        $this->id = $create_request['id'];
        $this->teamId = $create_request['teamId'];
    }
}