<?php

namespace app\repositories;

use app\models\TeamType;

class TeamTypeRepository
{
    public function getById(int $id)
    {
        return TeamType::getById($id);
    }

    public function getByIds(array $ids)
    {
        return TeamType::getByIds($ids);
    }
}