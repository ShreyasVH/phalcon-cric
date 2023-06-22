<?php

namespace app\responses;

use app\models\SeriesType;

class SeriesTypeResponse
{
    public int $id;
    public string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function from_series_type(SeriesType $series_type): SeriesTypeResponse
    {
        return new SeriesTypeResponse($series_type->id, $series_type->name);
    }
}