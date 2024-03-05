<?php
class CotizacionesEditar {

    public static function generarTablaProductos($conexionBdPrincipal, $resultadoD, $simbolosMonedas) {
        $htmlTabla = ''; 
        global $datosUsuarioActual;
                            
        $productos = $conexionBdPrincipal->query("SELECT czpp_id, czpp_valor, czpp_cantidad, czpp_descuento, czpp_impuesto, czpp_orden, czpp_observacion, czpp_descuento_especial, czpp_aprobado_usuario, czpp_aprobado_fecha,
            prod_descuento2, prod_costo, prod_id, prod_nombre, prod_descripcion_corta, prod_utilidad
            FROM productos
            INNER JOIN cotizacion_productos ON czpp_producto=prod_id AND czpp_cotizacion='" . $_GET["id"] . "'
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

            $valorTotal = ($prod['czpp_valor'] * $prod['czpp_cantidad']);

            if ($prod['czpp_cantidad'] > 0 && $prod['czpp_descuento'] > 0) {
                $dcto = ($valorTotal * ($prod['czpp_descuento'] / 100));
                $totalDescuento += $dcto;
            }

            $valorConDcto = $valorTotal - $dcto;

            $totalIva += ($valorConDcto * ($prod['czpp_impuesto'] / 100));

            $subtotal += $valorTotal;
            $totalCantidad += $prod['czpp_cantidad'];

            $utilidadDealer = $prod['prod_descuento2'] / 100;
            $precioDealer = !empty($prod['prod_costo']) ? $prod['prod_costo'] + ($prod['prod_costo'] * $utilidadDealer) : 0;

            $sumaUtilidad += ($prod['czpp_valor'] - $prod['prod_costo']);

            $htmlTabla .= '<tr class="producto">';
            $htmlTabla .= '<td>' . $no . '</td>';
            $htmlTabla .= '<td><input type="number" title="czpp_orden" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_orden'] . '" onChange="productos(this)" style="width: 50px; text-align: center;"></td>';
            $htmlTabla .= '<td>';
            $htmlTabla .= '<a href="#" class="delete-product" data-id="'. $prod['prod_id'].'"><i class="icon-trash"></i></a>';
            $htmlTabla .= '<a href="productos-editar.php?id=' . $prod['prod_id'] . '" target="_blank">' . $prod['prod_nombre'] . '</a><br>';
            $htmlTabla .= '<span style="font-size: 9px; color: darkblue;">' . $prod['prod_descripcion_corta'] . '</span><br>';
            $htmlTabla .= '<p><textarea title="czpp_observacion" name="' . $prod['czpp_id'] . '" onChange="productos(this)" style="width: 300px;" rows="4">' . $prod['czpp_observacion'] . '</textarea></p>';
            $htmlTabla .= '</td>';
            $htmlTabla .= '<td><input type="number" title="czpp_cantidad" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_cantidad'] . '" onChange="productos(this)" style="width: 50px; text-align: center;"></td>';
            $htmlTabla .= '<td>';
            if ($resultadoD['cli_categoria'] == CLI_CATEGORIA_DEALER && $datosUsuarioActual['usr_tipo'] == 1) {
                $htmlTabla .= '<b>Precio Dealer: $' . number_format($precioDealer, 0, ",", ".") . '</b><br>';
            }
            $htmlTabla .= '<input type="text" alt="' . $resultadoD['cli_categoria'] . '" title="czpp_valor" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_valor'] . '" onChange="productos(this)" style="width: 200px;"><br>';
            if ($datosUsuarioActual['usr_tipo'] == 1) {
                $htmlTabla .= '<b>Costo: $' . number_format($prod['prod_costo'], 0, ",", ".") . '</b><br>';
                $htmlTabla .= '<b>Utilidad: ' . $prod['prod_utilidad'] . '%</b><br>';
                $htmlTabla .= '<b>Valor Utilidad: $' . number_format(($prod['czpp_valor'] - $prod['prod_costo']), 0, ",", ".") . '</b><br>';
            }
            $htmlTabla .= '</td>';
            $htmlTabla .= '<td><input type="text" title="czpp_impuesto" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_impuesto'] . '" onChange="productos(this)" style="width: 50px; text-align: center;"></td>';
            $htmlTabla .= '<td><input type="text" title="czpp_descuento" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_descuento'] . '" onChange="productos(this)" style="width: 50px; text-align: center;"></td>';
            if ($resultadoD['cotiz_descuentos_especiales'] == 1) {
                $htmlTabla .= '<td>';
                $htmlTabla .= '<input type="text" title="czpp_descuento_especial" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_descuento_especial'] . '" onChange="combos(this)" style="width: 50px; text-align: center;">';
                if ($datosUsuarioActual['usr_tipo'] == 1 && $prod['czpp_aprobado_usuario'] == "" && $prod['czpp_descuento_especial'] > 0) {
                    $htmlTabla .= '<br><a href="sql.php?get=70&idItem=' . $prod['czpp_id'] . '" class="btn btn-success"> <i class="icon-ok-sign"></i> </a>';
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

        return "<body>$htmlTabla</body>"; // Devuelve el HTML de la tabla
    }

    public static function generarTablacombos($conexionBdPrincipal, $resultadoD, $simbolosMonedas) {
        $htmlTabla = ''; 
        global $datosUsuarioActual;
                            
        $productos = $conexionBdPrincipal->query("SELECT * FROM combos
        INNER JOIN cotizacion_productos ON czpp_combo=combo_id AND czpp_cotizacion='".$_GET["id"]."'
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

            $valorTotal = ($prod['czpp_valor'] * $prod['czpp_cantidad']);

            if($prod['czpp_cantidad']>0 and $prod['czpp_descuento']>0){
                $dcto = ($valorTotal * ($prod['czpp_descuento']/100));
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

            $productosDelCombo = $conexionBdPrincipal->query("SELECT * FROM combos_productos
            INNER JOIN productos ON prod_id=copp_producto
            WHERE copp_combo='".$prod['combo_id']."'
            ");

            $sumaCostosProductosCombos = 0;
            $precioDealer = 0;
            $totalDealer = 0;
            while($pdCombo = mysqli_fetch_array($productosDelCombo, MYSQLI_BOTH)){

                $sumaCostosProductosCombos += $pdCombo['prod_costo'];

                $utilidadDealer = $pdCombo['prod_descuento2'] / 100;
                $precioDealer = $pdCombo['prod_costo'] + ($pdCombo['prod_costo'] * $utilidadDealer);
                $subtotalDealer = ($precioDealer * $pdCombo['copp_cantidad']);
                $totalDealer +=$subtotalDealer;

            }

            $sumaUtilidad += ($prod['czpp_valor'] - $sumaCostosProductosCombos);

            $htmlTabla .= '<tr class="combo">';
            $htmlTabla .= '<td>' . $no . '</td>';
            $htmlTabla .= '<td><input type="number" title="czpp_orden" name="'.$prod['czpp_id'].'" value="'.$prod['czpp_orden'].'>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>';
            $htmlTabla .= '<td>';
            $htmlTabla .= '<a href="#" class="delete-combo" data-id="'. $prod['combo_id'].'"><i class="icon-trash"></i></a>';
            $htmlTabla .= '<a href="combos-editar.php?id=' . $prod['combo_id'] . '" target="_blank">' . $prod['combo_nombre'] . '</a><br>';
            if($prod['combo_descuento']!="" and $resultadoD['cotiz_ocultar_descuento_combo']=='0'){
                $htmlTabla .= '<span><b>Precio Normal:</b> $'.number_format($precioNormalCombo[0],0,".",".").'</span><br>';
                $htmlTabla .= '<span><b>Descuento:</b>'.$prod['combo_descuento'].'%</span><br>';
            }
            $htmlTabla .= '<span style="font-size: 9px; color: darkblue;">' . $prod['combo_descripcion'] . '</span><br>';
            $htmlTabla .= '<span style="font-size: 9px; color: teal;">';
            $productosCombo = $conexionBdPrincipal->query("SELECT copp_id, copp_combo, copp_producto, copp_cantidad, prod_id, prod_nombre FROM productos
            INNER JOIN combos_productos ON copp_producto=prod_id AND copp_combo='".$prod['combo_id']."'
            WHERE prod_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'
            ORDER BY copp_id");
            while($prodCombo = mysqli_fetch_array($productosCombo, MYSQLI_BOTH)){
                $htmlTabla .= $prodCombo['prod_nombre']." (".$prodCombo['copp_cantidad']." Unds.).<br>";
            }
            $htmlTabla .= '</span><br>';
            $htmlTabla .= '<p><textarea title="czpp_observacion" name="' . $prod['czpp_id'] . '" onChange="productos(this)" style="width: 300px;" rows="4">' . $prod['czpp_observacion'] . '</textarea></p>';
            $htmlTabla .= '</td>';
            $htmlTabla .= '<td><input type="number" title="czpp_cantidad" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_cantidad'] . '" onChange="productos(this)" style="width: 50px; text-align: center;"></td>';
            $htmlTabla .= '<td>';
            if ($resultadoD['cli_categoria'] == CLI_CATEGORIA_DEALER && $datosUsuarioActual['usr_tipo'] == 1) {
                $htmlTabla .= '<b>Precio Dealer: $' . number_format($totalDealer, 0, ",", ".") . '</b><br>';
            }
            $htmlTabla .= '<input type="text" alt="' . $resultadoD['cli_categoria'] . '" title="czpp_valor" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_valor'] . '" onChange="productos(this)" style="width: 200px;" disabled><br>';
            if ($datosUsuarioActual['usr_tipo'] == 1) {
                $htmlTabla .= '<b>Costo: $' . number_format($sumaCostosProductosCombos, 0, ",", ".") . '</b><br>';
                $htmlTabla .= '<b>Valor Utilidad: $' . number_format(($prod['czpp_valor'] - $sumaCostosProductosCombos), 0, ",", ".") . '</b><br>';
            }
            $htmlTabla .= '</td>';
            $htmlTabla .= '<td><input type="text" title="czpp_impuesto" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_impuesto'] . '" onChange="productos(this)" style="width: 50px; text-align: center;"></td>';
            $htmlTabla .= '<td><input type="text" title="czpp_descuento" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_descuento'] . '" onChange="productos(this)" style="width: 50px; text-align: center;"></td>';
            if ($resultadoD['cotiz_descuentos_especiales'] == 1) {
                $htmlTabla .= '<td>';
                $htmlTabla .= '<input type="text" title="czpp_descuento_especial" name="' . $prod['czpp_id'] . '" value="' . $prod['czpp_descuento_especial'] . '" onChange="combos(this)" style="width: 50px; text-align: center;">';
                if ($datosUsuarioActual['usr_tipo'] == 1 && $prod['czpp_aprobado_usuario'] == "" && $prod['czpp_descuento_especial'] > 0) {
                    $htmlTabla .= '<br><a href="sql.php?get=70&idItem=' . $prod['czpp_id'] . '" class="btn btn-success"> <i class="icon-ok-sign"></i> </a>';
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

        return "<body>$htmlTabla</body>"; // Devuelve el HTML de la tabla
    }

}
?>