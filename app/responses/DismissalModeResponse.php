<?php


namespace app\responses;


use app\models\DismissalMode;

class DismissalModeResponse
{
    public int $id;
    public string $name;

    public function __construct(DismissalMode $dismissal_mode)
    {
        $this->id = $dismissal_mode->id;
        $this->name = $dismissal_mode->name;
    }
}