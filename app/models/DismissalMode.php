<?php
namespace app\models;


class DismissalMode extends BaseModel
{
    public $id;
    public $name;

    public function initialize()
    {
        $this->setSource('dismissal_modes');
    }

    public static function getById(int $id) : DismissalMode | null
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
