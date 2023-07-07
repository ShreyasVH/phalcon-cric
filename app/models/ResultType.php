<?php
namespace app\models;


class ResultType extends BaseModel
{
    public $id;
    public $name;

    public function initialize()
    {
        $this->setSource('result_types');
    }

    public static function getById(int $id) : ResultType | null
    {
        return self::findFirst([
            'conditions' => 'id = :id:',
            'bind' => [
                'id' => $id
            ]
        ]);
    }
}
