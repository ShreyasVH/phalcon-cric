<?php
namespace app\models;


use Phalcon\Paginator\Adapter\Model as ModelPaginator;

class Tag extends BaseModel
{
    public $id;
    public $name;
    public $type;

    public function initialize()
    {
        $this->setSource('tags');
    }

    public static function getAll(int $page, int $limit)
    {
        $paginator = new ModelPaginator([
            'model' => Tag::class,
            'parameters' => [
                'order' => 'name ASC',
            ],
            'limit' => $limit,
            'page' => $page,
        ]);

        return self::toList($paginator->paginate()->getItems());
    }

    public static function getTotalCount() : int
    {
        return self::count();
    }

    public static function get_by_type(string $type) : array
    {
        return self::toList(self::find([
            'conditions' => 'type = :type:',
            'bind' => ['type' => $type]
        ]));
    }
}
