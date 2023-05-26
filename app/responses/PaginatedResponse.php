<?php

namespace app\responses;

class PaginatedResponse
{
    public int $totalCount;
    public array $items;
    public int $page;
    public int $limit;

    public function __construct(int $totalCount, array $items, int $page, int $limit)
    {
        $this->totalCount = $totalCount;
        $this->items = $items;
        $this->page = $page;
        $this->limit = $limit;
    }
}