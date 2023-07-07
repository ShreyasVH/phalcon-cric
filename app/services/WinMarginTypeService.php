<?php


namespace app\services;


use app\models\WinMarginType;

class WinMarginTypeService
{
    public function getById(int $id)
    {
        return WinMarginType::getById($id);
    }
}