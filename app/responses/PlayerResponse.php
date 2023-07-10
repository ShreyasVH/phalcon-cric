<?php


namespace app\responses;


use app\models\Player;

class PlayerResponse
{
    public int $id;
    public string $name;
    public CountryResponse $country;
    public $dateOfBirth;
    public string $image;
    public $dismissalStats = [];
    public $battingStats = [];
    public $bowlingStats = [];
    public $fieldingStats = [];

    public function __construct(int $id, string $name, $dateOfBirth, string $image)
    {
        $this->id = $id;
        $this->name = $name;
        $this->dateOfBirth = $dateOfBirth;
        $this->image = $image;
    }

    public static function withPlayer(Player $player)
    {
        return new self($player->id, $player->name, $player->date_of_birth, $player->image);
    }
}