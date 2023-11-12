<?php


namespace app\services;


use app\exceptions\ConflictException;
use app\models\Game;
use app\requests\matches\CreateRequest;

class MatchService
{
    public function create(CreateRequest $create_request)
    {
        $create_request->validate();

        $existing_game = Game::get_by_stadium_and_start_time($create_request->stadiumId, $create_request->startTime);
        if(null != $existing_game)
        {
            throw new ConflictException('Match');
        }

        $match = Game::from_request($create_request);
        $match->save();
        return $match;
    }

    /**
     * @param int $id
     */
    public function get_by_id(int $id)
    {
        return Game::get_by_id($id);
    }
}