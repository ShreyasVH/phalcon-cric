<?php


namespace app\services;


use app\exceptions\ConflictException;
use app\models\Tour;
use app\repositories\TourRepository;
use app\requests\tours\CreateRequest;

class TourService
{
    protected TourRepository $tour_repository;

    public function __construct() {
        $this->tour_repository = new TourRepository();
    }

    public function create(CreateRequest $create_request): Tour
    {
        $create_request->validate();

        $existing_team = $this->tour_repository->findByNameAndStartTime($create_request->name, $create_request->startTime);
        if(null != $existing_team)
        {
            throw new ConflictException('Tour');
        }
        return $this->tour_repository->create($create_request);
    }

    public function get_by_id($id)
    {
        return $this->tour_repository->get_by_id($id);
    }

    public function get_by_ids(array $ids)
    {
        return $this->tour_repository->get_by_ids($ids);
    }

    public function get_all_for_year(int $year, int $page, int $limit): array
    {
        return Tour::get_all_for_year($year, $page, $limit);
    }

    public function get_total_count_for_year(int $year): int
    {
        return Tour::get_total_count_for_year($year);
    }
}