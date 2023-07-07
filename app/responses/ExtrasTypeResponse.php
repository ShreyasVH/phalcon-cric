<?php


namespace app\responses;


use app\models\ExtrasType;

class ExtrasTypeResponse
{
    public int $id;
    public string $name;

    public function __construct(ExtrasType $extras_type)
    {
        $this->id = $extras_type->id;
        $this->name = $extras_type->name;
    }
}