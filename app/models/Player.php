<?php
namespace app\models;

use app\requests\players\CreateRequest;
use Phalcon\Paginator\Adapter\Model as ModelPaginator;

class Player extends BaseModel
{
    public $id;
    public $name;
    public $country_id;
    public $date_of_birth;
    public $image;

    public function initialize()
    {
        $this->setSource('players');
    }

    public static function fromRequest(CreateRequest $create_request)
    {
        $player = new self();

        $player->name = $create_request->name;
        $player->country_id = $create_request->countryId;
        $player->date_of_birth = $create_request->dateOfBirth;
        $player->image = $create_request->image;

        return $player;
    }

    public static function findByNameAndCountryIdAndDateOfBirth(string $name, int $countryId, $dateOfBirth)
    {
        return self::findFirst([
            'conditions' => 'name = :name: and country_id = :countryId: and date_of_birth = :dateOfBirth:',
            'bind' => [
                'name' => $name,
                'countryId' => $countryId,
                'dateOfBirth' => $dateOfBirth
            ]
        ]);
    }

    public static function getAll(int $page, int $limit)
    {
        $paginator = new ModelPaginator([
            'model' => Player::class,
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
