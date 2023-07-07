<?php


namespace app\services;


use app\models\ExtrasType;

class ExtrasTypeService
{
    public function getById(int $id)
    {
        return ExtrasType::getById($id);
    }

    public function get_all()
    {
        return ExtrasType::get_all();
    }
}