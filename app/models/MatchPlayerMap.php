<?php


namespace app\models;


class MatchPlayerMap extends BaseModel
{
    public $id;
    public $match_id;
    public $player_id;
    public $team_id;

    public function initialize()
    {
        $this->setSource('match_player_map');
    }

    public static function with_data(int $match_id, int $player_id, int $team_id)
    {
        $match_player_map = new self();

        $match_player_map->match_id = $match_id;
        $match_player_map->player_id = $player_id;
        $match_player_map->team_id = $team_id;

        return $match_player_map;
    }

    public static function add(int $match_id, array $player_ids, $player_team_map)
    {
        $match_player_maps = [];
        foreach($player_ids as $player_id)
        {
            $match_player_map = MatchPlayerMap::with_data($match_id, $player_id, $player_team_map[$player_id]);
            $match_player_map->save();
            $match_player_maps[] = $match_player_map;
        }

        return $match_player_maps;
    }

    /**
     * @param int $match_id
     * @return MatchPlayerMap[]
     */
    public static function get_by_match_id(int $match_id): array
    {
        return self::toList(self::find([
            'conditions' => 'match_id = :matchId:',
            'bind' => ['matchId' => $match_id]
        ]));
    }

    /**
     * @param int $match_id
     */
    public static function remove(int $match_id)
    {
        foreach(self::get_by_match_id($match_id) as $match_player_map)
        {
            $match_player_map->delete();
        }
    }
}