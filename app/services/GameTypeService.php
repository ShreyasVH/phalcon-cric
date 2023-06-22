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

    public function get_by_ids(array $ids)
    {
        return $this->game_type_repository->get_by_ids($ids);
    }
}