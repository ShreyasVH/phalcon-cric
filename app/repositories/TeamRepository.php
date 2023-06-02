<?php

namespace app\repositories;

use app\models\Team;
use app\requests\teams\CreateRequest;

class TeamRepository
{
    public function create(CreateRequest $create_request)
    {
        $team = Team::fromRequest($create_request);
        $team->save();
        return $team;
    }

    public function findByNameAndCountryIdAndTypeId(string $name, int $countryId, int $typeId)
    {
        return Team::findByNameAndCountryIdAndTypeId($name, $countryId, $typeId);
    }

    public function getAll(int $page, int $limit)
    {
        return Team::getAll($page, $limit);
    }

    public function getTotalCount()
    {
        return Team::getTotalCount();
    }
}