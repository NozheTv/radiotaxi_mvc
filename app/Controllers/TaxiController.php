<?php
namespace App\Controllers;

use App\Core\Controller;

class TaxiController extends Controller
{
    private $taxiModel;

    public function __construct()
    {
        $this->taxiModel = $this->model('TaxiModel');
    }

    public function obtenerTaxiDisponible()
    {
        $taxi = $this->taxiModel->obtenerTaxiDisponible();
        if ($taxi) {
            echo json_encode(['success' => true, 'taxi' => $taxi]);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'No hay taxis disponibles']);
        }
    }
}
