<?php


namespace app\services;


use app\models\BallwiseDetail;

class BallwiseDetailService
{
    public function add(array $ballwise_detail_requests, $player_to_match_player_map)
    {
        return BallwiseDetail::add($ballwise_detail_requests, $player_to_match_player_map);
    }

//    /**
//     * @param int[] $match_player_ids
//     * @return Partnership[]
//     */
//    public function get_by_match_player_ids(array $match_player_ids): array
//    {
//        return Partnership::get_by_match_player_ids($match_player_ids);
//    }
//
    /**
     * @param array $match_player_ids
     */
    public function remove(array $match_player_ids)
    {
        BallwiseDetail::remove($match_player_ids);
    }
}