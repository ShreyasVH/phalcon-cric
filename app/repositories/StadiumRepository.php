<?php

namespace app\repositories;

use app\models\Stadium;
use app\requests\stadiums\CreateRequest;

class StadiumRepository
{
    public function create(CreateRequest $create_request)
    {
        $stadium = Stadium::fromRequest($create_request);
        $stadium->save();
        return $stadium;
    }

    public function findByNameAndCountryId(string $name, int $countryId)
    {
        return Stadium::findByNameAndCountryId($name, $countryId);
    }

    public function getAll(int $page, int $limit)
    {
        return Stadium::getAll($page, $limit);
    }

    public function getTotalCount()
    {
        return Stadium::getTotalCount();
    }
}