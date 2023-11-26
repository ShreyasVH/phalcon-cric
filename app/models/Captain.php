<?php


namespace app\models;


class Captain extends BaseModel
{
    public $id;
    public $match_player_id;

    public function initialize()
    {
        $this->setSource('captains');
    }

    public static function with_match_player_id(int $match_player_id)
    {
        $captain = new self();

        $captain->match_player_id = $match_player_id;

        return $captain;
    }

    public static function add(array $player_ids, $player_to_match_player_map)
    {
        $captain_list = [];
        foreach($player_ids as $player_id)
        {
            $captain = self::with_match_player_id($player_to_match_player_map[$player_id]);
            $captain->save();
            $captain_list[] = $captain;
        }

        return $captain_list;
    }

    /**
     * @param int[] $match_player_ids
     * @return Captain[]
     */
    public static function get_by_match_player_ids(array $match_player_ids): array
    {
        $captains = [];

        if(!empty($match_player_ids))
        {
            $captains = self::toList(self::find([
                'conditions' => 'match_player_id IN ({matchPlayerIds:array})',
                'bind' => ['matchPlayerIds' => $match_player_ids]
            ]));
        }

        return $captains;
    }

    /**
     * @param int[] $match_player_ids
     */
    public static function remove(array $match_player_ids)
    {
        foreach(self::get_by_match_player_ids($match_player_ids) as $captain)
        {
            $captain->delete();
        }
    }
}