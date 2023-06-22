<?php
namespace app\models;

use app\requests\teams\CreateRequest;
use Phalcon\Paginator\Adapter\Model as ModelPaginator;

class SeriesTeamsMap extends BaseModel
{
    public $id;
    public $series_id;
    public $team_id;

    public function initialize()
    {
        $this->setSource('series_teams_map');
    }

    public static function withSeriesAndTeam(int $series_id, int $team_id)
    {
        $series_teams_map = new self();

        $series_teams_map->series_id = $series_id;
        $series_teams_map->team_id = $team_id;

        return $series_teams_map;
    }

    public static function add(int $series_id, array $team_ids)
    {
        foreach($team_ids as $team_id)
        {
            $series_teams_map = self::withSeriesAndTeam($series_id, $team_id);
            $series_teams_map->save();
        }
    }

    public static function get_by_series_ids(array $series_ids)
    {
        return self::toList(self::find([
            'conditions' => 'series_id IN ({seriesIds:array})',
            'bind' => ['seriesIds' => $series_ids]
        ]));
    }
}
