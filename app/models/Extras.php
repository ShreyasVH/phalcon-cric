<?php


namespace app\models;


use app\requests\matches\ExtrasRequest;

class Extras extends BaseModel
{
    public $id;
    public $match_id;
    public $type_id;
    public $runs;
    public $batting_team_id;
    public $bowling_team_id;
    public $innings;

    public function initialize()
    {
        $this->setSource('extras');
    }

    public static function from_request(int $match_id, ExtrasRequest $extras_request)
    {
        $extras = new self();

        $extras->match_id = $match_id;
        $extras->type_id = $extras_request->typeId;
        $extras->runs = $extras_request->runs;
        $extras->batting_team_id = $extras_request->battingTeamId;
        $extras->bowling_team_id = $extras_request->bowlingTeamId;
        $extras->innings = $extras_request->innings;

        return $extras;
    }

    public static function add(int $match_id, array $extras_requests)
    {
        $extras_list = [];

        /** @var ExtrasRequest $extras_request */
        foreach($extras_requests as $extras_request)
        {
            $extras = Extras::from_request($match_id, $extras_request);
            $extras->save();
            $extras_list[] = $extras;
        }

        return $extras_list;
    }
}