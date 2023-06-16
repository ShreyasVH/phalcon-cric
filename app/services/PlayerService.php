<?php


namespace app\services;


use app\exceptions\ConflictException;
use app\models\Player;
use app\repositories\PlayerRepository;
use app\requests\players\CreateRequest;

class PlayerService
{
    protected PlayerRepository $playerRepository;

    public function __construct() {
        $this->playerRepository = new PlayerRepository();
    }

    public function create(CreateRequest $create_request): Player
    {
        $create_request->validate();

        $existingPlayer = $this->playerRepository->findByNameAndCountryIdAndDateOfBirth($create_request->name, $create_request->countryId, $create_request->dateOfBirth);
        if(null != $existingPlayer)
        {
            throw new ConflictException('Player');
        }
        return $this->playerRepository->create($create_request);
    }

    public function getAll(int $page, int $limit)
    {
        return $this->playerRepository->getAll($page, $limit);
    }

    public function getTotalCount()
    {
        return $this->playerRepository->getTotalCount();
    }
}