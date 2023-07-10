<?php

namespace app\repositories;

use app\models\Player;
use app\requests\players\CreateRequest;

class PlayerRepository
{
    public function create(CreateRequest $create_request)
    {
        $player = Player::fromRequest($create_request);
        $player->save();
        return $player;
    }

    public function findByNameAndCountryIdAndDateOfBirth(string $name, int $countryId, $dateOfBirth)
    {
        return Player::findByNameAndCountryIdAndDateOfBirth($name, $countryId, $dateOfBirth);
    }

    public function getAll(int $page, int $limit)
    {
        return Player::getAll($page, $limit);
    }

    public function getTotalCount()
    {
        return Player::getTotalCount();
    }


}