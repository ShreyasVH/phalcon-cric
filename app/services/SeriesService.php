<?php


namespace app\services;


use app\exceptions\BadRequestException;
use app\exceptions\ConflictException;
use app\models\Series;
use app\repositories\SeriesRepository;
use app\requests\series\CreateRequest;
use app\requests\series\UpdateRequest;

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

    public function getAll(int $page, int $limit)
    {
        return $this->series_repository->getAll($page, $limit);
    }

    public function getTotalCount()
    {
        return $this->series_repository->getTotalCount();
    }

    public function get_by_id(int $id)
    {
        return Series::getById($id);
    }

    public function update(Series $existing_series, UpdateRequest $update_request)
    {
        $is_update_required = false;

        if(!empty($update_request->name) && $update_request->name != $existing_series->name)
        {
            $is_update_required = true;
            $existing_series->name = $update_request->name;
        }

        if(!empty($update_request->homeCountryId) && $update_request->homeCountryId != $existing_series->home_country_id)
        {
            $is_update_required = true;
            $existing_series->home_country_id = $update_request->homeCountryId;
        }

        if(!empty($update_request->tourId) && $update_request->tourId != $existing_series->tour_id)
        {
            $is_update_required = true;
            $existing_series->tour_id = $update_request->tourId;
        }

        if(!empty($update_request->typeId) && $update_request->typeId != $existing_series->type_id)
        {
            $is_update_required = true;
            $existing_series->type_id = $update_request->typeId;
        }

        if(!empty($update_request->gameTypeId) && $update_request->gameTypeId != $existing_series->game_type_id)
        {
            $is_update_required = true;
            $existing_series->game_type_id = $update_request->gameTypeId;
        }

        if(!empty($update_request->startTime) && $update_request->startTime != $existing_series->start_time)
        {
            $is_update_required = true;
            $existing_series->start_time = $update_request->startTime;
        }

        if($is_update_required)
        {
            $existing_series->save();
        }

        return $existing_series;
    }
}