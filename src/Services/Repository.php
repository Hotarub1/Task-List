<?php

namespace Services;

use PDO;

class Repository
{
    protected PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
}
