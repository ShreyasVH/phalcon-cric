<?php


namespace app\models;


use Phalcon\Mvc\Model;

class BaseModel extends Model
{
    public static function toList($result): array
    {
        $list = [];

        foreach($result as $item)
        {
            $list[] = $item;
        }

        return $list;
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

    public static function getByIds(array $ids) : array
    {
        $entities = [];

        if(!empty($ids))
        {
            $entities = self::toList(self::find([
                'conditions' => 'id IN ({ids:array})',
                'bind' => ['ids' => $ids]
            ]));
        }

        return $entities;
    }

    public static function get_by_ids(array $ids) : array
    {
        return self::getByIds($ids);
    }
}