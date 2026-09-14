<?php

namespace app\requests\matches;

class BallwiseDetailRequest
{
    public int $batsmanPlayerId;
    public int $bowlerPlayerId;
    public int $innings;
    public int $ball;
    public bool $dismissal;
    public int $totalRuns;
    public int $batsmanRuns;
    public int $bowlerRuns;
    public int $extrasRuns;
    public string $extrasType;

    public int $timestamp;

    public function __construct(array $ballwise_detail_request)
    {
        $this->batsmanPlayerId = $ballwise_detail_request['batsmanPlayerId'];
        $this->bowlerPlayerId = $ballwise_detail_request['bowlerPlayerId'];
        $this->innings = $ballwise_detail_request['innings'];
        $this->ball = $ballwise_detail_request['ball'];
        $this->dismissal = $ballwise_detail_request['dismissal'];
        $this->totalRuns = $ballwise_detail_request['totalRuns'];
        $this->batsmanRuns = $ballwise_detail_request['batsmanRuns'];
        $this->bowlerRuns = $ballwise_detail_request['bowlerRuns'];
        $this->extrasRuns = $ballwise_detail_request['extrasRuns'];
        $this->extrasType = $ballwise_detail_request['extrasType'];
        $this->timestamp = $ballwise_detail_request['timestamp'];
    }
}