<?php


namespace app\requests\matches;


class TotalRequestEntry
{
    public int $teamId;
    public int $runs;
    public int $wickets;
    public int $balls;
    public int $innings;

    public function __construct(array $total)
    {
        $this->teamId = $total['teamId'];
        $this->runs = $total['runs'];
        $this->wickets = $total['wickets'];
        $this->balls = $total['balls'];
        $this->innings = $total['innings'];
    }
}