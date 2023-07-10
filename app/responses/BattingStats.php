<?php


namespace app\responses;


class BattingStats
{
    public int $runs;
    public int $balls;
    public int $fours;
    public int $sixes;
    public int $notOuts = 0;
    public int $highest;
    public ?float $average = null;
    public ?float $strikeRate = null;
    public int $fifties;
    public int $hundreds;
    public int $twoHundreds;
    public int $threeHundreds;
    public int $fourHundreds;

    public function __construct($basic_stats)
    {
        $this->runs = $basic_stats['runs'] ?? 0;
        $this->balls = $basic_stats['balls'];
        $this->fours = $basic_stats['fours'];
        $this->sixes = $basic_stats['sixes'];
        $this->highest = $basic_stats['highest'];
        $this->fifties = $basic_stats['fifties'];
        $this->hundreds = $basic_stats['hundreds'];
        $this->twoHundreds = $basic_stats['twoHundreds'];
        $this->threeHundreds = $basic_stats['threeHundreds'];
        $this->fourHundreds = $basic_stats['fourHundreds'];
    }
}