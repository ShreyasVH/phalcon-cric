<?php

use app\exceptions\MyException;
use Phalcon\Mvc\Micro\Collection;
use app\responses\Response;
use app\middlewares\CorsMiddleware;

$application->before(new CorsMiddleware());

$countries = new Collection();
$countries->setHandler('app\controllers\CountryController', true);

$countries->options('/{params:[a-zA-Z0-9\/]+}', function () use ($application) {
});

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
$tours->get('/cric/v1/tours/year/{year:[0-9]+}', 'get_all_for_year');
$tours->get('/cric/v1/tours/years', 'get_all_years');
$tours->get('/cric/v1/tours/{id:[0-9]+}', 'get_by_id');

$application->mount($tours);

$players = new Collection();
$players->setHandler('app\controllers\PlayerController', true);

$players->post('/cric/v1/players', 'create');
$players->get('/cric/v1/players', 'getall');
$players->get('/cric/v1/players/{id:[0-9]+}', 'get_by_id');
$players->post('/cric/v1/players/merge', 'merge');
$players->get('/cric/v1/players/search', 'search');

$application->mount($players);

$series = new Collection();
$series->setHandler('app\controllers\SeriesController', true);

$series->post('/cric/v1/series', 'create');
$series->get('/cric/v1/series', 'get_all');
$series->put('/cric/v1/series/{id:[0-9]+}', 'update');
$series->get('/cric/v1/series/{id:[0-9]+}', 'get');
$series->delete('/cric/v1/series/{id:[0-9]+}', 'remove');

$application->mount($series);

$matches = new Collection();
$matches->setHandler('app\controllers\MatchController', true);

$matches->post('/cric/v1/matches', 'create');
$matches->get('/cric/v1/matches/{id:[0-9]+}', 'get_by_id');
$matches->delete('/cric/v1/matches/{id:[0-9]+}', 'remove');

$application->mount($matches);

$tags = new Collection();
$tags->setHandler('app\controllers\TagsController', true);

$tags->get('/cric/v1/tags', 'getAll');

$application->mount($tags);

$stats = new Collection();
$stats->setHandler('app\controllers\StatsController', true);

$stats->post('/cric/v1/stats', 'get_stats');

$application->mount($stats);

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

    $response = Response::withMessage($description);

    $application->response->setContentType('application/json', 'UTF-8');
    $output_content = json_encode($response, JSON_UNESCAPED_SLASHES);
    $application->response->setContent($output_content);
    $application->response->setStatusCode($http_status_code);
    $application->response->send();
    exit;
});