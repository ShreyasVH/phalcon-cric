<?php

namespace app\repositories;

use app\models\Tour;
use app\requests\tours\CreateRequest;

class TourRepository
{
    public function create(CreateRequest $create_request)
    {
        $tour = Tour::fromRequest($create_request);
        $tour->save();

        return $tour;
    }

    public function findByNameAndStartTime(string $name, string $startTime)
    {
        return Tour::findByNameAndStartTime($name, $startTime);
    }

    public function get_by_id(int $id)
    {
        return Tour::getById($id);
    }
}