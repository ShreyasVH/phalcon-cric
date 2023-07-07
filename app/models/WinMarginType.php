<?php
namespace app\models;


class WinMarginType extends BaseModel
{
    public $id;
    public $name;

    public function initialize()
    {
        $this->setSource('win_margin_types');
    }

    public static function getById(int $id) : WinMarginType | null
    {
        return self::findFirst([
            'conditions' => 'id = :id:',
            'bind' => [
                'id' => $id
            ]
        ]);
    }
}
