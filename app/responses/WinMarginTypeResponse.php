<?php

namespace app\responses;

use app\models\WinMarginType;

class WinMarginTypeResponse
{
    public int $id;
    public string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function from_win_margin_type(WinMarginType $win_margin_type)
    {
        return new WinMarginTypeResponse($win_margin_type->id, $win_margin_type->name);
    }
}