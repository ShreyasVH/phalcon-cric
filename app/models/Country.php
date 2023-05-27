<?php
namespace app\models;

use Phalcon\Paginator\Adapter\Model as ModelPaginator;

use app\requests\countries\CreateRequest;

class Country extends BaseModel
{
    public $id;
    public $name;

    public function initialize()
    {
        $this->setSource('countries');
    }

    public static function fromRequest(CreateRequest $create_request)
    {
        $country = new self();

        $country->name = $create_request->name;

        return $country;
    }

    public static function findByName(string $name)
    {
        $country = self::findFirst([
            'conditions' => 'name = :name:',
            'bind' => [
                'name' => $name
            ]
        ]);

        return $country;
    }

    public static function findByNamePattern(string $name)
    {
        $countries = self::find([
            'conditions' => 'name LIKE :name:',
            'bind' => [
                'name' => '%' . $name . '%'
            ]
        ]);

        return self::toList($countries);
    }

    public static function getAll(int $page, int $limit)
    {
        $paginator = new ModelPaginator([
            'model' => Country::class,
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

    public static function getById(int $id) : Country | null
    {
        return self::findFirst([
            'conditions' => 'id LIKE :id:',
            'bind' => [
                'id' => $id
            ]
        ]);
    }
}
