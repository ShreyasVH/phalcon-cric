<?php

namespace app\repositories;

use app\models\SeriesType;

class SeriesTypeRepository
{
    public function getById(int $id)
    {
        return SeriesType::getById($id);
    }

    public function get_by_ids(array $ids)
    {
        return SeriesType::getByIds($ids);
    }
}