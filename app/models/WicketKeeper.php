<?php


namespace app\models;


class WicketKeeper extends BaseModel
{
    public $id;
    public $match_player_id;

    public function initialize()
    {
        $this->setSource('wicket_keepers');
    }

    public static function with_match_player_id(int $match_player_id)
    {
        $wicket_keeper = new self();

        $wicket_keeper->match_player_id = $match_player_id;

        return $wicket_keeper;
    }

    public static function add(array $player_ids, $player_to_match_player_map)
    {
        $wicket_keeper_list = [];
        foreach($player_ids as $player_id)
        {
            $wicket_keeper = self::with_match_player_id($player_to_match_player_map[$player_id]);
            $wicket_keeper->save();
            $wicket_keeper_list[] = $wicket_keeper;
        }

        return $wicket_keeper_list;
    }
}