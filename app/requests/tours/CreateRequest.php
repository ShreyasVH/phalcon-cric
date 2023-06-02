<?php


namespace app\requests\tours;


use app\exceptions\BadRequestException;

class CreateRequest
{
    public $name;
    public $startTime;

    public function __construct($name, $startTime)
    {
        $this->name = $name;
        $this->startTime = $startTime;
    }

    public function validate()
    {
        if(empty($this->name))
        {
            throw new BadRequestException("Invalid name");
        }

        if(empty($this->startTime))
        {
            throw new BadRequestException("Invalid start time");
        }
    }

    public static function fromPostRequest(array $request)
    {
        return new CreateRequest($request['name'], $request['startTime']);
    }
}