<?php


namespace app\models;


use app\requests\matches\TotalRequestEntry;

class Total extends BaseModel
{
    public $id;
    public $match_id;
    public $team_id;
    public $runs;
    public $wickets;
    public $balls;
    public $innings;


    public function initialize()
    {
        $this->setSource('totals');
    }

    public static function from_total_request_entry(int $match_id, TotalRequestEntry $totalRequestEntry)
    {
        $total = new self();

        $total->match_id = $match_id;
        $total->team_id = $totalRequestEntry->teamId;
        $total->runs = $totalRequestEntry->runs;
        $total->wickets = $totalRequestEntry->wickets;
        $total->balls = $totalRequestEntry->balls;
        $total->innings = $totalRequestEntry->innings;

        return $total;
    }

    public static function add(array $totals)
    {
        /** @var Total $total */
        foreach($totals as $total)
        {
            $total->save();
        }
    }
}