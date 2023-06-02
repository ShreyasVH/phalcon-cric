<?php

use app\exceptions\MyException;
use Phalcon\Mvc\Micro\Collection;
use app\responses\Response;
use app\middlewares\CorsMiddleware;

$application->before(new CorsMiddleware());

$countries = new Collection();
$countries->setHandler('app\controllers\CountryController', true);

$countries->post('/cric/v1/countries', 'create');
$countries->get('/cric/v1/countries/name/{name:[a-zA-Z]+}', 'searchByName');
$countries->get('/cric/v1/countries', 'getAll');

$application->mount($countries);

$stadiums = new Collection();
$stadiums->setHandler('app\controllers\StadiumController', true);

$stadiums->post('/cric/v1/stadiums', 'create');
$stadiums->get('/cric/v1/stadiums', 'getAll');

$application->mount($stadiums);

$teams = new Collection();
$teams->setHandler('app\controllers\TeamController', true);

$teams->post('/cric/v1/teams', 'create');
$teams->get('/cric/v1/teams', 'getAll');

$application->mount($teams);

$tours = new Collection();
$tours->setHandler('app\controllers\TourController', true);

$tours->post('/cric/v1/tours', 'create');

$application->mount($tours);

$application->notFound(function () use ($application) {
    $application->response->setStatusCode(404, 'Not Found');
    $application->response->sendHeaders();

    $message = 'Action Not Found';
    $application->response->setContent($message);
    $application->response->send();
});

$application->error(function(Throwable $ex) use($application) {
    $description = $ex->getMessage();
    $http_status_code = 500;
    if($ex instanceof MyException)
    {
        $my_exception = $ex;
        $description = $my_exception->description;
        $http_status_code = $my_exception->http_status_code;
    }

    $response = Response::withError($description);

    $application->response->setContentType('application/json', 'UTF-8');
    $output_content = json_encode($response, JSON_UNESCAPED_SLASHES);
    $application->response->setContent($output_content);
    $application->response->setStatusCode($http_status_code);
    $application->response->send();
    exit;
});