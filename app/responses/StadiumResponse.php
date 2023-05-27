<?php

namespace app\responses;

use app\models\Stadium;

class StadiumResponse
{
    public int $id;
    public string $name;
    public string $city;
    public string $state;
    public CountryResponse $country;

    public function __construct(int $id, string $name, string $city, string $state, CountryResponse $country)
    {
        $this->id = $id;
        $this->name = $name;
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
    }

    public static function withStadiumAndCountry(Stadium $stadium, CountryResponse $country)
    {
        return new StadiumResponse($stadium->id, $stadium->name, $stadium->city, $stadium->state, $country);
    }
}