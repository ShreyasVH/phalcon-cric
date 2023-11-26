<?php


namespace app\responses;


class Response
{
    public bool $success;
    public $data;
    public string $message;

    public function __construct($data, $success, $message)
    {
        $this->data = $data;
        $this->success = $success;
        $this->message = $message;
    }

    public static function withMessage(string $message)
    {
        return self::withMessageAndSuccess($message, false);
    }

    public static function withMessageAndSuccess(string $message, bool $success)
    {
        return new Response(null, $success, $message);
    }

    public static function withData($data)
    {
        return new Response($data, true, "");
    }
}