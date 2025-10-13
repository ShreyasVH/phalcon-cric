<?php
namespace app\models;


class TagMap extends BaseModel
{
    public $id;
    public $entity_type;
    public $entity_id;
    public $tag_id;

    public function initialize()
    {
        $this->setSource('tags_map');
    }

    public static function add(int $entity_id, array $tag_ids, string $tag_entity_type)
    {
        foreach($tag_ids as $tag_id)
        {
            $tag_map = new TagMap();
            $tag_map->entity_type = $tag_entity_type;
            $tag_map->entity_id = $entity_id;
            $tag_map->tag_id = $tag_id;

            $tag_map->save();
        }
    }
}
