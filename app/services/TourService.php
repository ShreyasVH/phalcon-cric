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
}