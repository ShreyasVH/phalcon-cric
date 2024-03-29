<?php


namespace app\services;


use app\exceptions\ConflictException;
use app\models\Team;
use app\repositories\TeamRepository;
use app\requests\teams\CreateRequest;

class TeamService
{
    protected TeamRepository $teamRepository;

    public function __construct() {
        $this->teamRepository = new TeamRepository();
    }

    public function create(CreateRequest $create_request): Team
    {
        $create_request->validate();

        $existingTeam = $this->teamRepository->findByNameAndCountryIdAndTypeId($create_request->name, $create_request->countryId, $create_request->typeId);
        if(null != $existingTeam)
        {
            throw new ConflictException('Team');
        }
        return $this->teamRepository->create($create_request);
    }

    public function getAll(int $page, int $limit)
    {
        return $this->teamRepository->getAll($page, $limit);
    }

    public function getTotalCount()
    {
        return $this->teamRepository->getTotalCount();
    }

    /**
     * @param int[] $ids
     * @return Team[]
     */
    public function get_by_ids(array $ids): array
    {
        return $this->teamRepository->get_by_ids($ids);
    }
}