<?php


namespace app\services;


use app\models\Extras;

class ExtrasService
{
    public function add(int $match_id, array $extras_requests)
    {
        return Extras::add($match_id, $extras_requests);
    }

    /**
     * @param int $match_id
     * @return Extras[]
     */
    public function get_by_match_id(int $match_id): array
    {
        return Extras::get_by_match_id($match_id);
    }

    /**
     * @param int $match_id
     */
    public function remove(int $match_id)
    {
        Extras::remove($match_id);
    }
}