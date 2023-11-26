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

    /**
     * @param int[] $match_player_ids
     * @return WicketKeeper[]
     */
    public static function get_by_match_player_ids(array $match_player_ids): array
    {
        $wicket_keepers = [];

        if(!empty($match_player_ids))
        {
            $wicket_keepers = self::toList(self::find([
                'conditions' => 'match_player_id IN ({matchPlayerIds:array})',
                'bind' => ['matchPlayerIds' => $match_player_ids]
            ]));
        }

        return $wicket_keepers;
    }

    /**
     * @param int[] $match_player_ids
     */
    public static function remove(array $match_player_ids)
    {
        foreach(self::get_by_match_player_ids($match_player_ids) as $wicket_keeper)
        {
            $wicket_keeper->delete();
        }
    }
}