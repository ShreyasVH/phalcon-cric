<?php

use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Events\Manager;
use Phalcon\Logger\Logger;
use Phalcon\Logger\Adapter\Stream;

$di->setShared('db', function () {
    $eventsManager = new Manager();
    $adapter = new Stream(APP_PATH . 'logs/db.log');
    $logger  = new Logger(
        'messages',
        [
            'main' => $adapter,
        ]
    );

    $eventsManager->attach(
        'db:beforeQuery',
        function ($event, $connection) use ($logger) {
            $logger->info(
                $connection->getSQLStatement()
            );
        }
    );

    $connection =  new DbAdapter([
        'host'     => getenv('MYSQL_IP'),
        'username' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASSWORD'),
        'dbname'   => getenv('MYSQL_DB'),
        'port'     => getenv('MYSQL_PORT'),
        'charset'  => 'utf8',
    ]);

    $connection->setEventsManager($eventsManager);

    return $connection;
});






