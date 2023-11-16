<?php

/**
         * Calcula el precio de una vivienda basado en la zona y el tamaño.
         *
         * Esta función toma la zona, el tamaño y el precio de una vivienda y calcula
         * el precio final aplicando coeficientes basados en la zona y el tamaño.
         *
         * @param string $zona La zona de la vivienda (por ejemplo, 'Centro').
         * @param int $tamaño El tamaño de la vivienda en metros cuadrados.
         * @param float $precio El precio base de la vivienda.
         *
         * @return float El precio final calculado.
         */
function calcularPrecio($zona, $tamano, $precio) {
    $coeficiente = 0.0;

    if ($tamano < 100) {
        switch ($zona) {
            case 'Centro':
                $coeficiente = 0.3;
                break;
            case 'Zaidín':
                $coeficiente = 0.25;
                break;
            case 'Chana':
                $coeficiente = 0.22;
                break;
            case 'Albaicín':
                $coeficiente = 0.2;
                break;
            case 'Sacromonte':
                $coeficiente = 0.22;
                break;
            case 'Realejo':
                $coeficiente = 0.25;
                break;
        }
    } else {
        switch ($zona) {
            case 'Centro':
                $coeficiente = 0.35;
                break;
            case 'Zaidín':
                $coeficiente = 0.28;
                break;
            case 'Chana':
                $coeficiente = 0.25;
                break;
            case 'Albaicín':
                $coeficiente = 0.35;
                break;
            case 'Sacromonte':
                $coeficiente = 0.25;
                break;
            case 'Realejo':
                $coeficiente = 0.28;
                break;
        }
    }

    return $precio * $coeficiente;
}

?>