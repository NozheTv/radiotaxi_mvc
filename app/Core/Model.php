<?php
namespace App\Core;

use PDO;

class Model {
    protected $pdo;

    public function __construct() {
        global $pdo; // Usar conexión global
        $this->pdo = $pdo;
    }
}
