<?php
require_once RUTA_PROYECTO.'/usuarios/class/Cotizacion.php';
require_once RUTA_PROYECTO.'/usuarios/class/Combo.php';
require_once RUTA_PROYECTO.'/usuarios/class/Modulos.php';

class CotizacionesEditar {
    /**
     * Convierte valores numéricos en formato string a float seguro.
     */
    private static function toFloat($value): float {
        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        if (!is_string($value)) {
            return 0.0;
        }

        $value = trim($value);
        if ($value === '') {
            return 0.0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        // Soporta formatos 1.234,56 y 1234,56
        $normalized = str_replace('.', '', $value);
        $normalized = str_replace(',', '.', $normalized);

        return is_numeric($normalized) ? (float) $normalized : 0.0;
    }

    /**
     * 
     */
    public static function generarTablaProductos(
        $conexionBdPrincipal, 
        array $resultadoD, 
        $simbolosMonedas, 
        int $idEmpresa
    ): string {
        $htmlTabla = ''; 
        global $datosUsuarioActual, $conexionBdAdmin, $configuracion;

        $camposCotizacionDisabled = '';

        if (Cotizacion::esCotizacionVendida($_GET["id"], $idEmpresa)) {
            $camposCotizacionDisabled = 'disabled';
        }

        $productos = $conexionBdPrincipal->query("SELECT czpp_id, czpp_valor, czpp_cantidad, czpp_descuento, czpp_impuesto, czpp_orden, czpp_observacion, czpp_descuento_especial, czpp_aprobado_usuario, czpp_aprobado_fecha,
            prod_descuento2, prod_costo, prod_id, prod_nombre, prod_descripcion_corta, prod_utilidad, czpp_tipo, prod_existencias
            FROM productos
            INNER JOIN cotizacion_productos ON czpp_producto=prod_id AND czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo = ".CZPP_TIPO_COTZ."
            ORDER BY czpp_orden");

        $no = 1;
        $totalIva = 0;
        $subtotal = 0;
        $totalDescuento = 0;
        $totalCantidad = 0;
        $sumaUtilidad = 0;

        while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
            $dcto = 0;
            $valorTotal = 0;

            $valorTotal = (self::toFloat($prod['czpp_valor']) * self::toFloat($prod['czpp_cantidad']));

            if ($prod['czpp_cantidad'] > 0 && $prod['czpp_descuento'] > 0) {
                $valor_numerico_dcto = (float) str_replace(',', '.', $prod['czpp_descuento']);
                $dcto = ($valorTotal * ($valor_numerico_dcto / 100));
                $totalDescuento += $dcto;
            }

            $valorConDcto = $valorTotal - $dcto;

            $totalIva += ($valorConDcto * ($prod['czpp_impuesto'] / 100));

            $subtotal += $valorTotal;
            $totalCantidad += $prod['czpp_cantidad'];

            $utilidadDealer = $prod['prod_descuento2'] / 100;
            $precioDealer = !empty($prod['prod_costo']) ? $prod['prod_costo'] + ($prod['prod_costo'] * $utilidadDealer) : 0;

            $valorCotizado = self::toFloat($prod['czpp_valor']);
            $costoProducto = self::toFloat($prod['prod_costo']);
            $valorUtilidadProducto = $valorCotizado - $costoProducto;
            $sumaUtilidad += $valorUtilidadProducto;

            $htmlTabla .= '<tr class="producto">';
            $htmlTabla .= '<td>' . $no . '</td>';
            $htmlTabla .= '<td><input type="number" title="czpp_orden" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_orden'] . '" onChange="productos(this)" style="width: 50px; text-align: center;" translate="no" '.$camposCotizacionDisabled.'></td>';
            $htmlTabla .= '<td>';

            if($resultadoD['cotiz_vendida'] != Cotizacion::COTIZACION_VENDIDA) {
                $htmlTabla .= '<a href="#" class="delete-product" data-id="'. $prod['prod_id'].'"><i class="icon-trash"></i></a>';
            }

            $htmlTabla .= '<a href="productos-editar.php?id=' . $prod['prod_id'] . '" target="_blank">' . $prod['prod_nombre'] . ' <b>(Quedan '.$prod['prod_existencias'].' unds.)</b></a><br>';
            $htmlTabla .= '<span style="font-size: 9px; color: darkblue;">' . $prod['prod_descripcion_corta'] . '</span><br>';
            $htmlTabla .= '<p><textarea title="czpp_observacion" name="' . $prod['czpp_id'] . '" onChange="productos(this)" style="width: 300px;" rows="4" '.$camposCotizacionDisabled.' data-valor-actual="'.$prod['czpp_observacion'].'">' . $prod['czpp_observacion'] . '</textarea></p>';
            $htmlTabla .= '</td>';
            $htmlTabla .= '<td><input type="number" title="czpp_cantidad" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_cantidad'] . '" onChange="productos(this)" style="width: 50px; text-align: center;" translate="no" '.$camposCotizacionDisabled.' data-valor-actual="'.$prod['czpp_cantidad'].'"></td>';
            $htmlTabla .= '<td>';

            if ($resultadoD['cli_categoria'] == CLI_CATEGORIA_DEALER && $datosUsuarioActual['usr_tipo'] == 1) {
                $htmlTabla .= '<b>Precio Dealer: $' . number_format($precioDealer, 0, ",", ".") . '</b><br>';
            }

            $htmlTabla .= '<input type="text" alt="' . $resultadoD['cli_categoria'] . '" title="czpp_valor" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_valor'] . '" onChange="productos(this)" style="width: 200px;" translate="no" '.$camposCotizacionDisabled.' data-valor-actual="'.$prod['czpp_valor'].'"><br>';

            if ($datosUsuarioActual['usr_tipo'] == 1) {
                $htmlTabla .= '<b>Costo: $' . number_format(self::toFloat($prod['prod_costo']), 0, ",", ".") . '</b><br>';
                $htmlTabla .= '<b>Utilidad: ' . $prod['prod_utilidad'] . '%</b><br>';
                $htmlTabla .= '<b class="valor-utilidad" data-utilidad="' . $valorUtilidadProducto . '">Valor Utilidad: $' . number_format($valorUtilidadProducto, 0, ",", ".") . '</b><br>';
            }

            $htmlTabla .= '</td>';
            $htmlTabla .= '<td><input type="text" title="czpp_impuesto" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_impuesto'] . '" onChange="productos(this)" style="width: 50px; text-align: center;" translate="no" '.$camposCotizacionDisabled.' data-valor-actual="'.$prod['czpp_impuesto'].'"></td>';
            $htmlTabla .= '<td><input type="text" title="czpp_descuento" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_descuento'] . '" onChange="productos(this)" style="width: 50px; text-align: center;" translate="no" '.$camposCotizacionDisabled.' data-valor-actual="'.$prod['czpp_descuento'].'"></td>';

            if ($resultadoD['cotiz_descuentos_especiales'] == 1) {
                $htmlTabla .= '<td>';
                $htmlTabla .= '<input type="text" title="czpp_descuento_especial" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_descuento_especial'] . '" onChange="combos(this)" style="width: 50px; text-align: center;" translate="no" '.$camposCotizacionDisabled.'>';

                if (
                    Modulos::validarRol([309], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && 
                    $prod['czpp_aprobado_usuario'] == "" && 
                    $prod['czpp_descuento_especial'] > 0
                ) {
                    $htmlTabla .= '<br><a href="bd_update/descuentos-cotizaciones-actualizar.php?idItem=' . $prod['czpp_id'] . '" class="btn btn-success"> <i class="icon-ok-sign"></i> </a>';
                }

                $consultaDctoEspecial = $conexionBdPrincipal->query("SELECT usr_id, usr_nombre FROM usuarios WHERE usr_id='" . $prod['czpp_aprobado_usuario'] . "'");
                $usuarioDctoEspecialAprobar = mysqli_fetch_array($consultaDctoEspecial, MYSQLI_BOTH);
                $htmlTabla .= '<br><span style="font-size:10px; color:gray;">' . $prod['czpp_aprobado_fecha'] . '<br>' . $usuarioDctoEspecialAprobar['usr_nombre'] . '</span>';
                $htmlTabla .= '</td>';
            }

            $htmlTabla .= '<td>'. '<span class="moneda-simbolo">' . $simbolosMonedas[$resultadoD['cotiz_moneda']].'</span>'. '	<span class="valor-numerico">'. number_format($valorTotal, 0, ",", ".") . '</span>'.'</td>';
            $htmlTabla .= '</tr>';

            $no++;
        }

        return $htmlTabla; // Devuelve solo filas <tr>
    }

    /**
     * 
     */
    public static function generarTablacombos($conexionBdPrincipal, array $resultadoD, $simbolosMonedas, int $idEmpresa): string {
        $htmlTabla = ''; 
        global $datosUsuarioActual;

        if (Cotizacion::esCotizacionVendida($_GET["id"], $idEmpresa)) {
            $camposCotizacionDisabled = 'disabled';
        }

        $productos = $conexionBdPrincipal->query("SELECT * FROM combos
        INNER JOIN cotizacion_productos ON czpp_combo=combo_id AND czpp_cotizacion='".$_GET["id"]."' AND czpp_tipo = ".CZPP_TIPO_COTZ."
        WHERE combo_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'
        ORDER BY czpp_orden");

        $no = 1;
        $totalIva = 0;
        $subtotal = 0;
        $totalDescuento = 0;
        $totalCantidad = 0;
        $sumaUtilidad = 0;

        while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
            $dcto = 0;
            $valorTotal = 0;

            $valorTotal = (self::toFloat($prod['czpp_valor']) * self::toFloat($prod['czpp_cantidad']));

            if($prod['czpp_cantidad']>0 and $prod['czpp_descuento']>0){
                $valor_numerico_dcto = (float) str_replace(',', '.', $prod['czpp_descuento']);
                $dcto = ($valorTotal * ($valor_numerico_dcto /100));
                $totalDescuento += $dcto;	
            }

            $valorConDcto = $valorTotal - $dcto;

            $totalIva += ($valorConDcto * ($prod['czpp_impuesto']/100));

            $subtotal +=$valorTotal;
            
            
            $totalCantidad += $prod['czpp_cantidad'];

            $consultaPreciosCombos=$conexionBdPrincipal->query("SELECT SUM(copp_cantidad*prod_precio) FROM combos_productos
            INNER JOIN productos ON prod_id=copp_producto
            WHERE copp_combo='".$prod['combo_id']."'");
            $precioNormalCombo = mysqli_fetch_array($consultaPreciosCombos, MYSQLI_BOTH);
            
            $valorActualCombo = round(Combo::obtenerValorActualCombo($prod['combo_id'], $conexionBdPrincipal),0);

            $productosDelCombo = $conexionBdPrincipal->query("SELECT * FROM combos_productos
            INNER JOIN productos ON prod_id=copp_producto
            WHERE copp_combo='".$prod['combo_id']."'
            ");

            $sumaCostosProductosCombos = 0;
            $precioDealer = 0;
            $totalDealer = 0;
            while($pdCombo = mysqli_fetch_array($productosDelCombo, MYSQLI_BOTH)){

                $sumaCostosProductosCombos += $pdCombo['prod_costo'];

                $utilidadDealer = !empty($pdCombo['prod_descuento2']) ? $pdCombo['prod_descuento2'] / 100 : 0;
                $precioDealer = !empty($pdCombo['prod_costo']) ? $pdCombo['prod_costo'] + ($pdCombo['prod_costo'] * $utilidadDealer) : 0;
                $subtotalDealer = ($precioDealer * $pdCombo['copp_cantidad']);
                $totalDealer +=$subtotalDealer;

            }

            $valorCotizadoCombo = self::toFloat($prod['czpp_valor']);
            $valorUtilidadCombo = $valorCotizadoCombo - (float) $sumaCostosProductosCombos;
            $sumaUtilidad += $valorUtilidadCombo;

            $alarmaValorComboDiferente = '';

            if($resultadoD['cotiz_vendida'] != Cotizacion::COTIZACION_VENDIDA && $valorActualCombo <> $prod['czpp_valor']) {
                $alarmaValorComboDiferente = 'style="background-color:#f5ee8c;" title="Este valor es diferente al actual del combo ($'.number_format(self::toFloat($valorActualCombo), 0, ",", ".").'). Verificalo dando click sobre el nombre del combo. Si deseas actualizarlo puedes eliminar este item y volverlo a agregar."';
            }

            $htmlTabla .= '<tr class="combo">';
            $htmlTabla .= '<td>' . $no . '</td>';
            $htmlTabla .= '<td><input type="number" title="czpp_orden" name="'.$prod['czpp_id'].'" value="'.$prod['czpp_orden'].'" onChange="productos(this)" style="width: 50px; text-align: center;" translate="no" '.$camposCotizacionDisabled.'></td>';
            $htmlTabla .= '<td>';

            if($resultadoD['cotiz_vendida'] != Cotizacion::COTIZACION_VENDIDA) {
                $htmlTabla .= '<a href="#" class="delete-combo" data-id="'. $prod['combo_id'].'"><i class="icon-trash"></i></a>&nbsp;';
            }

            $htmlTabla .= '<a href="combos-editar.php?id=' . $prod['combo_id'] . '" target="_blank">' . $prod['combo_nombre'] . '</a><br>';

            if($prod['combo_descuento'] > 0 and $resultadoD['cotiz_ocultar_descuento_combo']=='0'){
                $htmlTabla .= '<span><b>Precio Normal:</b> $'.number_format(self::toFloat($precioNormalCombo[0]),0,".",".").'</span><br>';
                $htmlTabla .= '<span><b>Descuento:</b>'.$prod['combo_descuento'].'%</span><br>';
            }

            $htmlTabla .= '<span style="font-size: 9px; color: darkblue;">' . $prod['combo_descripcion'] . '</span><br>';
            $htmlTabla .= '<span style="font-size: 9px; color: teal;">';
            $productosCombo = $conexionBdPrincipal->query("SELECT copp_id, copp_combo, copp_producto, copp_cantidad, prod_id, prod_nombre, prod_existencias FROM productos
            INNER JOIN combos_productos ON copp_producto=prod_id AND copp_combo='".$prod['combo_id']."'
            WHERE prod_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'
            ORDER BY copp_id");

            while($prodCombo = mysqli_fetch_array($productosCombo, MYSQLI_BOTH)){
                $htmlTabla .= $prodCombo['prod_nombre']." <b>(Incluye ".$prodCombo['copp_cantidad']." Unds. de ".$prodCombo['prod_existencias']." restantes)</b>.<br>";
            }

            $htmlTabla .= '</span><br>';
            $htmlTabla .= '<p><textarea title="czpp_observacion" name="' . $prod['czpp_id'] . '" onChange="productos(this)" style="width: 300px;" rows="4" '.$camposCotizacionDisabled.'>' . $prod['czpp_observacion'] . '</textarea></p>';
            $htmlTabla .= '</td>';
            $htmlTabla .= '<td><input type="number" title="czpp_cantidad" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_cantidad'] . '" onChange="productos(this)" style="width: 50px; text-align: center;" translate="no" '.$camposCotizacionDisabled.'></td>';
            $htmlTabla .= '<td '.$alarmaValorComboDiferente.'>';

            if ($resultadoD['cli_categoria'] == CLI_CATEGORIA_DEALER && $datosUsuarioActual['usr_tipo'] == 1) {
                $htmlTabla .= '<b>Precio Dealer: $' . number_format($totalDealer, 0, ",", ".") . '</b><br>';
            }

            $disabledValorCombos = 'disabled';

            if ($resultadoD['cotiz_es_precotizacion'] == 1) {
                $disabledValorCombos = '';
            }

            $htmlTabla .= '<input type="text" alt="' . $resultadoD['cli_categoria'] . '" title="czpp_valor" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_valor'] . '" onChange="productos(this)" style="width: 200px;" translate="no" '.$disabledValorCombos.'><br>';

            if ($datosUsuarioActual['usr_tipo'] == 1) {
                $htmlTabla .= '<b>Costo: $' . number_format($sumaCostosProductosCombos, 0, ",", ".") . '</b><br>';
                $htmlTabla .= '<b class="valor-utilidad" data-utilidad="' . $valorUtilidadCombo . '">Valor Utilidad: $' . number_format($valorUtilidadCombo, 0, ",", ".") . '</b><br>';
            }
            $htmlTabla .= '</td>';
            $htmlTabla .= '<td><input type="text" title="czpp_impuesto" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_impuesto'] . '" onChange="productos(this)" style="width: 50px; text-align: center;" translate="no" '.$camposCotizacionDisabled.'></td>';
            $htmlTabla .= '<td><input type="text" title="czpp_descuento" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_descuento'] . '" onChange="combos(this)" style="width: 50px; text-align: center;" translate="no" '.$camposCotizacionDisabled.'></td>';
            if ($resultadoD['cotiz_descuentos_especiales'] == 1) {
                $htmlTabla .= '<td>';
                $htmlTabla .= '<input type="text" title="czpp_descuento_especial" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_descuento_especial'] . '" onChange="combos(this)" style="width: 50px; text-align: center;" translate="no" '.$camposCotizacionDisabled.'>';
                if ($datosUsuarioActual['usr_tipo'] == 1 && $prod['czpp_aprobado_usuario'] == "" && $prod['czpp_descuento_especial'] > 0) {
                    $htmlTabla .= '<br><a href="bd_update/descuentos-cotizaciones-actualizar.php?idItem=' . $prod['czpp_id'] . '" class="btn btn-success"> <i class="icon-ok-sign"></i> </a>';
                }
                $consultaDctoEspecial = $conexionBdPrincipal->query("SELECT usr_id, usr_nombre FROM usuarios WHERE usr_id='" . $prod['czpp_aprobado_usuario'] . "'");
                $usuarioDctoEspecialAprobar = mysqli_fetch_array($consultaDctoEspecial, MYSQLI_BOTH);
                $htmlTabla .= '<br><span style="font-size:10px; color:gray;">' . $prod['czpp_aprobado_fecha'] . '<br>' . $usuarioDctoEspecialAprobar['usr_nombre'] . '</span>';
                $htmlTabla .= '</td>';
            }
            $htmlTabla .= '<td>'. '<span class="moneda-simbolo">' . $simbolosMonedas[$resultadoD['cotiz_moneda']].'</span>'. '	<span class="valor-numerico">'. number_format($valorTotal, 0, ",", ".") . '</span>'.'</td>';
            $htmlTabla .= '</tr>';

            $no++;
        }

        return $htmlTabla; // Devuelve solo filas <tr>
    }

    public static function generarTablaServicios($conexionBdPrincipal, array $resultadoD, $simbolosMonedas, int $idEmpresa) {
        $htmlTabla = ''; 
        global $datosUsuarioActual;

        if (Cotizacion::esCotizacionVendida($_GET["id"], $idEmpresa)) {
            $camposCotizacionDisabled = 'disabled';
        }

        $productos = $conexionBdPrincipal->query("SELECT * FROM servicios
        INNER JOIN cotizacion_productos ON czpp_servicio=serv_id AND czpp_cotizacion='".$_GET["id"]."' AND czpp_tipo = ".CZPP_TIPO_COTZ."
        WHERE serv_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'
        ORDER BY czpp_orden");

        $no = 1;
        $totalIva = 0;
        $subtotal = 0;
        $totalDescuento = 0;
        $totalCantidad = 0;
        $sumaUtilidad = 0;

        while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
            $dcto = 0;
            $valorTotal = 0;

            $valorTotal = (self::toFloat($prod['czpp_valor']) * self::toFloat($prod['czpp_cantidad']));

            if($prod['czpp_cantidad']>0 and $prod['czpp_descuento']>0){
                $valor_numerico_dcto = (float) str_replace(',', '.', $prod['czpp_descuento']);
                $dcto = ($valorTotal * ($valor_numerico_dcto/100));
                $totalDescuento += $dcto;	
            }

            $valorConDcto = $valorTotal - $dcto;

            $totalIva += ($valorConDcto * ($prod['czpp_impuesto']/100));

            $subtotal +=$valorTotal;	

            $totalCantidad += $prod['czpp_cantidad'];

            $htmlTabla .= '<tr class="servicio">';
            $htmlTabla .= '<td>' . $no . '</td>';
            $htmlTabla .= '<td><input type="number" title="czpp_orden" name="'.$prod['czpp_id'].'" value="'.$prod['czpp_orden'].'" onChange="productos(this)" style="width: 50px; text-align: center;" translate="no" '.$camposCotizacionDisabled.'></td>';
            $htmlTabla .= '<td>';

            if($resultadoD['cotiz_vendida'] != Cotizacion::COTIZACION_VENDIDA) {
                $htmlTabla .= '<a href="#" class="delete-servicios" data-id="'. $prod['serv_id'].'"><i class="icon-trash"></i></a>';
            }

            $htmlTabla .= '<a href="servicios-editar.php?id=' . $prod['serv_id'] . '" target="_blank">' . $prod['serv_nombre'] . '</a><br>';
            $htmlTabla .= '<p><textarea title="czpp_observacion" name="' . $prod['czpp_id'] . '" onChange="productos(this)" style="width: 300px;" rows="4" '.$camposCotizacionDisabled.'>' . $prod['czpp_observacion'] . '</textarea></p>';
            $htmlTabla .= '</td>';
            $htmlTabla .= '<td><input type="number" title="czpp_cantidad" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_cantidad'] . '" onChange="productos(this)" style="width: 50px; text-align: center;" translate="no" '.$camposCotizacionDisabled.'></td>';
            $htmlTabla .= '<td><input type="text" alt="' . $resultadoD['cli_categoria'] . '" title="czpp_valor" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_valor'] . '" onChange="productos(this)" style="width: 200px;" translate="no" '.$camposCotizacionDisabled.'></td>';
            $htmlTabla .= '<td><input type="text" title="czpp_impuesto" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_impuesto'] . '" onChange="productos(this)" style="width: 50px; text-align: center;" translate="no" '.$camposCotizacionDisabled.'></td>';
            $htmlTabla .= '<td><input type="text" title="czpp_descuento" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_descuento'] . '" onChange="servicios(this)" style="width: 50px; text-align: center;" translate="no" '.$camposCotizacionDisabled.'></td>';
            if ($resultadoD['cotiz_descuentos_especiales'] == 1) {
                $htmlTabla .= '<td><input type="text" title="czpp_descuento_especial" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_descuento_especial'] . '" onChange="combos(this)" style="width: 50px; text-align: center;" translate="no" '.$camposCotizacionDisabled.'></td>';
            }
            $htmlTabla .= '<td>'. '<span class="moneda-simbolo">' . $simbolosMonedas[$resultadoD['cotiz_moneda']].'</span>'. '	<span class="valor-numerico">'. number_format($valorTotal, 0, ",", ".") . '</span>'.'</td>';
            $htmlTabla .= '</tr>';

            $no++;
        }

        return $htmlTabla; // Devuelve solo filas <tr>
    }

}
?>