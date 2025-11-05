<?php
include("../sesion.php");
$idPagina = 50;

require_once RUTA_PROYECTO.'/librerias/tcpdf/tcpdf.php';

require_once("logica-cotizacion.php");

$consulta=$conexionBdAdmin->query("SELECT * FROM documentos_configuracion WHERE dconf_id_empresa= '".$idEmpresa."' AND dconf_id_documento='".ID_DOC_COTIZACION."';");
$configuracionDoc = mysqli_fetch_array($consulta, MYSQLI_BOTH);

class MIPDF extends TCPDF {

    public $cotiz_id;
    public $ruta_imagen_cabezado_1;
	public $ruta_imagen_cabezado_2;
	public $ruta_imagen_pie_pagina;

    public function Header() {
        $html = '
        <table width="100%" style="border-bottom: 1px solid #000; padding-bottom: 5px;">
            <tr>
                <td width="50%" style="text-align: left; font-size: 10px;">
                    <strong>'.$this->cotiz_id.'</strong>
                </td>
                <td width="50%" style="text-align: right; font-size: 8px;">
                    Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages().'
                </td>
            </tr>
        </table>
        ';
        $this->SetY(5);
        $this->SetFont('helvetica', '', 8);
        $this->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, 'L', true);
    }

    public function Footer() {
        $html = '
        <table width="100%" style="border-top: 1px solid #000; padding-top: 5px; margin-top: 10px;">
            <tr>
                <td width="100%" style="text-align: center; font-size: 7px; line-height: 1.2;">
                    <strong>CONDICIONES COMERCIALES</strong><br>
                    <strong>Garantía:</strong> Un (1) año por desperfectos de fabricación para equipos y tres (3) meses para accesorios<br>
                    <strong>Tiempo de entrega:</strong> Si se encuentra en stock sería inmediata, de lo contrario 20 días aproximadamente.<br>
                    <strong>Bancos a nombre de JMendoza Equipos SAS</strong><br>
                    Banco Bogotá N°443028204 Cuenta Corriente<br>
                    Bancolombia N°29895245284 Cuenta Corriente
                </td>
            </tr>
        </table>
        ';
        $this->SetY(-25);
        $this->SetFont('helvetica', '', 7);
        $this->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, 'L', true);
    }
}

$pdf = new MIPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
$pdf->cotiz_id = $resultado['cotiz_id'];
$pdf->ruta_imagen_cabezado_1 = RUTA_PROYECTO.'/usuarios/images/'.$configuracion['conf_encabezado_cotizacion'];
$pdf->ruta_imagen_cabezado_2 = RUTA_PROYECTO.'/usuarios/reportes/0033a0.png';
$pdf->ruta_imagen_pie_pagina = RUTA_PROYECTO.'/usuarios/images/'.$configuracion['conf_pie_cotizacion'];

$pdf->SetCreator('Mi Aplicación');
$pdf->SetTitle('Cotización '.$resultado['cotiz_id'].' ('.$resultado['cotiz_fecha_propuesta'].') - '.$resultado['cli_nombre']);

$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(TRUE, 30);
$pdf->AddPage();

// Header section
$html = '
<table width="100%" style="border-bottom: 1px solid #000; padding-bottom: 10px; margin-bottom: 10px;">
    <tr>
        <td width="50%">
            <img src="'.$pdf->ruta_imagen_cabezado_1.'" style="max-width: 100px; height: auto; margin-bottom: 5px;"><br>
            <h3 style="margin: 0; font-size: 14px;">'.$configuracion['conf_empresa'].'</h3>
            <p style="margin: 5px 0; font-size: 10px;">NIT: '.$configuracion['conf_nit'].'</p>
            <p style="margin: 5px 0; font-size: 10px;">Teléfono: '.$configuracion['conf_telefono'].'</p>
            <p style="margin: 5px 0; font-size: 10px;">Email: '.$configuracion['conf_email'].'</p>
        </td>
        <td width="50%" style="text-align: right;">
            <h2 style="margin: 0; font-size: 16px;">COTIZACIÓN #'.$resultado['cotiz_id'].'</h2>
            <p style="margin: 5px 0; font-size: 10px;"><strong>Fecha Propuesta:</strong> '.$resultado['cotiz_fecha_propuesta'].'</p>
            <p style="margin: 5px 0; font-size: 10px;"><strong>Fecha Vencimiento:</strong> '.$resultado['cotiz_fecha_vencimiento'].'</p>
            <p style="margin: 5px 0; font-size: 10px;"><strong>Cliente:</strong> '.strtoupper($resultado['cli_nombre']).'</p>
            <p style="margin: 5px 0; font-size: 10px;"><strong>NIT:</strong> '.$resultado['cli_usuario'].'</p>
        </td>
    </tr>
</table>
';

$pdf->SetFont('helvetica', '', 10);
$pdf->writeHTML($html, true, false, true, false, '');

// Client details
$html = '
<table width="100%" style="margin-bottom: 15px;">
    <tr>
        <td width="50%">
            <p style="margin: 5px 0; font-size: 10px;"><strong>Dirección:</strong> '.$resultado['cli_direccion'].'</p>
            <p style="margin: 5px 0; font-size: 10px;"><strong>Teléfono:</strong> '.$resultado['cli_telefono'].'</p>
        </td>
        <td width="50%">
            <p style="margin: 5px 0; font-size: 10px;"><strong>Celular:</strong> '.$resultado['cli_celular'].'</p>
            <p style="margin: 5px 0; font-size: 10px;"><strong>Email:</strong> '.$resultado['cont_email'].'</p>
            <p style="margin: 5px 0; font-size: 10px;"><strong>Contacto:</strong> '.$resultado['cont_nombre'].'</p>
        </td>
    </tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');

// Provider info if applicable
$cotizacionEnPresentacion = '';
if ($configuracion['conf_proveedor_cotizacion'] == 1) {
	$cotizacionEnPresentacion = '<p style="font-size: 10px; margin-bottom: 15px;">
		<strong>Negocio en representación comercial de:</strong><br>
		DNI: '.strtoupper($proveedor['prov_documento']). " -- " .strtoupper($proveedor['prov_nombre']).'
	</p>';
}

if ($cotizacionEnPresentacion) {
    $pdf->writeHTML($cotizacionEnPresentacion, true, false, true, false, '');
}

// Items table
$html = '
<h4 style="margin-bottom: 10px; font-size: 12px;">Detalles de la Cotización:</h4>
<table width="100%" style="border-collapse: collapse; margin-bottom: 15px;">
    <thead>
        <tr style="background-color: #f0f0f0;">
            <th style="border: 1px solid #000; padding: 5px; font-size: 10px; text-align: center;" width="5%">#</th>
            <th style="border: 1px solid #000; padding: 5px; font-size: 10px;" width="50%">Descripción</th>
            <th style="border: 1px solid #000; padding: 5px; font-size: 10px; text-align: center;" width="10%">Cant</th>
            <th style="border: 1px solid #000; padding: 5px; font-size: 10px; text-align: right;" width="15%">Precio Unit.</th>
            <th style="border: 1px solid #000; padding: 5px; font-size: 10px; text-align: center;" width="10%">IVA</th>
            <th style="border: 1px solid #000; padding: 5px; font-size: 10px; text-align: center;" width="10%">Dto</th>
        </tr>
    </thead>
    <tbody>';

		// Items loop
		$no = 1;
		$productosDetalles = array();
		$productos = $conexionBdPrincipal->query("
												SELECT * FROM productos
												INNER JOIN productos_categorias ON catp_id=prod_categoria
												INNER JOIN cotizacion_productos ON czpp_producto=prod_id AND czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo='".CZPP_TIPO_COTZ."'
												WHERE prod_id_empresa='".$idEmpresa."'
												ORDER BY czpp_orden");
		$totalIva = 0;
		$subtotal=0;
		$totalDescuento=0;

		while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
			require("logica-cotizacion-items.php");

			$descripcionCombo = $prod['prod_nombre'];
			if (!empty($prod['czpp_observacion'])) {
				$descripcionCombo .= '<br><span style="font-size: 8px; color: #666;">Obs: '.$prod['czpp_observacion'].'</span>';
			}

			// Store product details for potential separate page
			$productosDetalles[] = array(
				'tipo' => 'Producto',
				'nombre' => $prod['prod_nombre'],
				'descripcion_corta' => isset($prod['prod_descripcion_corta']) ? $prod['prod_descripcion_corta'] : '',
				'observacion' => $prod['czpp_observacion']
			);

			$html .= '
			<tr>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px; text-align: center;" width="5%">'.$no.'</td>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px;" width="50%">'.$descripcionCombo.'</td>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px; text-align: center;" width="10%">'.$prod['czpp_cantidad'].'</td>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px; text-align: right;" width="15%">'.$simbolosMonedas[$resultado['cotiz_moneda']].number_format(floatval(str_replace(',', '.', $prod['czpp_valor'])), 0, ',', '.').'</td>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px; text-align: center;" width="10%">'.$prod['czpp_impuesto'].'%</td>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px; text-align: center;" width="10%">'.$prod['czpp_descuento'].'%</td>
			</tr>';
			$no++;
		}

		// Combos
		$productos = $conexionBdPrincipal->query("
												SELECT * FROM combos
												INNER JOIN cotizacion_productos ON czpp_combo=combo_id AND czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo='".CZPP_TIPO_COTZ."'
												WHERE combo_id_empresa='".$idEmpresa."'
												ORDER BY czpp_orden");

		while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
			require("logica-cotizacion-items.php");

			$descripcionCombo = $prod['combo_nombre'];
			if (!empty($prod['czpp_observacion'])) {
				$descripcionCombo .= '<br><span style="font-size: 8px; color: #666;">Obs: '.$prod['czpp_observacion'].'</span>';
			}

			// Store combo details for potential separate page
			$productosDetalles[] = array(
				'tipo' => 'Combo',
				'nombre' => $prod['combo_nombre'],
				'descripcion_corta' => $prod['combo_descripcion'],
				'observacion' => $prod['czpp_observacion']
			);

			$html .= '
			<tr>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px; text-align: center;" width="5%">'.$no.'</td>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px;" width="50%">'.$descripcionCombo.'</td>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px; text-align: center;" width="10%">'.$prod['czpp_cantidad'].'</td>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px; text-align: right;" width="15%">'.$simbolosMonedas[$resultado['cotiz_moneda']].number_format(floatval(str_replace(',', '.', $prod['czpp_valor'])), 0, ',', '.').'</td>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px; text-align: center;" width="10%">'.$prod['czpp_impuesto'].'%</td>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px; text-align: center;" width="10%">'.$prod['czpp_descuento'].'%</td>
			</tr>';
			$no++;
		}

		// Services
		$productos = $conexionBdPrincipal->query("
												SELECT * FROM servicios
												INNER JOIN cotizacion_productos ON czpp_servicio=serv_id AND czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo='".CZPP_TIPO_COTZ."'
												WHERE serv_id_empresa='".$idEmpresa."'
												ORDER BY czpp_orden");

		while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
			require("logica-cotizacion-items.php");

			$descripcionCombo = $prod['serv_nombre'];
			if (!empty($prod['czpp_observacion'])) {
				$descripcionCombo .= '<br><span style="font-size: 8px; color: #666;">Obs: '.$prod['czpp_observacion'].'</span>';
			}

			// Store service details for potential separate page
			$productosDetalles[] = array(
				'tipo' => 'Servicio',
				'nombre' => $prod['serv_nombre'],
				'descripcion_corta' => '',
				'observacion' => $prod['czpp_observacion']
			);

			$html .= '
			<tr>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px; text-align: center;" width="5%">'.$no.'</td>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px;" width="50%">'.$descripcionCombo.'</td>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px; text-align: center;" width="10%">'.$prod['czpp_cantidad'].'</td>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px; text-align: right;" width="15%">'.$simbolosMonedas[$resultado['cotiz_moneda']].number_format(floatval(str_replace(',', '.', $prod['czpp_valor'])), 0, ',', '.').'</td>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px; text-align: center;" width="10%">'.$prod['czpp_impuesto'].'%</td>
				<td style="border: 1px solid #000; padding: 5px; font-size: 9px; text-align: center;" width="10%">'.$prod['czpp_descuento'].'%</td>
			</tr>';
			$no++;
		}

		if($resultado['cotiz_envio']==''){$envio=0;}else{$envio=$resultado['cotiz_envio'];}
		$total = $subtotal- $totalDescuento + $totalIva + $envio;

		$subTotal = isset($subtotal)? $subtotal : 0;
		$totalDescuento = isset($totalDescuento)? $totalDescuento : 0;
		$totalIvaP = isset($totalIva)? $totalIva : 0;
		$totalEnvioP = isset($resultado)? $resultado['cotiz_envio'] : 0;
		$totalPagarP = $total;

$html .= '
    </tbody>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Totals table
$html = '
<table width="100%" style="border-collapse: collapse; margin-bottom: 20px;">
    <tr>
        <td width="45%"></td>
        <td width="30%" style="border: 1px solid #000; padding: 12px; font-size: 11px; text-align: right; background-color: #f9f9f9;"><strong>Subtotal:</strong></td>
        <td width="25%" style="border: 1px solid #000; padding: 12px; font-size: 11px; text-align: right;">'.$simbolosMonedas[$resultado['cotiz_moneda']].number_format($subTotal, 0, ',', '.').'</td>
    </tr>
    <tr>
        <td></td>
        <td style="border: 1px solid #000; padding: 12px; font-size: 11px; text-align: right; background-color: #f9f9f9;"><strong>Descuento:</strong></td>
        <td style="border: 1px solid #000; padding: 12px; font-size: 11px; text-align: right;">-'.$simbolosMonedas[$resultado['cotiz_moneda']].number_format($totalDescuento, 0, ',', '.').'</td>
    </tr>
    <tr>
        <td></td>
        <td style="border: 1px solid #000; padding: 12px; font-size: 11px; text-align: right; background-color: #f9f9f9;"><strong>IVA:</strong></td>
        <td style="border: 1px solid #000; padding: 12px; font-size: 11px; text-align: right;">'.$simbolosMonedas[$resultado['cotiz_moneda']].number_format($totalIvaP, 0, ',', '.').'</td>
    </tr>
    <tr>
        <td></td>
        <td style="border: 1px solid #000; padding: 12px; font-size: 11px; text-align: right; background-color: #f9f9f9;"><strong>Envío:</strong></td>
        <td style="border: 1px solid #000; padding: 12px; font-size: 11px; text-align: right;">'.$simbolosMonedas[$resultado['cotiz_moneda']].number_format(floatval($totalEnvioP), 0, ',', '.').'</td>
    </tr>
    <tr>
        <td></td>
        <td style="border: 1px solid #000; background-color: #e9ecef; padding: 15px; font-size: 12px; text-align: right;"><strong>Total a Pagar:</strong></td>
        <td style="border: 1px solid #000; background-color: #e9ecef; padding: 15px; font-size: 12px; text-align: right;"><strong>'.$simbolosMonedas[$resultado['cotiz_moneda']].number_format($totalPagarP, 0, ',', '.').'</strong></td>
    </tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');

// Observations
if ($resultado['cotiz_observaciones']) {
    $html = '
    <p style="font-size: 10px; margin-bottom: 15px;"><strong>Observaciones:</strong> '.$resultado['cotiz_observaciones'].'</p>
    ';
    // Force page break before additional terms to keep them on same page
    $pdf->AddPage();

    // Additional commercial terms
    $html = '
    <h4 style="margin: 10px 0 10px 0; font-size: 12px;">Términos y Condiciones Adicionales:</h4>
    <div style="border: 1px solid #000; padding: 15px; background-color: #f9f9f9; margin-bottom: 20px;">
        <ul style="margin: 0; padding-left: 20px; font-size: 10px; line-height: 1.4;">
            <li style="margin-bottom: 8px;">Por pagos realizados con Tarjetas de Crédito agregar 4% de servicios bancarios al valor neto de la cotización.</li>
            <li style="margin-bottom: 8px;">Por pagos realizados con Tarjetas de Débito agregar 2% de servicios bancarios al valor neto de la cotización.</li>
            <li style="margin-bottom: 8px;">Transferencias Bancarias NO tienen Gastos adicionales.</li>
            <li style="margin-bottom: 8px;">Después de Aprobada la cotización no se aceptan devoluciones o reversiones pactadas dentro de ella.</li>
            <li>Las Garantías serán cubiertas después de haber recibido los equipos o accesorios en nuestros centros autorizados de servicios para revisión y aprobación por garantía.</li>
        </ul>
    </div>
    ';

    $pdf->writeHTML($html, true, false, true, false, '');
}

// Commercial conditions are now in the footer

// Add comprehensive details page for all items and general observations
$hasDetails = false;
foreach ($productosDetalles as $item) {
    if (!empty($item['descripcion_corta']) || !empty($item['observacion'])) {
        $hasDetails = true;
        break;
    }
}

if ($hasDetails || !empty($resultado['cotiz_observaciones'])) {
    $pdf->AddPage();
    $html = '
    <h3 style="margin-bottom: 15px; font-size: 14px; text-align: center;">Detalles Completos de la Cotización</h3>
    ';

    // Item details
    if ($hasDetails) {
        $html .= '<h4 style="margin: 15px 0 10px 0; font-size: 12px;">Detalles de Productos, Servicios y Combos:</h4>';
        foreach ($productosDetalles as $item) {
            if (!empty($item['descripcion_corta']) || !empty($item['descripcion_larga']) || !empty($item['observacion'])) {
                $html .= '
                <div style="margin-bottom: 15px; border: 1px solid #000; padding: 8px;">
                    <h5 style="margin: 0 0 8px 0; font-size: 11px; color: #333;"><strong>['.$item['tipo'].']</strong> '.$item['nombre'].'</h5>
                ';
                if (!empty($item['descripcion_corta'])) {
                    $html .= '<p style="margin: 3px 0; font-size: 9px;"><strong>Descripción:</strong> '.$item['descripcion_corta'].'</p>';
                }
                if (!empty($item['observacion'])) {
                    $html .= '<p style="margin: 3px 0; font-size: 9px;"><strong>Observación:</strong> '.$item['observacion'].'</p>';
                }
                $html .= '</div>';
            }
        }
    }

    $pdf->writeHTML($html, true, false, true, false, '');
}

// Output PDF
$pdf->Output('Cotización '.$resultado['cotiz_id'].' ('.$resultado['cotiz_fecha_propuesta'].') - '.$resultado['cli_nombre'].'.pdf', 'I');