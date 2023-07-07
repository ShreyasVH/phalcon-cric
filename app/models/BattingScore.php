<?php


namespace app\models;



use app\requests\matches\BattingScoreRequest;

class BattingScore extends BaseModel
{
    public $id;
    public $match_player_id;
    public $runs;
    public $balls;
    public $fours;
    public $sixes;
    public $dismissal_mode_id;
    public $bowler_id;
    public $innings;
    public $number;

    public function initialize()
    {
        $this->setSource('batting_scores');
    }

    public static function from_batting_score_request(BattingScoreRequest $batting_score_request, $match_player_map)
    {
        $batting_score = new self();

        $batting_score->runs = $batting_score_request->runs;
        $batting_score->balls = $batting_score_request->balls;
        $batting_score->fours = $batting_score_request->fours;
        $batting_score->sixes = $batting_score_request->sixes;
        $batting_score->dismissal_mode_id = $batting_score_request->dismissalModeId;
        $batting_score->innings = $batting_score_request->innings;
        $batting_score->number = $batting_score_request->number;
        $batting_score->match_player_id = $match_player_map[$batting_score_request->playerId];
        if(null != $batting_score_request->bowlerId)
        {
            $batting_score->bowler_id = $match_player_map[$batting_score_request->bowlerId];
        }

        return $batting_score;
    }

    public static function add(array $batting_score_requests, $player_to_match_player_map)
    {
        $batting_scores = [];
        /** @var BattingScoreRequest $batting_score_request */
        foreach($batting_score_requests as $batting_score_request)
        {
            $batting_score = BattingScore::from_batting_score_request($batting_score_request, $player_to_match_player_map);
            $batting_score->save();
            $batting_scores[] = $batting_score;
        }
        return $batting_scores;
    }
}