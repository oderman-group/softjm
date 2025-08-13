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
            <table width="100%">
                <tr>
                    <td width="100%" align="center">
                        <img src="'. $this->ruta_imagen_cabezado_1.'"/><br>
                        <img src="'. $this->ruta_imagen_cabezado_2.'"/>
                    </td>
                </tr>
            </table>
            <hr style="color:#dee2e6;">			
        ';

        // Ajustar posición
        $this->SetY(5);
        $this->SetFont('helvetica', '', 10);
        $this->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, 'L', true);

		// numero de cotización
		$this->SetY(40); 
        $this->SetFont('helvetica', '', 17);

		$html = '<span style="color: white;"># '.$this->cotiz_id.'</span>';
        
        $this->writeHTMLCell(0, 0, 170, '', $html, 0, 1, 0, true, 'C', true);

		//  raya horizontal
		$this->SetY(48); 
        $this->SetFont('helvetica', '', 8);

        $html = ' <hr style="color:#dee2e6;">';

        $this->writeHTMLCell(0, 0, 170, '', $html, 0, 1, 0, true, 'R', true);

		// pie de página
		$this->SetY(49);
        $this->SetFont('helvetica', '', 7);

		$html = '<span style="color: white;">Página '.$this->getAliasNumPage() . ' de ' . $this->getAliasNbPages().'</span>';
        
        $this->writeHTMLCell(0, 0, 170, '', $html, 0, 1, 0, true, 'R', true);
    }

    public function Footer() {
       
		$this->SetY(-45); // Posición desde el fondo
        $this->SetFont('helvetica', '', 8);

        $html = ' <hr style="color:#dee2e6;">';

        $this->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

		$this->SetY(-44);

        // Ancho disponible
        $pageWidth = $this->getPageWidth();
        $leftMargin = $this->getMargins()['left'];
        $rightMargin = $this->getMargins()['right'];
        $usableWidth = $pageWidth - $leftMargin - $rightMargin;

        // Ruta de imagen
        $footerImage = $this->ruta_imagen_pie_pagina;

        // Dibujar la imagen (X, Y, ancho)
        $this->Image($footerImage, $leftMargin, $this->GetY(), $usableWidth, '', '', '', 'L', false, 300);
    }
}

$pdf = new MIPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
$pdf->cotiz_id = $resultado['cotiz_id'];
$pdf->ruta_imagen_cabezado_1 = RUTA_PROYECTO.'/usuarios/images/'.$configuracion['conf_encabezado_cotizacion'];
$pdf->ruta_imagen_cabezado_2 = RUTA_PROYECTO.'/usuarios/reportes/0033a0.png';
$pdf->ruta_imagen_pie_pagina = RUTA_PROYECTO.'/usuarios/images/'.$configuracion['conf_pie_cotizacion'];


$pdf->SetCreator('Mi Aplicación');
$pdf->SetTitle('Cotización '.$resultado['cotiz_id'].' ('.$resultado['cotiz_fecha_propuesta'].') - '.$resultado['cli_nombre']);

$pdf->SetMargins(10, 55, 10); // Espacio para encabezado
$pdf->SetAutoPageBreak(TRUE, 48);
$pdf->AddPage();

$cotizacionEnPresentacion = '';

if ($configuracion['conf_proveedor_cotizacion'] == 1) {
	$cotizacionEnPresentacion .= '<p  align="center">
			<span style="font-size: 10px; font-weight: bold;">NEGOCIO EN REPRESENTACIÓN COMERCIAL DE</span><br> '
			.' DNI: '.strtoupper($proveedor['prov_documento']). " -- " .strtoupper($proveedor['prov_nombre']).'
		</p>';
}

// Estilos y encabezado de la tabla
$html = '
<table width="100%" cellpadding="2">
	<tr>
		<td width="100%" align="left">
			<span style="font-weight: bold;font-size: 13;">' . strtoupper($resultado['cli_nombre']) . '</span>
		</td>
	</tr>
	<tr>
		<td width="70%" align="left">
			<p>
				<strong>NIT: </strong>'.$resultado['cli_usuario'].'<br>
				<strong>DIRECCIÓN: </strong>'.$resultado['cli_direccion'].'<br>
				<strong>EMAIL: </strong>'.$resultado['cont_email'].'<br>
				<strong>TELÉFONO: </strong>'.$resultado['cli_telefono'].'<br>
				<strong>CELULAR: </strong>'.$resultado['cli_celular'].'<br>
				<strong>CONTACTO: </strong>'.$resultado['cont_nombre'].'

			</p>
		</td>
		<td width="30%" align="right">
			<p >
				<strong>FECHA PROPUESTA:</strong>'.$resultado['cotiz_fecha_propuesta'].'<br>
				<strong>FECHA VENCIMIENTO:</strong>'.$resultado['cotiz_fecha_vencimiento'].'<br>
				<strong>FORMA DE PAGO:</strong>'.$formaPago[$resultado['cotiz_forma_pago']].'<br>
				<strong>VENDEDOR:</strong>'.$resultado['usr_nombre'].'<br>
				<strong>EMAIL:</strong>'.$resultado['usr_email'].'
			</p>
		</td>
	</tr>
</table>
<br>
'.$cotizacionEnPresentacion.'
<br>
<table  cellpadding="5" style="width:100%; border-collapse: collapse;" >
    <thead>
        <tr style="line-height:10px;background-color: #e9ecef; font-weight: bold;">
            <th style="border: 1px solid #dee2e6;" scope="col" width="20px">#</th>
            <th style="border: 1px solid #dee2e6;" scope="col" width="255px">Producto/Servicio</th>
            <th style="border: 1px solid #dee2e6;" scope="col" width="40px" align="right">Cant</th>
            <th style="border: 1px solid #dee2e6;" scope="col" width="80px" align="right">Precio Unitario</th>
			<th style="border: 1px solid #dee2e6;" scope="col" width="40px" align="right">IVA</th>
			<th style="border: 1px solid #dee2e6;" scope="col" width="40px" align="right">Dcto</th>
            <th style="border: 1px solid #dee2e6;" scope="col" width="80px" align="right">Precio Total</th>
        </tr>
    </thead>
    <tbody>';

		//<!-- COMBOS -->
		$no = 1;
		$productos = $conexionBdPrincipal->query("
													SELECT * FROM combos 
													INNER JOIN cotizacion_productos ON czpp_combo=combo_id AND czpp_cotizacion='" . $_GET["id"] . "'
													WHERE combo_id_empresa='".$idEmpresa."'
													ORDER BY czpp_orden");
		$totalIva = 0;
		$subtotal=0;
		$totalDescuento=0;

		while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {

			require("logica-cotizacion-items.php");

			$precioNormalCombo = mysqli_fetch_array($conexionBdPrincipal->query("
																				SELECT SUM(copp_cantidad*prod_precio) FROM combos_productos
																				INNER JOIN productos ON prod_id=copp_producto
																				WHERE copp_combo='".$prod['combo_id']."'"), MYSQLI_BOTH);

			$infoComboDescuento = '';
			if($prod['combo_descuento'] !="" and $resultado['cotiz_ocultar_descuento_combo']=='0'){
				$infoComboDescuento ='
				<span><b>Precio Normal:</b> $'.number_format($precioNormalCombo[0],0,".",".").'</span><br>
				<span><b>Descuento:</b>'.$prod['combo_descuento'].'%</span><br>';
			}

			$infoComboProductos = '';
			$productosCombo = $conexionBdPrincipal->query("
														SELECT prod_id, prod_nombre, copp_cantidad FROM productos 
														INNER JOIN combos_productos ON copp_producto=prod_id AND copp_combo='" . $prod['combo_id'] . "'
														WHERE prod_id_empresa='".$idEmpresa."'
														ORDER BY copp_id");
			$c = 1;
			while ($prodCombo = mysqli_fetch_array($productosCombo, MYSQLI_BOTH)) {
				if ($c == 1) {
					$infoComboProductos .= "<br><b>INCLUYE:</b>";
				}
				$infoComboProductos .= " <br> * (" . $prodCombo['copp_cantidad'] . " Unds) " . $prodCombo['prod_nombre'];
				$c++;
			}

			$descripcionCombo = $prod['combo_nombre'].'<br>'.$infoComboDescuento.'
				<span style="font-size: 9px; color: darkblue;">'.$prod['combo_descripcion'].'</span><br>
				<span style="font-size: 7px; color: teal;">'.$infoComboProductos.'</span><br>
				<span style="font-size: 9px; color: darkblue;">'.$prod['czpp_observacion'].'</span>
			';

			$html .= '
			<tr>
				<th style="border: 1px solid #dee2e6;" scope="row" width="20px">'.$no.'</th>
				<td style="border: 1px solid #dee2e6;text-align: justify;" width="255px">'.$descripcionCombo.'</td>
				<td style="border: 1px solid #dee2e6;" width="40" align="right">'.$prod['czpp_cantidad'].'</td>
				<td style="border: 1px solid #dee2e6;" width="80" align="right">'.$simbolosMonedas[$resultado['cotiz_moneda']].number_format($prod['czpp_valor'], 0, ',', '.').'</td>
				<td style="border: 1px solid #dee2e6;" width="40" align="right">'.$prod['czpp_impuesto'].'%</td>
				<td style="border: 1px solid #dee2e6;" width="40" align="right">'.$prod['czpp_descuento'].'%</td>
				<td style="border: 1px solid #dee2e6;" width="80" align="right">'.$simbolosMonedas[$resultado['cotiz_moneda']].number_format($valorTotal, 0, ',', '.').'</td>
			</tr>';
			$no++;
		}

		//<!-- PRODUCTOS -->
		$productos = $conexionBdPrincipal->query("
												SELECT * FROM productos 
												INNER JOIN productos_categorias ON catp_id=prod_categoria
												INNER JOIN cotizacion_productos ON czpp_producto=prod_id AND czpp_cotizacion='" . $_GET["id"] . "'
												WHERE prod_id_empresa='".$idEmpresa."'
												ORDER BY czpp_orden");
		while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {

			require("logica-cotizacion-items.php");

			$descripcionComboCorta = '';
			if(isset($prod['prod_descripcion_corta']) && $prod['prod_descripcion_corta'] !=''){
				$descripcionComboCorta = '<br><span style="font-size: 9px; color: #0033a0;">'.$prod['prod_descripcion_corta'].'</span>';
			}

			$descripcionComboObservacion = "";
			if(isset($prod['czpp_observacion']) && $prod['czpp_observacion'] !=''){
				$descripcionComboObservacion = '<br><span style="font-size: 9px; color: #0033a0;">'.$prod['czpp_observacion'].'</span>';
			}

			$descripcionCombo = $prod['prod_nombre'].$descripcionComboCorta.$descripcionComboObservacion;

			$html .= '
			<tr>
				<th style="border: 1px solid #dee2e6;" scope="row" width="20px">'.$no.'</th>
				<td style="border: 1px solid #dee2e6;text-align: justify;" width="255px">'.$descripcionCombo.'</td>
				<td style="border: 1px solid #dee2e6;" width="40" align="right">'.$prod['czpp_cantidad'].'</td>
				<td style="border: 1px solid #dee2e6;" width="80" align="right">'.$simbolosMonedas[$resultado['cotiz_moneda']].number_format($prod['czpp_valor'], 0, ',', '.').'</td>
				<td style="border: 1px solid #dee2e6;" width="40" align="right">'.$prod['czpp_impuesto'].'%</td>
				<td style="border: 1px solid #dee2e6;" width="40" align="right">'.$prod['czpp_descuento'].'%</td>
				<td style="border: 1px solid #dee2e6;" width="80" align="right">'.$simbolosMonedas[$resultado['cotiz_moneda']].number_format($valorTotal, 0, ',', '.').'</td>
			</tr>';
			$no++;
		}

		//<!-- SERVICIOS -->
		$productos = $conexionBdPrincipal->query("
												SELECT * FROM servicios
												INNER JOIN cotizacion_productos ON czpp_servicio=serv_id AND czpp_cotizacion='" . $_GET["id"] . "'
												WHERE serv_id_empresa='".$idEmpresa."'
												ORDER BY czpp_orden");
		while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {

			require("logica-cotizacion-items.php");

			$descripcionComboObservacion = '';
			if(isset($prod['czpp_observacion']) && $prod['czpp_observacion'] !=''){
				$descripcionComboObservacion = '<br><span style="font-size: 9px; color: #0033a0;">'.$prod['czpp_observacion'].'</span><br>';
			}

			$descripcionCombo = $prod['serv_nombre'].$descripcionComboObservacion;

			$html .= '
			<tr>
				<th style="border: 1px solid #dee2e6;" scope="row" width="20px">'.$no.'</th>
				<td style="border: 1px solid #dee2e6;" width="255px">'.$descripcionCombo.'</td>
				<td style="border: 1px solid #dee2e6;" width="40" align="right">'.$prod['czpp_cantidad'].'</td>
				<td style="border: 1px solid #dee2e6;" width="80" align="right">'.$simbolosMonedas[$resultado['cotiz_moneda']].number_format($prod['czpp_valor'], 0, ',', '.').'</td>
				<td style="border: 1px solid #dee2e6;" width="40" align="right">'.$prod['czpp_impuesto'].'%</td>
				<td style="border: 1px solid #dee2e6;" width="40" align="right">'.$prod['czpp_descuento'].'%</td>
				<td style="border: 1px solid #dee2e6;" width="80" align="right">'.$simbolosMonedas[$resultado['cotiz_moneda']].number_format($valorTotal, 0, ',', '.').'</td>
			</tr>';
			$no++;
		}

		if($resultado['cotiz_envio']==''){$envio=0;}else{$envio=$resultado['cotiz_envio'];}
		$total = $subtotal- $totalDescuento + $totalIva + $envio;
		

		$subTotal = isset($subtotal)? $subtotal : 0;
		$totalDescuento = isset($totalDescuento)? $totalDescuento : 0;
		$totalDescuentoPorcentaje = 0;
		$totalIvaP = isset($totalIva)? $totalIva : 0;
		$totalEnvioP = isset($resultado)? $resultado['cotiz_envio'] : 0;
		$totalPagarP = $total;

$html .= '
        <hr style="color:#dee2e6;line-height:5px;">
        <tfoot>
            <tr style="line-height:8px">
                <th colspan="5" rowspan="5" align="left" width="395px" style="font-weight: bold;border: 1px solid #dee2e6;">
                   <strong>Observaciones:</strong>
                </th>
                <th style="font-weight: bold;border: 1px solid #dee2e6;" width="80px" align="right">Subtotal:</th>
                <td align="right" style="border: 1px solid #dee2e6;" width="80px">$'.number_format($subTotal, 0, ',', '.').'</td>
            </tr>
            <tr style="line-height:8px;">
                <th style="font-weight: bold;border: 1px solid #dee2e6;" align="right">Descuento:</th>
                <td align="right" style="border: 1px solid #dee2e6;">-$'.number_format($totalDescuento, 0, ',', '.').'</td>
            </tr>
            <tr style="line-height:8px;">
                <th style="font-weight: bold;border: 1px solid #dee2e6;" align="right">IVA:</th>
                <td align="right" style="border: 1px solid #dee2e6;">$'.number_format($totalIvaP, 0, ',', '.').'</td>
            </tr>
			 <tr style="line-height:8px;">
                <th style="font-weight: bold;border: 1px solid #dee2e6;" align="right">ENVÍO:</th>
                <td align="right" style="border: 1px solid #dee2e6;">$'.number_format(floatval($totalEnvioP), 0, ',', '.').'</td>
            </tr>
            <tr style="line-height:8px;"> 
                <th style="font-weight: bold;border: 1px solid #dee2e6;background-color:#0033a0;color:white;" align="right">Total a Pagar:</th>
                <td style="font-weight: bold;border: 1px solid #dee2e6;background-color:#0033a0;color:white;" align="right">$'.number_format($totalPagarP, 0, ',', '.').'</td>
            </tr>
        </tfoot>
    </tbody>
</table>';


$pdf->SetFont('helvetica', '', 8);
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Ln(5);

$htmlFirma = '
<table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
        <td width="100%" align="center">
			<img src="condicionesCoti.png" >
        </td>
    </tr>
</table>';

$pdf->writeHTML($htmlFirma, true, false, true, false, '');

//echo $html;

// Salida del PDF
$pdf->Output('Cotización '.$resultado['cotiz_id'].' ('.$resultado['cotiz_fecha_propuesta'].') - '.$resultado['cli_nombre'].'.pdf', 'I');
