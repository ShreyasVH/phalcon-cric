<?php
namespace app\models;

use app\requests\teams\CreateRequest;

class Team extends BaseModel
{
    public $id;
    public $name;
    public $country_id;
    public $type_id;

    public function initialize()
    {
        $this->setSource('teams');
    }

    public static function fromRequest(CreateRequest $create_request)
    {
        $team = new self();

        $team->name = $create_request->name;
        $team->country_id = $create_request->countryId;
        $team->type_id = $create_request->typeId;

        return $team;
    }

    public static function findByNameAndCountryIdAndTypeId(string $name, int $countryId, int $typeId)
    {
        return self::findFirst([
            'conditions' => 'name = :name: and country_id = :countryId: and type_id = :typeId:',
            'bind' => [
                'name' => $name,
                'countryId' => $countryId,
                'typeId' => $typeId
            ]
        ]);
    }
}
