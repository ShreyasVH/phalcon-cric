<?php


namespace app\models;


class ManOfTheMatch extends BaseModel
{
    public $id;
    public $match_player_id;

    public function initialize()
    {
        $this->setSource('man_of_the_match');
    }

    public static function with_match_player_id(int $match_player_id)
    {
        $man_of_the_match = new self();

        $man_of_the_match->match_player_id = $match_player_id;

        return $man_of_the_match;
    }

    public static function add(array $player_ids, $player_to_match_player_map)
    {
        $man_of_the_match_list = [];
        foreach($player_ids as $player_id)
        {
            $man_of_the_match = self::with_match_player_id($player_to_match_player_map[$player_id]);
            $man_of_the_match->save();
            $man_of_the_match_list[] = $man_of_the_match;
        }

        return $man_of_the_match_list;
    }
}