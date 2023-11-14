<?php


namespace app\services;


use app\exceptions\ConflictException;
use app\models\Stadium;
use app\repositories\StadiumRepository;
use app\requests\stadiums\CreateRequest;

class StadiumService
{
    protected StadiumRepository $stadiumRepository;

    public function __construct() {
        $this->stadiumRepository = new StadiumRepository();
    }

    public function create(CreateRequest $create_request): Stadium
    {
        $create_request->validate();

        $existingStadium = $this->stadiumRepository->findByNameAndCountryId($create_request->name, $create_request->countryId);
        if(null != $existingStadium)
        {
            throw new ConflictException('Stadium');
        }
        return $this->stadiumRepository->create($create_request);
    }

    public function getAll(int $page, int $limit)
    {
        return $this->stadiumRepository->getAll($page, $limit);
    }

    public function getTotalCount()
    {
        return $this->stadiumRepository->getTotalCount();
    }

    public function get_by_id(int $stadium_id)
    {
        return Stadium::getById($stadium_id);
    }

    /**
     * @param int[] $ids
     * @return Stadium[]
     */
    public function get_by_ids(array $ids): array
    {
        return Stadium::get_by_ids($ids);
    }
}