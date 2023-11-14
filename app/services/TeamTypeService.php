<?php


namespace app\services;


use app\exceptions\ConflictException;
use app\models\Country;
use app\models\TeamType;
use app\repositories\TeamTypeRepository;
use app\requests\countries\CreateRequest;

class TeamTypeService
{
    protected TeamTypeRepository $team_type_repository;

    public function __construct() {
        $this->team_type_repository = new TeamTypeRepository();
    }

    public function getById(int $id)
    {
        return $this->team_type_repository->getById($id);
    }

    /**
     * @param int[] $ids
     * @return TeamType[]
     */
    public function getByIds(array $ids)
    {
        return $this->team_type_repository->getByIds($ids);
    }
}