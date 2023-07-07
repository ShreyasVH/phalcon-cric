<?php


namespace app\models;


use app\requests\matches\CreateRequest;

class Game extends BaseModel
{
    public $id;
    public $series_id;
    public $team1_id;
    public $team2_id;
    public $toss_winner_id;
    public $bat_first_id;
    public $result_type_id;
    public $winner_id;
    public $win_margin;
    public $win_margin_type_id;
    public $stadium_id;
    public $start_time;
    public $is_official;

    public function initialize()
    {
        $this->setSource('matches');
    }

    public static function get_by_stadium_and_start_time(int $stadium_id, $start_time)
    {
        return self::findFirst([
            'conditions' => 'stadium_id = :stadiumId: and start_time = :startTime:',
            'bind' => [
                'stadiumId' => $stadium_id,
                'startTime' => $start_time
            ]
        ]);
    }

    public static function from_request(CreateRequest $create_request)
    {
        $match = new self();

        $match->series_id = $create_request->seriesId;
        $match->team1_id = $create_request->team1Id;
        $match->team2_id = $create_request->team2Id;
        $match->toss_winner_id = $create_request->tossWinnerId;
        $match->bat_first_id = $create_request->batFirstId;
        $match->result_type_id = $create_request->resultTypeId;
        $match->winner_id = $create_request->winnerId;
        $match->win_margin = $create_request->winMargin;
        $match->win_margin_type_id = $create_request->winMarginTypeId;
        $match->stadium_id = $create_request->stadiumId;
        $match->start_time = $create_request->startTime;
        $match->is_official = $create_request->isOfficial;

        return $match;
    }
}