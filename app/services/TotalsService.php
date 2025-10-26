<?php


namespace app\services;


use app\models\Total;

class TotalsService
{
    /**
     * @param Total[] $totals
     */
    public function add(array $totals)
    {
        Total::add($totals);
    }

    /**
     * @param int $match_id
     */
    public function remove(int $match_id)
    {
        Total::remove($match_id);
    }
}