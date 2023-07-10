<?php


namespace app\responses;


class BowlingStats
{
    public int $innings = 0;
    public int $balls = 0;
    public int $maidens = 0;
    public int $runs = 0;
    public int $wickets = 0;
    public ?float $economy = null;
    public ?float $average = null;
    public ?float $strikeRate = null;
    public int $fifers = 0;
    public int $tenWickets = 0;

    public function __construct($basic_stats)
    {
        $this->innings = $basic_stats['innings'];
        $this->balls = $basic_stats['balls'] ?? 0;
        $this->maidens = $basic_stats['maidens'] ?? 0;
        $this->runs = $basic_stats['runs'] ?? 0;
        $this->wickets = $basic_stats['wickets'] ?? 0;
        $this->fifers = $basic_stats['fifers'];
        $this->tenWickets = $basic_stats['tenWickets'];
    }
}