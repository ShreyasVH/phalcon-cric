<?php

namespace app\repositories;

use app\models\Country;
use app\requests\countries\CreateRequest;

class CountryRepository
{
    public function create(CreateRequest $create_request)
    {
        $country = Country::fromRequest($create_request);
        $country->save();

        return $country;
    }

    public function findByName(string $name)
    {
        return Country::findByName($name);
    }

    public function findByNamePattern(string $name)
    {
        return Country::findByNamePattern($name);
    }
}