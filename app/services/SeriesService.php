<?php


namespace app\services;


use app\exceptions\BadRequestException;
use app\exceptions\ConflictException;
use app\models\Series;
use app\repositories\SeriesRepository;
use app\requests\series\CreateRequest;

class SeriesService
{
    protected SeriesRepository $series_repository;

    public function __construct() {
        $this->series_repository = new SeriesRepository();
    }

    /**
     * @throws ConflictException
     * @throws BadRequestException
     */
    public function create(CreateRequest $create_request): Series
    {
        $create_request->validate();

        $existing_series = $this->series_repository->findByNameAndTourIdAndGameTypeId($create_request->name, $create_request->tourId, $create_request->gameTypeId);
        if(null != $existing_series)
        {
            throw new ConflictException('Series');
        }
        return $this->series_repository->create($create_request);
    }
}