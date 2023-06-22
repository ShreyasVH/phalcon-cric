<?php


namespace app\repositories;


use app\models\GameType;

class GameTypeRepository
{
    public function getById(int $id)
    {
        return GameType::getById($id);
    }
}