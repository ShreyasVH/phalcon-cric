<?php


namespace app\models;


use app\requests\matches\PartnershipRequest;

class Partnership extends BaseModel
{
    public $id;
    public $innings;
    public $wicket;
    public $runs;
    public $balls;
    public $ended;
    public $match_player_id_1;
    public $runs_1;
    public $balls_1;
    public $match_player_id_2;
    public $runs_2;
    public $balls_2;
    public $primary_entry;

    public function initialize()
    {
        $this->setSource('partnerships');
    }

    public static function with_partnership_request(PartnershipRequest $partnership_request, $player_to_match_player_map, $primary)
    {
        $partnership = new self();

        $partnership->innings = $partnership_request->innings;
        $partnership->wicket = $partnership_request->wicket;
        $partnership->runs = $partnership_request->runs;
        $partnership->balls = $partnership_request->balls;
        $partnership->ended = $partnership_request->ended ? 1 : 0;
        if ($primary)
        {
            $partnership->match_player_id_1 = $player_to_match_player_map[$partnership_request->playerId1];
            $partnership->runs_1 = $partnership_request->runs1;
            $partnership->balls_1 = $partnership_request->balls1;

            $partnership->match_player_id_2 = $player_to_match_player_map[$partnership_request->playerId2];
            $partnership->runs_2 = $partnership_request->runs2;
            $partnership->balls_2 = $partnership_request->balls2;
        }
        else
        {
            $partnership->match_player_id_1 = $player_to_match_player_map[$partnership_request->playerId2];
            $partnership->runs_1 = $partnership_request->runs2;
            $partnership->balls_1 = $partnership_request->balls2;

            $partnership->match_player_id_2 = $player_to_match_player_map[$partnership_request->playerId1];
            $partnership->runs_2 = $partnership_request->runs1;
            $partnership->balls_2 = $partnership_request->balls1;
        }


        $partnership->primary_entry = $primary ? 1 : 0;

        return $partnership;
    }

    public static function add(array $partnership_requests, $player_to_match_player_map)
    {
        $partnerships = [];

        /** @var PartnershipRequest $partnership_request */
        foreach($partnership_requests as $partnership_request)
        {
            $partnership_1 = Partnership::with_partnership_request($partnership_request, $player_to_match_player_map, true);
            $partnership_1->save();
            $partnerships[] = $partnership_1;
            $partnership_2 = Partnership::with_partnership_request($partnership_request, $player_to_match_player_map, false);
            $partnership_2->save();
            $partnerships[] = $partnership_2;
        }

        return $partnerships;
    }

    /**
     * @param int[] $match_player_ids
     * @return BowlingFigure[]
     */
    public static function get_by_match_player_ids_all(array $match_player_ids): array
    {
        $partnerships = [];

        if(!empty($match_player_ids))
        {
            $partnerships = self::toList(self::find([
                'conditions' => 'match_player_id_1 IN ({matchPlayerIds:array}) OR match_player_id_2 IN ({matchPlayerIds:array})',
                'bind' => ['matchPlayerIds' => $match_player_ids]
            ]));
        }

        return $partnerships;
    }

    /**
     * @param int[] $match_player_ids
     */
    public static function remove(array $match_player_ids)
    {
        foreach(self::get_by_match_player_ids_all($match_player_ids) as $partnership)
        {
            $partnership->delete();
        }
    }
}