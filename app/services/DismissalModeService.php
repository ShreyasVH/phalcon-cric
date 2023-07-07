<?php


namespace app\services;


use app\models\DismissalMode;

class DismissalModeService
{
    public function getById(int $id)
    {
        return DismissalMode::getById($id);
    }

    public function get_all()
    {
        return DismissalMode::get_all();
    }
}