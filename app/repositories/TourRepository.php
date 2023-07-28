<?php

namespace app\repositories;

use app\models\Tour;
use app\requests\tours\CreateRequest;
use Phalcon\Di\Injectable;
use Phalcon\Db\Adapter\Pdo\Mysql;

class TourRepository extends Injectable
{
    private Mysql $_db;

    public function __construct()
    {
        $this->_db = $this->getDI()->get('db');
    }

    public function create(CreateRequest $create_request)
    {
        $tour = Tour::fromRequest($create_request);
        $tour->save();

        return $tour;
    }

    public function findByNameAndStartTime(string $name, string $startTime)
    {
        return Tour::findByNameAndStartTime($name, $startTime);
    }

    public function get_by_id(int $id)
    {
        return Tour::getById($id);
    }

    public function get_by_ids(array $ids)
    {
        return Tour::getByIds($ids);
    }

    public function get_all_years(): array
    {
        $years = [];
        $query = 'SELECT DISTINCT YEAR(start_time) AS year FROM tours ORDER BY year DESC';
        $sql_query = $this->_db->query($query);
        $result = $sql_query->fetchAll();

        foreach($result as $row)
        {
            $years[] = $row['year'];
        }

        return $years;
    }
}