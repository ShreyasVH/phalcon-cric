<?php

namespace app\requests\players;

class MergeRequest
{
    public ?int $playerIdToMerge;
    public ?int $originalPlayerId;

    public function __construct($playerIdToMerge, $originalPlayerId)
    {
        $this->playerIdToMerge = $playerIdToMerge;
        $this->originalPlayerId = $originalPlayerId;
    }

    public static function fromPostRequest(array $request)
    {
        return new MergeRequest($request['playerIdToMerge'], $request['originalPlayerId']);
    }
}