<?php


namespace app\services;


use app\repositories\SeriesTypeRepository;

class SeriesTypeService
{
    protected SeriesTypeRepository $series_type_repository;

    public function __construct() {
        $this->series_type_repository = new SeriesTypeRepository();
    }

    public function getById(int $id)
    {
        return $this->series_type_repository->getById($id);
    }

    public function get_by_ids(array $ids)
    {
        return $this->series_type_repository->get_by_ids($ids);
    }
}