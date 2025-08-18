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
}