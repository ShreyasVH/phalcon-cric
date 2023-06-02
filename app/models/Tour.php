<?php
namespace app\models;

use Phalcon\Paginator\Adapter\Model as ModelPaginator;

use app\requests\tours\CreateRequest;

class Tour extends BaseModel
{
    public $id;
    public $name;
    public $start_time;

    public function initialize()
    {
        $this->setSource('tours');
    }

    public static function fromRequest(CreateRequest $create_request)
    {
        $tour = new self();

        $tour->name = $create_request->name;
        $tour->start_time = $create_request->startTime;

        return $tour;
    }

    public static function findByNameAndStartTime(string $name, $startTime)
    {
        $tour = self::findFirst([
            'conditions' => 'name = :name: and start_time = :startTime:',
            'bind' => [
                'name' => $name,
                'startTime' => $startTime
            ]
        ]);

        return $tour;
    }
}
