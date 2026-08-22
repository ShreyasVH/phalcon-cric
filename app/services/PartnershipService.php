<?php


namespace app\services;


use app\models\Partnership;

class PartnershipService
{
    public function add(array $partnership_requests, $player_to_match_player_map)
    {
        return Partnership::add($partnership_requests, $player_to_match_player_map);
    }

//    public function get_bowling_stats($player_id)
//    {
//        return $this->_bowling_figure_repository->get_bowling_stats($player_id);
//    }
//
//    /**
//     * @param int[] $match_player_ids
//     * @return BowlingFigure[]
//     */
//    public function get_by_match_player_ids(array $match_player_ids): array
//    {
//        return BowlingFigure::get_by_match_player_ids($match_player_ids);
//    }
//
//    /**
//     * @param array $match_player_ids
//     */
//    public function remove(array $match_player_ids)
//    {
//        BowlingFigure::remove($match_player_ids);
//    }
}