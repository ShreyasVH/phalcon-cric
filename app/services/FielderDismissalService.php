<?php


namespace app\services;


use app\models\FielderDismissal;
use app\repositories\FielderDismissalRepository;

class FielderDismissalService
{
    private FielderDismissalRepository $_fielder_dismissal_repository;

    public function __construct()
    {
        $this->_fielder_dismissal_repository = new FielderDismissalRepository();
    }

    public function add($score_fielders_maps, $player_to_match_player_map)
    {
        return FielderDismissal::add($score_fielders_maps, $player_to_match_player_map);
    }

    public function get_fielding_stats(int $player_id)
    {
        return $this->_fielder_dismissal_repository->get_fielding_stats($player_id);
    }

    /**
     * @param int[] $match_player_ids
     * @return FielderDismissal[]
     */
    public function get_by_match_player_ids(array $match_player_ids): array
    {
        return FielderDismissal::get_by_match_player_ids($match_player_ids);
    }

    /**
     * @param array $match_player_ids
     */
    public function remove(array $match_player_ids)
    {
        FielderDismissal::remove($match_player_ids);
    }
}