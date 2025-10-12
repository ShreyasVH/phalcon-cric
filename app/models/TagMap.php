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
}
