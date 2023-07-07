<?php

namespace app\requests\matches;

class BattingScoreRequest
{
    public int $playerId;
    public int $runs;
    public int $balls;
    public int $fours;
    public int $sixes;
    public ?int $dismissalModeId;
    public ?int $bowlerId;
    public ?array $fielderIds;
    public int $innings;
    public ?int $number;

    public function __construct(array $create_request)
    {
        $this->playerId = $create_request['playerId'];
        $this->runs = $create_request['runs'];
        $this->balls = $create_request['balls'];
        $this->fours = $create_request['fours'];
        $this->sixes = $create_request['sixes'];

        $dismissal_mode_id = null;
        $bowler_id = null;
        $fielder_ids = null;
        if(array_key_exists('dismissalModeId', $create_request))
        {
            $dismissal_mode_id = $create_request['dismissalModeId'];
            if(array_key_exists('bowlerId', $create_request))
            {
                $bowler_id = $create_request['bowlerId'];
            }
            if(array_key_exists('fielderIds', $create_request))
            {
                $fielder_ids = $create_request['fielderIds'];
            }
        }

        $this->dismissalModeId = $dismissal_mode_id;
        $this->bowlerId = $bowler_id;
        $this->fielderIds = $fielder_ids;

        $this->innings = $create_request['innings'];
        $this->number = $create_request['number'];
    }

}