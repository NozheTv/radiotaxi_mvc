<?php

class Radiotaxi
{
    public static function obtenerTodos($pdo)
    {
        $sql = "
            SELECT 
                r.id,
                r.placa,
                r.modelo,
                r.gps_latitud,
                r.gps_longitud,
                e.descripcion AS estado,
                u.nombre_completo AS conductor
            FROM radiotaxis r
            LEFT JOIN estados_taxi e ON r.id_estado_taxi = e.id
            LEFT JOIN usuarios u ON r.id_conductor = u.id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
