<?php
namespace app\models;

use DateTime;
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

    public static function get_all_for_year(int $year, int $page, int $limit)
    {
        $startTime = (DateTime::createFromFormat('Y-m-d', $year . '-01-01'))
            ->setTime(0, 0, 0)
            ->format('Y-m-d H:i:s');
        $endTime = (DateTime::createFromFormat('Y-m-d', $year . '-12-31'))
            ->setTime(23, 59, 59)
            ->format('Y-m-d H:i:s');

//        var_dump($startTime);
//        var_dump($endTime);

        $paginator = new ModelPaginator([
            'model' => Tour::class,
            'parameters' => [
                'start_time >= :startTime: and start_time <= :endTime:',
                'bind' => [
                    'startTime' => $startTime,
                    'endTime' => $endTime
                ],
                'order' => 'start_time ASC',
            ],
            'limit' => $limit,
            'page' => $page,
        ]);

        return self::toList($paginator->paginate()->getItems());
    }

    public static function get_total_count_for_year(int $year) : int
    {
        $startTime = (DateTime::createFromFormat('Y-m-d', $year . '-01-01'))
            ->setTime(0, 0, 0)
            ->format('Y-m-d H:i:s');
        $endTime = (DateTime::createFromFormat('Y-m-d', $year . '-12-31'))
            ->setTime(23, 59, 59)
            ->format('Y-m-d H:i:s');
        return self::count([
            'conditions' => 'start_time >= :startTime: and start_time <= :endTime:',
            'bind' => [
                'startTime' => $startTime,
                'endTime' => $endTime
            ]
        ]);
    }
}
