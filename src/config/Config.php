<?php

namespace Code237\Nkap\config;
use Dotenv\Dotenv as env;

require_once __DIR__ . '/../../vendor/autoload.php'; // Include Composer's autoloader
$dotenv = env::createImmutable(__DIR__. '/../../');
$dotenv->load();

class Config
{
    public static function getConfigs(): array
    {
        return [
            "db_connection" => $_ENV["DB_CONNECTION"],

            "db_host" => $_ENV["DB_HOST"],

            "db_port" => $_ENV["DB_PORT"],

            "db_database" => $_ENV["DB_DATABASE"],

            "db_username" => $_ENV["DB_USERNAME"],

            "db_password" => $_ENV["DB_PASSWORD"]
        ];
    }
}