<?php

namespace app\responses;

use app\models\Player;

class PlayerMiniResponse
{
    public int $id;
    public string $name;
    public CountryResponse $country;
    public $dateOfBirth;
    public string $image;

    public function __construct(int $id, string $name, CountryResponse $country, $dateOfBirth, string $image)
    {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->dateOfBirth = $dateOfBirth;
        $this->image = $image;
    }

    public static function withPlayerAndCountry(Player $player, CountryResponse $country)
    {
        return new PlayerMiniResponse($player->id, $player->name, $country, $player->date_of_birth, $player->image);
    }
}