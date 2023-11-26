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

    public static function getAll(int $page, int $limit): array
    {
        $paginator = new ModelPaginator([
            'model' => self::class,
            'parameters' => [
                'order' => 'name ASC',
            ],
            'limit' => $limit,
            'page' => $page,
        ]);

        return self::toList($paginator->paginate()->getItems());
    }

    public static function getTotalCount() : int
    {
        return self::count();
    }

    public static function get_by_tour_id(int $tour_id): array
    {
        return self::toList(self::find([
            'conditions' => 'tour_id = :tourId:',
            'bind' => [
                'tourId' => $tour_id
            ],
            'order' => 'start_time DESC'
        ]));
    }

    /**
     * @param int $id
     */
    public static function remove(int $id)
    {
        (self::get_by_id($id))->delete();
    }
}
