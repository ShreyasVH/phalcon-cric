<?php


namespace app\responses;


use app\models\Player;

class PlayerResponse implements \JsonSerializable
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

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'dateOfBirth' => $this->dateOfBirth,
            'image' => $this->image,
            'dismissalStats' => empty($this->dismissalStats) ? new \stdClass() : $this->dismissalStats,
            'battingStats' => empty($this->battingStats) ? new \stdClass() : $this->battingStats,
            'bowlingStats' => empty($this->bowlingStats) ? new \stdClass() : $this->bowlingStats,
            'fieldingStats' => empty($this->fieldingStats) ? new \stdClass() : $this->fieldingStats
        ];
    }
}