<?php

namespace app\repositories;

use app\models\TeamType;

class TeamTypeRepository
{
    public function getById(int $id)
    {
        return TeamType::getById($id);
    }
}