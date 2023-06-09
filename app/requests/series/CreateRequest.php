<?php

namespace app\requests\series;

use app\exceptions\BadRequestException;

class CreateRequest
{
    public ?string $name;
    public ?int $homeCountryId;
    public ?int $tourId;
    public ?int $typeId;
    public ?int $gameTypeId;
    public $startTime;
    public array $teams;

    public function __construct($name, $homeCountryId, $tourId, $typeId, $gameTypeId, $startTime, $teams)
    {
        $this->name = $name;
        $this->homeCountryId = $homeCountryId;
        $this->tourId = $tourId;
        $this->typeId = $typeId;
        $this->gameTypeId = $gameTypeId;
        $this->startTime = $startTime;
        $this->teams = $teams;
    }

    public function validate()
    {
        if(empty($this->name))
        {
            throw new BadRequestException("Invalid name");
        }

        if(empty($this->teams) || !is_array($this->teams) || count($this->teams) < 2)
        {
            throw new BadRequestException("Invalid teams");
        }

        if(empty($this->startTime))
        {
            throw new BadRequestException("Invalid start time");
        }
    }

    public static function fromPostRequest(array $request)
    {
        return new CreateRequest($request['name'], $request['homeCountryId'], $request['tourId'], $request['typeId'], $request['gameTypeId'], $request['startTime'], $request['teams']);
    }
}