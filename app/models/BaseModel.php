<?php


namespace app\models;


use Phalcon\Mvc\Model;

class BaseModel extends Model
{
    public static function toList(Model\ResultsetInterface $result)
    {
        $list = [];

        foreach($result as $item)
        {
            $list[] = $item;
        }

        return $list;
    }
}