<?php

class Producto {

    public static function CalcularPrecioLista(string $costo, string $utilidadSobreCien) {
        return $costo / (1 - $utilidadSobreCien);
    }

}