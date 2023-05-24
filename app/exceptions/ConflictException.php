<?php


namespace app\exceptions;


class ConflictException extends MyException
{
    public int $http_status_code = 409;
    public string $description;

    public function __construct($entity = "")
    {
        $this->description = $entity . ' already exists';
    }
}