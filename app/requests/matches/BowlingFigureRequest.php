<?php


namespace app\requests\matches;


class BowlingFigureRequest
{
    public int $playerId;
    public int $balls;
    public int $maidens;
    public int $runs;
    public int $wickets;
    public int $innings;

    public function __construct(array $create_request)
    {
        $this->playerId = $create_request['playerId'];
        $this->balls = $create_request['balls'];
        $this->maidens = $create_request['maidens'];
        $this->runs = $create_request['runs'];
        $this->wickets = $create_request['wickets'];
        $this->innings = $create_request['innings'];
    }
}