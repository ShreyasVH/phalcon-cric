<?php
namespace app\models;


class TagMap extends BaseModel
{
    public $id;
    public $entity_id;
    public $tag_id;

    public function initialize()
    {
        $this->setSource('tags_map');
    }

    public static function add(int $entity_id, array $tag_ids)
    {
        foreach($tag_ids as $tag_id)
        {
            $tag_map = new TagMap();
            $tag_map->entity_id = $entity_id;
            $tag_map->tag_id = $tag_id;

            $tag_map->save();
        }
    }

    public static function get_maps(int $entity_id, array $tag_ids)
    {
        return self::toList(self::find([
            'conditions' => 'entity_id = :entity_id: AND tag_id IN ({tag_ids:array})',
            'bind' => [
                'entity_id' => $entity_id,
                'tag_ids' => $tag_ids
            ]
        ]));
    }

    public static function remove_maps(int $entity_id, array $tag_ids)
    {
        foreach(self::get_maps($entity_id, $tag_ids) as $tag_map)
        {
            $tag_map->delete();
        }
    }
}
