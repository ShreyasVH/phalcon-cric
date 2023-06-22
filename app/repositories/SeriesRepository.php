<?php

namespace app\repositories;

use app\models\Series;
use app\requests\series\CreateRequest;

class SeriesRepository
{
    public function create(CreateRequest $create_request)
    {
        $series = Series::fromRequest($create_request);
        $series->save();
        return $series;
    }

    public function findByNameAndTourIdAndGameTypeId(string $name, int $tourId, int $gameTypeId)
    {
        return Series::findByNameAndTourIdAndGameTypeId($name, $tourId, $gameTypeId);
    }

    public function getAll(int $page, int $limit)
    {
        return Series::getAll($page, $limit);
    }

    public function getTotalCount()
    {
        return Series::getTotalCount();
    }
}