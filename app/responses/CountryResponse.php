<?php

namespace app\responses;

use app\models\Country;

class CountryResponse
{
    public int $id;
    public string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function from_country(Country $country)
    {
        return new CountryResponse($country->id, $country->name);
    }
}