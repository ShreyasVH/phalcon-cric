<?php

namespace app\responses;

use app\models\ResultType;

class ResultTypeResponse
{
    public int $id;
    public string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function from_result_type(ResultType $result_type)
    {
        return new ResultTypeResponse($result_type->id, $result_type->name);
    }
}