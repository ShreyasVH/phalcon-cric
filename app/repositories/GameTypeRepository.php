<?php


namespace app\repositories;


use app\models\GameType;

class GameTypeRepository
{
    public function getById(int $id)
    {
        return GameType::getById($id);
    }

    public function get_by_ids(array $ids)
    {
        return GameType::getByIds($ids);
    }
}