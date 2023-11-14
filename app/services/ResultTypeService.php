<?php


namespace app\services;


use app\models\ResultType;

class ResultTypeService
{
    public function getById(int $id)
    {
        return ResultType::getById($id);
    }

    /**
     * @param int[] $ids
     * @return ResultType[]
     */
    public function get_by_ids(array $ids): array
    {
        return ResultType::get_by_ids($ids);
    }
}