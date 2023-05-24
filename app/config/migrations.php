<?php

use Phalcon\Config\Config;

return new Config([
    'database' => [
        'adapter' => 'mysql',
        'host'     => getenv('MYSQL_IP'),
        'username' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASSWORD'),
        'dbname'   => getenv('MYSQL_DB'),
        'port'     => getenv('MYSQL_PORT'),
        'charset' => 'utf8',
    ],
    'application' => [
        'logInDb' => true,
        'no-auto-increment' => true,
        'skip-ref-schema' => true,
        'skip-foreign-checks' => true,
        'migrationsDir' => 'db/migrations',
        'migrationsTsBased' => false, // true - Use TIMESTAMP as version name, false - use versions
        'exportDataFromTables' => [
            // Tables names
            // Attention! It will export data every new migration
        ],
    ],
]);
