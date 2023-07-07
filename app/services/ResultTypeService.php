<?php


namespace app\services;


use app\models\ResultType;

class ResultTypeService
{
    public function getById(int $id)
    {
        return ResultType::getById($id);
    }
}