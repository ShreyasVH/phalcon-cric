<?php


namespace app\services;


use app\models\Extras;

class ExtrasService
{
    public function add(int $match_id, array $extras_requests)
    {
        return Extras::add($match_id, $extras_requests);
    }
}