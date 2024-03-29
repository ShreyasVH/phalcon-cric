<?php

namespace app\controllers;

use app\responses\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Http\Response as ResponseEntity;

class BaseController extends Controller
{
    public function status($data, $code, $description)
    {
        $responseEntity = $this->response;
        $responseEntity->setStatusCode($code, $description);
        $responseEntity->setJsonContent(Response::withData($data));
        return $responseEntity;
    }

    public function status_with_message($message, $code, $description)
    {
        $responseEntity = $this->response;
        $responseEntity->setStatusCode($code, $description);
        $responseEntity->setJsonContent(Response::withMessageAndSuccess($message, true));
        return $responseEntity;
    }

    public function created($data)
    {
        return $this->status($data, 201, 'Created');
    }

    public function ok($data)
    {
        return $this->status($data, 200, 'OK');
    }

    public function ok_with_message($message)
    {
        return $this->status_with_message($message, 200, 'OK');
    }
}