<?php


namespace app\models;


class FielderDismissal extends BaseModel
{
    public $id;
    public $score_id;
    public $match_player_id;

    public function initialize()
    {
        $this->setSource('fielder_dismissals');
    }

    public static function with_data(int $score_id, int $match_player_id)
    {
        $fielder_dismissal = new self();

        $fielder_dismissal->score_id = $score_id;
        $fielder_dismissal->match_player_id = $match_player_id;

        return $fielder_dismissal;
    }

    public static function add($score_fielder_maps, $player_to_match_player_map)
    {
        $fielder_dismissals = [];

        foreach($score_fielder_maps as $score_id => $fielder_ids)
        {
            foreach($fielder_ids as $fielder_id)
            {
                $fielder_dismissal = FielderDismissal::with_data($score_id, $player_to_match_player_map[$fielder_id]);
                $fielder_dismissal->save();
                $fielder_dismissals[] = $fielder_dismissal;
            }
        }

        return $fielder_dismissals;
    }

    /**
     * @param int[] $match_player_ids
     * @return FielderDismissal[]
     */
    public static function get_by_match_player_ids(array $match_player_ids): array
    {
        return self::toList(self::find([
            'conditions' => 'match_player_id in ({matchPlayerIds:array})',
            'bind' => ['matchPlayerIds' => $match_player_ids]
        ]));
    }
}