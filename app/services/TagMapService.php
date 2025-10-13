<?php


namespace app\services;


use app\models\TagMap;

class TagMapService
{
    public function create(int $entity_id, array $tag_ids, string $tag_entity_type)
    {
        TagMap::add($entity_id, $tag_ids, $tag_entity_type);
    }
}