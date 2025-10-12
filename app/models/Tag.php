<?php
namespace app\models;


use Phalcon\Paginator\Adapter\Model as ModelPaginator;

class Tag extends BaseModel
{
    public $id;
    public $name;

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
}
