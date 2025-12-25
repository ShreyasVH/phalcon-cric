<?php
namespace app\requests;

class FilterRequest
{
    public $type;
    public $offset;
    public $count;
    public $filters;
    public $rangeFilters;
    public $sortMap;

    public function __construct(array $request)
    {
        $this->type = $request['type'];
        $this->offset = ((array_key_exists('offset', $request)) ? $request['offset'] : 0);
        $this->count = ((array_key_exists('count', $request)) ? $request['count'] : 30);
        $this->filters = ((array_key_exists('filters', $request)) ? $request['filters'] : []);
        $this->rangeFilters = ((array_key_exists('rangeFilters', $request)) ? $request['rangeFilters'] : []);
        $this->sortMap = ((array_key_exists('sortMap', $request)) ? $request['sortMap'] : []);
    }
}