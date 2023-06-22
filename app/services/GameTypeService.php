<?php


namespace app\services;


use app\repositories\GameTypeRepository;

class GameTypeService
{
    protected GameTypeRepository $game_type_repository;

    public function __construct() {
        $this->game_type_repository = new GameTypeRepository();
    }

    public function getById(int $id)
    {
        return $this->game_type_repository->getById($id);
    }
}