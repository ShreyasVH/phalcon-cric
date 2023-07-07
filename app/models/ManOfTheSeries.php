<?php
namespace app\models;

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

    public static function get_by_series_ids(array $series_ids)
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

    public static function remove(int $series_id, array $player_ids)
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
}
