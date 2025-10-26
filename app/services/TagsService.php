<?php


namespace app\services;


use app\models\Tag;

class TagsService
{
    public function getAll(int $page, int $limit): array
    {
        return Tag::getAll($page, $limit);
    }

    public function getTotalCount(): int
    {
        return Tag::getTotalCount();
    }

    public function get_by_ids(array $ids): array
    {
        return Tag::getByIds($ids);
    }
}