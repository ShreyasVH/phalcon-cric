<?php

namespace app\requests\teams;

use app\exceptions\BadRequestException;

class CreateRequest
{
    public ?string $name;
    public ?int $countryId;
    public ?int $typeId;

    public function __construct($name, $countryId, $typeId)
    {
        $this->name = $name;
        $this->countryId = $countryId;
        $this->typeId = $typeId;
    }

    public function validate()
    {
        if(empty($this->name))
        {
            throw new BadRequestException("Invalid name");
        }
    }

    public static function fromPostRequest(array $request)
    {
        return new CreateRequest($request['name'], $request['countryId'], $request['typeId']);
    }
}