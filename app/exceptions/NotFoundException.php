<?php


namespace app\exceptions;


class NotFoundException extends MyException
{
    public int $http_status_code = 404;
    public string $description;

    public function __construct($entity = "")
    {
        $this->description = $entity . ' not found';
    }
}