<?php


namespace app\services;


use app\exceptions\ConflictException;
use app\models\Country;
use app\repositories\CountryRepository;
use app\requests\countries\CreateRequest;

class CountryService
{
    protected CountryRepository $country_repository;

    public function __construct() {
        $this->country_repository = new CountryRepository();
    }

    public function create(CreateRequest $create_request): Country
    {
        $create_request->validate();

        $existing_country = $this->country_repository->findByName($create_request->name);
        if(null != $existing_country)
        {
            throw new ConflictException('Country');
        }
        return $this->country_repository->create($create_request);
    }

    public function searchByName(string $name)
    {
        return $this->country_repository->findByNamePattern($name);
    }

    public function getAll(int $page, int $limit)
    {
        return $this->country_repository->getAll($page, $limit);
    }

    public function getTotalCount()
    {
        return $this->country_repository->getTotalCount();
    }
}