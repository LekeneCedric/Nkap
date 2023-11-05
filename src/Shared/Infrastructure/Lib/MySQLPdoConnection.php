<?php

namespace Code237\Nkap\Shared\Infrastructure\Lib;

use Code237\Nkap\Shared\config\Config;
use Code237\Nkap\Shared\Lib\PdoConnection;
use PDO;
use PDOException;

class MySQLPdoConnection implements PdoConnection
{
    private string $dbUser = "";
    private string $dbPassword = "";
    private string $dsn = "";
    public function __construct(
    )
    {
        $env = Config::getConfigs();
        $dbHost = $env['db_host'];
        $dbPort = $env['db_port'];
        $dbName = $env['db_database'];
        $this->dbUser = $env['db_username'];
        $this->dbPassword = $env['db_password'];
        $this->dsn = "mysql:host=$dbHost:$dbPort;dbname=$dbName;charset=UTF8";
    }

    /**
     * @return PDO
     * @throws PDOException
     */
    public function getPdo(): PDO
    {
        try {
            return new PDO($this->dsn, $this->dbUser, $this->dbPassword);
        } catch (PDOException| \Exception $e) {
            throw new PDOException($e->getMessage());
        }
    }
}