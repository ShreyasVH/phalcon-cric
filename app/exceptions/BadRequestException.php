<?php


namespace app\exceptions;


class BadRequestException extends MyException
{
    public int $http_status_code = 400;
    public string $description;

    public function __construct($description)
    {
        $this->description = $description;
    }
}