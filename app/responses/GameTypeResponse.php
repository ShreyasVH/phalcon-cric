<?php

namespace app\responses;

use app\models\GameType;

class GameTypeResponse
{
    public int $id;
    public string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function from_game_type(GameType $game_type): GameTypeResponse
    {
        return new GameTypeResponse($game_type->id, $game_type->name);
    }
}