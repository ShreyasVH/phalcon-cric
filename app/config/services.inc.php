<?php

use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

$di->setShared('db', function () {
    return new DbAdapter([
        'host'     => getenv('MYSQL_IP'),
        'username' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASSWORD'),
        'dbname'   => getenv('MYSQL_DB'),
        'port'     => getenv('MYSQL_PORT'),
        'charset'  => 'utf8',
    ]);
});






