<?php

namespace app\requests\stadiums;

use app\exceptions\BadRequestException;

class CreateRequest
{
    public ?string $name;
    public ?string $city;
    public ?string $state;
    public ?int $countryId;

    public function __construct($name, $city, $state, $countryId)
    {
        $this->name = $name;
        $this->city = $city;
        $this->state = $state;
        $this->countryId = $countryId;
    }

    public function validate()
    {
        if(empty($this->name))
        {
            throw new BadRequestException("Invalid name");
        }

        if(empty($this->city))
        {
            throw new BadRequestException("Invalid city");
        }
    }

    public static function fromPostRequest(array $request)
    {
        return new CreateRequest($request['name'], $request['city'], $request['state'], $request['countryId']);
    }
}