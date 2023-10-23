<?php

use Dotenv\Dotenv as env;

require_once __DIR__ . '/vendor/autoload.php'; // Include Composer's autoloader
$dotenv = env::createImmutable(__DIR__. '/');
$dotenv->load();
var_dump($_ENV["DB_USERNAME"]);
return
[
    'paths' => [
        'migrations' => 'src/Shared/Infrastructure/Database/migrations',
        'seeds' => 'src/Shared/Infrastructure/Database/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => $_ENV["DB_HOST"],
            'name' => 'production_db',
            'user' => $_ENV["DB_USERNAME"],
            'pass' => $_ENV["DB_PASSWORD"],
            'port' => $_ENV["DB_PORT"],
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => $_ENV["DB_HOST"],
            'name' => $_ENV["DB_DATABASE"],
            'user' => $_ENV["DB_USERNAME"],
            'pass' => $_ENV["DB_PASSWORD"],
            'port' => $_ENV["DB_PORT"],
            'charset' => 'utf8',
        ],
        'testing' => [
            'adapter' => 'mysql',
            'host' => $_ENV["DB_HOST"],
            'name' => $_ENV["DB_TESTING"],
            'user' => $_ENV["DB_USERNAME"],
            'pass' => $_ENV["DB_PASSWORD"],
            'port' => $_ENV["DB_PORT"],
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
