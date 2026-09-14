<?php


namespace app\models;


use app\requests\matches\BallwiseDetailRequest;

class BallwiseDetail extends BaseModel
{
    public $id;
    public $batsman_match_player_id;
    public $bowler_match_player_id;
    public $innings;
    public $ball;
    public $dismissal;
    public $total_runs;
    public $batsman_runs;
    public $bowler_runs;
    public $extras_runs;
    public $extras_type;
    public $timestamp;

    public function initialize()
    {
        $this->setSource('ballwise_details');
    }

    public static function with_ballwise_details_request(BallwiseDetailRequest $ballwise_detail_request, $player_to_match_player_map)
    {
        $ballwise_detail = new self();

        $ballwise_detail->batsman_match_player_id = $player_to_match_player_map[$ballwise_detail_request->batsmanPlayerId];
        $ballwise_detail->bowler_match_player_id = $player_to_match_player_map[$ballwise_detail_request->bowlerPlayerId];
        $ballwise_detail->innings = $ballwise_detail_request->innings;
        $ballwise_detail->ball = $ballwise_detail_request->ball;
        $ballwise_detail->dismissal = $ballwise_detail_request->dismissal ? 1 : 0;
        $ballwise_detail->total_runs = $ballwise_detail_request->totalRuns;
        $ballwise_detail->batsman_runs = $ballwise_detail_request->batsmanRuns;
        $ballwise_detail->bowler_runs = $ballwise_detail_request->bowlerRuns;
        $ballwise_detail->extras_runs = $ballwise_detail_request->extrasRuns;
        $ballwise_detail->extras_type = $ballwise_detail_request->extrasType;
        $ballwise_detail->timestamp = $ballwise_detail_request->timestamp;

        return $ballwise_detail;
    }

    public static function add(array $ballwise_detail_requests, $player_to_match_player_map)
    {
        $ballwise_detail_list = [];

        /** @var BallwiseDetailRequest $ballwise_detail_request */
        foreach($ballwise_detail_requests as $ballwise_detail_request)
        {
            $ballwise_detail = BallwiseDetail::with_ballwise_details_request($ballwise_detail_request, $player_to_match_player_map);
            $ballwise_detail->save();
            $ballwise_detail_list[] = $ballwise_detail;
        }

        return $ballwise_detail_list;
    }

//    /**
//     * @param int[] $match_player_ids
//     * @return BowlingFigure[]
//     */
//    public static function get_by_match_player_ids_all(array $match_player_ids): array
//    {
//        $partnerships = [];
//
//        if(!empty($match_player_ids))
//        {
//            $partnerships = self::toList(self::find([
//                'conditions' => 'match_player_id_1 IN ({matchPlayerIds:array}) OR match_player_id_2 IN ({matchPlayerIds:array})',
//                'bind' => ['matchPlayerIds' => $match_player_ids]
//            ]));
//        }
//
//        return $partnerships;
//    }
//
//    /**
//     * @param int[] $match_player_ids
//     * @return BowlingFigure[]
//     */
//    public static function get_by_match_player_ids(array $match_player_ids): array
//    {
//        $partnerships = [];
//
//        if(!empty($match_player_ids))
//        {
//            $partnerships = self::toList(self::find([
//                'conditions' => '(match_player_id_1 IN ({matchPlayerIds:array}) OR match_player_id_2 IN ({matchPlayerIds:array})) AND primary_entry = 1',
//                'bind' => ['matchPlayerIds' => $match_player_ids]
//            ]));
//        }
//
//        return $partnerships;
//    }
//
//    /**
//     * @param int[] $match_player_ids
//     */
//    public static function remove(array $match_player_ids)
//    {
//        foreach(self::get_by_match_player_ids_all($match_player_ids) as $partnership)
//        {
//            $partnership->delete();
//        }
//    }
}