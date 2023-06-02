<?php
namespace app\models;


class TeamType extends BaseModel
{
    public $id;
    public $name;

    public function initialize()
    {
        $this->setSource('team_types');
    }

    public static function getById(int $id) : TeamType | null
    {
        return self::findFirst([
            'conditions' => 'id = :id:',
            'bind' => [
                'id' => $id
            ]
        ]);
    }

    public static function getByIds(array $ids) : array
    {
        return self::toList(self::find([
            'conditions' => 'id IN ({ids:array})',
            'bind' => ['ids' => $ids]
        ]));
    }
}
