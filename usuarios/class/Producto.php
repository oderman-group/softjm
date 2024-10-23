<?php

class Producto {

    public const PROD_UTILIDAD = 'prod_utilidad';
    public const PROD_COSTO    = 'prod_costo';

    public static function CalcularPrecioLista(string $costo, string $utilidadSobreCien) {
        return $costo / (1 - $utilidadSobreCien);
    }

}