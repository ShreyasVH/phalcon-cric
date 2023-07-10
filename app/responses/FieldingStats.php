<?php


namespace app\responses;


class FieldingStats
{
    public int $catches = 0;
    public int $runOuts = 0;
    public int $stumpings = 0;

    public function __construct($fielding_stats)
    {
        $this->catches = $fielding_stats['Caught'] ?? 0;
        $this->runOuts = $fielding_stats['Run Out'] ?? 0;
        $this->stumpings = $fielding_stats['Stumped'] ?? 0;
    }

}