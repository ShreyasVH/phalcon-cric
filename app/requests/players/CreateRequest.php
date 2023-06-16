<?php

namespace app\requests\players;

use app\exceptions\BadRequestException;

class CreateRequest
{
    public ?string $name;
    public ?int $countryId;
    public $dateOfBirth;
    public ?string $image;

    public function __construct($name, $countryId, $dateOfBirth, $image)
    {
        $this->name = $name;
        $this->countryId = $countryId;
        $this->dateOfBirth = $dateOfBirth;
        $this->image = $image;
    }

    public function validate()
    {
        if(empty($this->name))
        {
            throw new BadRequestException("Invalid name");
        }

        if(empty($this->dateOfBirth))
        {
            throw new BadRequestException("Invalid date of birth");
        }
    }

    public static function fromPostRequest(array $request)
    {
        return new CreateRequest($request['name'], $request['countryId'], $request['dateOfBirth'], $request['image']);
    }
}