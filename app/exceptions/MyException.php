<?php


namespace app\exceptions;


class MyException extends \Exception
{
    public int $http_status_code = 500;
    public string $description;

    public function __construct($message = "")
    {
        $this->description = $message;
    }
}