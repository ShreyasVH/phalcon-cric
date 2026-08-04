<?php


namespace app\services;


use app\models\TagMap;

class TagMapService
{
    public function create(int $entity_id, array $tag_ids)
    {
        TagMap::add($entity_id, $tag_ids);
    }

    public function get_maps(int $entity_id, array $tag_ids)
    {
        return TagMap::get_maps($entity_id, $tag_ids);
    }

    public function remove_maps(int $entity_id, array $tag_ids)
    {
        TagMap::remove_maps($entity_id, $tag_ids);
    }
}