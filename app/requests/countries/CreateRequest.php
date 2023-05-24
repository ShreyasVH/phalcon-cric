<?php


namespace app\requests\countries;


use app\exceptions\BadRequestException;

class CreateRequest
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
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
        return new CreateRequest($request['name']);
    }
}