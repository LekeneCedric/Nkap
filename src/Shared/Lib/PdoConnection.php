<?php

namespace Code237\Nkap\Shared\Lib;

use PDO;

interface PdoConnection
{
    public function getPdo(): PDO;
}