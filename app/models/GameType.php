<?php
namespace app\models;


class GameType extends BaseModel
{
    public $id;
    public $name;

    public function initialize()
    {
        $this->setSource('game_types');
    }

    public static function getById(int $id)
    {
        return self::findFirst([
            'conditions' => 'id = :id:',
            'bind' => [
                'id' => $id
            ]
        ]);
    }
}
