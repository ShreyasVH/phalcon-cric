<?php
namespace app\models;

use Phalcon\Paginator\Adapter\Model as ModelPaginator;

use app\requests\stadiums\CreateRequest;

class Stadium extends BaseModel
{
    public $id;
    public $name;
    public $city;
    public $state;
    public $country_id;

    public function initialize()
    {
        $this->setSource('stadiums');
    }

    public static function fromRequest(CreateRequest $create_request)
    {
        $stadium = new self();

        $stadium->name = $create_request->name;
        $stadium->city = $create_request->city;
        $stadium->state = $create_request->state;
        $stadium->country_id = $create_request->countryId;

        return $stadium;
    }

    public static function findByNameAndCountryId(string $name, int $countryId)
    {
        return self::findFirst([
            'conditions' => 'name = :name: and country_id = :countryId:',
            'bind' => [
                'name' => $name,
                'countryId' => $countryId
            ]
        ]);
    }

    public static function getAll(int $page, int $limit)
    {
        $paginator = new ModelPaginator([
            'model' => Stadium::class,
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
}
