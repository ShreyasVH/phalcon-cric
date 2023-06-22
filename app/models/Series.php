<?php
namespace app\models;

use app\requests\series\CreateRequest;
use Phalcon\Paginator\Adapter\Model as ModelPaginator;

class Series extends BaseModel
{
    public $id;
    public $name;
    public $home_country_id;
    public $tour_id;
    public $type_id;
    public $game_type_id;
    public $start_time;

    public function initialize()
    {
        $this->setSource('series');
    }

    public static function fromRequest(CreateRequest $create_request)
    {
        $series = new self();

        $series->name = $create_request->name;
        $series->home_country_id = $create_request->homeCountryId;
        $series->tour_id = $create_request->tourId;
        $series->type_id = $create_request->typeId;
        $series->game_type_id = $create_request->gameTypeId;
        $series->start_time = $create_request->startTime;

        return $series;
    }

    public static function findByNameAndTourIdAndGameTypeId(string $name, int $tourId, int $gameTypeId)
    {
        return self::findFirst([
            'conditions' => 'name = :name: and tour_id = :tourId: and game_type_id = :gameTypeId:',
            'bind' => [
                'name' => $name,
                'tourId' => $tourId,
                'gameTypeId' => $gameTypeId
            ]
        ]);
    }
}
