<?php


namespace app\services;


use app\models\Partnership;

class PartnershipService
{
    public function add(array $partnership_requests, $player_to_match_player_map)
    {
        return Partnership::add($partnership_requests, $player_to_match_player_map);
    }

    /**
     * @param int[] $match_player_ids
     * @return Partnership[]
     */
    public function get_by_match_player_ids(array $match_player_ids): array
    {
        return Partnership::get_by_match_player_ids($match_player_ids);
    }

    /**
     * @param array $match_player_ids
     */
    public function remove(array $match_player_ids)
    {
        Partnership::remove($match_player_ids);
    }
}