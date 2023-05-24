<?php
namespace app\models;

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
//        var_dump($countries);die;

//        return $countries->toArray(self::class);
//        $countryObjects = [];
//        foreach ($countries as $country) {
//            $countryObjects[] = $country;
//        }

        return self::toList($countries);
    }
}
