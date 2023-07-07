<?php
namespace app\models;


class ExtrasType extends BaseModel
{
    public $id;
    public $name;

    public function initialize()
    {
        $this->setSource('extras_types');
    }

    public static function getById(int $id) : ExtrasType | null
    {
        return self::findFirst([
            'conditions' => 'id = :id:',
            'bind' => [
                'id' => $id
            ]
        ]);
    }

    public static function get_all(): array
    {
        return self::toList(self::find());
    }
}
