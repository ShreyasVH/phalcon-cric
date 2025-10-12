<?php


namespace app\services;


use app\models\Tag;

class TagsService
{
    public function getAll(int $page, int $limit)
    {
        return Tag::getAll($page, $limit);
    }

    public function getTotalCount()
    {
        return Tag::getTotalCount();
    }
}