<?php

namespace app\responses;

use app\models\Tour;

class TourMiniResponse
{
    public int $id;
    public string $name;
    public $startTime;

    public function __construct(int $id, string $name, $startTime)
    {
        $this->id = $id;
        $this->name = $name;
        $this->startTime = $startTime;
    }

    public static function from_tour(Tour $tour)
    {
        return new TourMiniResponse($tour->id, $tour->name, $tour->start_time);
    }
}