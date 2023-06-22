<?php

namespace app\requests\series;

use app\exceptions\BadRequestException;

class UpdateRequest
{
    public ?string $name;
    public ?int $homeCountryId;
    public ?int $tourId;
    public ?int $typeId;
    public ?int $gameTypeId;
    public $startTime;
    public ?array $teams;
    public ?array $manOfTheSeriesList;

    public function __construct($name, $homeCountryId, $tourId, $typeId, $gameTypeId, $startTime, $teams, $manOfTheSeriesList)
    {
        $this->name = $name;
        $this->homeCountryId = $homeCountryId;
        $this->tourId = $tourId;
        $this->typeId = $typeId;
        $this->gameTypeId = $gameTypeId;
        $this->startTime = $startTime;
        $this->teams = $teams;
        $this->manOfTheSeriesList = $manOfTheSeriesList;
    }

    public function validate()
    {
        if(!empty($this->teams) && (!is_array($this->teams) || count($this->teams) < 2))
        {
            throw new BadRequestException("Invalid teams");
        }
    }

    public static function fromPostRequest(array $request)
    {
        return new UpdateRequest($request['name'], $request['homeCountryId'], $request['tourId'], $request['typeId'], $request['gameTypeId'], $request['startTime'], $request['teams'], $request['manOfTheSeriesList']);
    }
}