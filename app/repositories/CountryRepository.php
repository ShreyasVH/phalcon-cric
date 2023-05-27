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

    public function getAll(int $page, int $limit)
    {
        return Country::getAll($page, $limit);
    }

    public function getTotalCount()
    {
        return Country::getTotalCount();
    }

    public function getById(int $id)
    {
        return Country::getById($id);
    }

    public function getByIds(array $ids)
    {
        return Country::getByIds($ids);
    }
}