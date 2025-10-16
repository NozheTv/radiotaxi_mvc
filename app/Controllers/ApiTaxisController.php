<?php
require_once __DIR__ . '/../Models/Radiotaxi.php';

class ApiTaxisController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function obtenerTaxis()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $taxis = Radiotaxi::obtenerTodos($this->pdo);
            echo json_encode($taxis);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
