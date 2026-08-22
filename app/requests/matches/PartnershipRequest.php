<?php


namespace app\requests\matches;


class PartnershipRequest
{
    public int $innings;
    public int $wicket;
    public int $runs;
    public int $balls;
    public bool $ended;
    public int $playerId1;
    public int $runs1;
    public int $balls1;
    public int $playerId2;
    public int $runs2;
    public int $balls2;

    public function __construct(array $partnership_request)
    {
        $this->innings = $partnership_request['innings'];
        $this->wicket = $partnership_request['wicket'];
        $this->runs = $partnership_request['runs'];
        $this->balls = $partnership_request['balls'];
        $this->ended = $partnership_request['ended'];
        $this->playerId1 = $partnership_request['playerId1'];
        $this->runs1 = $partnership_request['runs1'];
        $this->balls1 = $partnership_request['balls1'];
        $this->playerId2 = $partnership_request['playerId2'];
        $this->runs2 = $partnership_request['runs2'];
        $this->balls2 = $partnership_request['balls2'];
    }
}
