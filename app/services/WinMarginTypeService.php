<?php


namespace app\services;


use app\models\WinMarginType;

class WinMarginTypeService
{
    public function getById(int $id)
    {
        return WinMarginType::getById($id);
    }

    /**
     * @param int[] $ids
     * @return WinMarginType[]
     */
    public function get_by_ids(array $ids): array
    {
        return WinMarginType::get_by_ids($ids);
    }
}