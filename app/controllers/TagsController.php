<?php


namespace app\controllers;


use app\responses\PaginatedResponse;
use app\services\TagsService;

class TagsController extends BaseController
{
    protected TagsService $tags_service;

    public function onConstruct()
    {
        $this->tags_service = new TagsService();
    }

    public function getAll()
    {
        $page = $this->request->getQuery('page', 'int', 1);
        $limit = $this->request->getQuery('limit', 'int', 25);
        $tags = $this->tags_service->getAll($page, $limit);
        $totalCount = 0;
        if ($page == 1)
        {
            $totalCount = $this->tags_service->getTotalCount();
        }
        $paginatedResponse = new PaginatedResponse($totalCount, $tags, $page, $limit);
        return $this->ok($paginatedResponse);
    }
}