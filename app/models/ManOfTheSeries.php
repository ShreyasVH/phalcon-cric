<?php
namespace app\models;

use app\requests\players\MergeRequest;

class ManOfTheSeries extends BaseModel
{
    public $id;
    public $series_id;
    public $player_id;

    public function initialize()
    {
        $this->setSource('man_of_the_series');
    }

    public static function withSeriesAndPlayer(int $series_id, int $player_id)
    {
        $man_of_the_series = new self();

        $man_of_the_series->series_id = $series_id;
        $man_of_the_series->player_id = $player_id;

        return $man_of_the_series;
    }

    public static function add(int $series_id, array $player_ids)
    {
        foreach($player_ids as $player_id)
        {
            $man_of_the_series = self::withSeriesAndPlayer($series_id, $player_id);
            $man_of_the_series->save();
        }
    }

    /**
     * @param int[] $series_ids
     * @return ManOfTheSeries[]
     */
    public static function get_by_series_ids(array $series_ids): array
    {
        $man_of_the_series_list = [];

        if(!empty($series_ids))
        {
            $man_of_the_series_list = self::toList(self::find([
                'conditions' => 'series_id IN ({seriesIds:array})',
                'bind' => ['seriesIds' => $series_ids]
            ]));
        }
        return $man_of_the_series_list;
    }

    public static function remove_players(int $series_id, array $player_ids)
    {
        if(!empty($player_ids))
        {
            $man_of_the_series_list = self::find([
                'conditions' => 'series_id = :seriesId: and player_id IN ({playerIds:array})',
                'bind' => [
                    'seriesId' => $series_id,
                    'playerIds' => $player_ids
                ]
            ]);

            foreach($man_of_the_series_list as $man_of_the_series)
            {
                $man_of_the_series->delete();
            }
        }
    }

    public static function get_by_player_id(int $player_id): array
    {
        return self::toList(self::find([
            'conditions' => 'player_id = :playerId:',
            'bind' => ['playerId' => $player_id]
        ]));
    }

    /**
     * @param int $series_id
     */
    public static function remove(int $series_id)
    {
        foreach(self::get_by_series_ids([$series_id]) as $mots)
        {
            $mots->delete();
        }
    }

    public static function merge(MergeRequest $mergeRequest)
    {
        /** @var ManOfTheSeries $man_of_the_series */
        foreach(self::get_by_player_id($mergeRequest->playerIdToMerge) as $man_of_the_series)
        {
            $man_of_the_series->player_id = $mergeRequest->originalPlayerId;
            $man_of_the_series->save();
        }
    }
}
