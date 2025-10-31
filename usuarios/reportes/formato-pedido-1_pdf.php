<?php
require_once '../sesion.php';
$idPagina = 373;

require_once RUTA_PROYECTO.'/usuarios/class/Pedido.php';
require_once RUTA_PROYECTO.'/usuarios/class/Cliente.php';
require_once RUTA_PROYECTO.'/usuarios/class/Contacto.php';
require_once RUTA_PROYECTO.'/usuarios/class/ItemAsociado.php';
require_once RUTA_PROYECTO.'/librerias/tcpdf/tcpdf.php';

$predicado = [
    'pedid_id' => $_GET["id"],
    'pedid_id_empresa' => $idEmpresa
];

$consultaPedido = Pedido::Select($predicado);
$datosPedido = mysqli_fetch_array($consultaPedido, MYSQLI_BOTH);

//Cliente
$predicado = [
    Cliente::$primaryKey => $datosPedido['pedid_cliente'],
    'cli_id_empresa' => $idEmpresa
];

$consultaCliente = Cliente::Select($predicado);
$datosCliente = mysqli_fetch_array($consultaCliente, MYSQLI_BOTH);

//Vendedor
$nombreVendedor = "No asignado";
if (!empty($datosPedido['pedid_vendedor'])) {
    $consultaVendedor = mysqli_query($conexionBdPrincipal, "SELECT usr_nombre FROM usuarios WHERE usr_id='" . $datosPedido['pedid_vendedor'] . "' AND usr_id_empresa='".$idEmpresa."'");
    if ($consultaVendedor && mysqli_num_rows($consultaVendedor) > 0) {
        $datosVendedor = mysqli_fetch_array($consultaVendedor, MYSQLI_BOTH);
        $nombreVendedor = $datosVendedor['usr_nombre'];
    }
}

class MIPDF extends TCPDF {

    public $conf_empresa;
    public $conf_nit;
    public $conf_telefono;
    public $conf_email;
    public $pedid_numero;
    public $pedid_fecha_propuesta;
    public $cli_nombre;
    public $cli_usuario;
    public $cli_direccion;
    public $conf_web;
    public $ruta_imagen;
    public $vendedor_nombre;

    public function Header() {

        $html = '
            <table width="100%" cellpadding="3" cellspacing="0">
                <tr>
                    <td width="50%" align="left" valign="top">
                        <span ><img src="'. $this->ruta_imagen.'" width="109"></span><br>
                        <p class="mb-0" style="line-height:1px;"><strong>Nit:</strong> ' . $this->conf_nit . '</p>
                        <p class="mb-0" style="line-height:1px;"><strong>Teléfono:</strong> ' . $this->conf_telefono . '</p>
                        <p class="mb-0" style="line-height:1px;"><strong>Email:</strong> ' . $this->conf_email . '</p>
                        <br>
                        <p class="mb-0" style="line-height:1px;"><strong>Vendedor:</strong> ' . $this->vendedor_nombre . '</p>
                    </td>
                    <td width="50%" align="right" valign="top">
                        <br>
                        <span style="font-weight: bold;font-size: 20;"> PEDIDO #' . $this->pedid_numero . '</span><br>
                        <p style="line-height:11px;margin:2px 0 1px 0;"><strong>Fecha del Pedido:</strong> ' . $this->pedid_fecha_propuesta . '</p>
                        <p style="line-height:11px;margin:2px 0 1px 0;"><strong>Nit/C.C.:</strong> ' . $this->cli_usuario . '</p>
                        <p style="line-height:11px;margin:2px 0 1px 0;"><strong>Cliente:</strong> ' . $this->cli_nombre . '</p>
                        <p style="line-height:11px;margin:2px 0 1px 0;"><strong>Dirección Cliente:</strong> ' . $this->cli_direccion . '</p>
                    </td>
                </tr>
            </table>
            <br>
            <hr style="color:#dee2e6;">
        ';

        // Ajustar posición
        $this->SetY(5);
        $this->SetFont('helvetica', '', 10);
        $this->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, 'L', true);
    }

    public function Footer() {
        $this->SetY(-30); // Posición desde el fondo
        $this->SetFont('helvetica', '', 7);

        $html = '
        <hr style="color:#dee2e6;">
        <table width="100%" cellpadding="2">            
            <tr>
                <td width="25%" align="left" valign="top">
                </td>
                <td width="50%" align="center" valign="top" style="line-height:1.3;">
                   <strong style="font-size:8px;">¡Gracias por tu pedido!</strong><br>
                   Visítanos en: ' . $this->conf_web . '<br>
                   <br>
                   <strong>Bancos a nombre de JMendoza Equipos SAS</strong><br>
                   Banco Bogotá N°443028204 Cuenta Corriente<br>
                   Bancolombia N°29895245284 Cuenta Corriente
                </td>
                <td width="25%" align="right" valign="top">
                    Pág. ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages() . '
                </td>
            </tr>
        </table>';

        $this->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    }
}

$pdf = new MIPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
$pdf->conf_empresa = $configuracion['conf_empresa'];
$pdf->conf_nit = $configuracion['conf_nit'];
$pdf->conf_telefono = $configuracion['conf_telefono'];
$pdf->conf_email = $configuracion['conf_email'];
$pdf->pedid_numero = $datosPedido[Pedido::$primaryKey];
$pdf->pedid_fecha_propuesta = $datosPedido['pedid_fecha_propuesta'];
$pdf->cli_nombre = $datosCliente['cli_nombre'];
$pdf->cli_usuario = $datosCliente['cli_usuario'];
$pdf->cli_direccion = $datosCliente['cli_direccion'];
$pdf->conf_web = $configuracion['conf_web'];
$pdf->ruta_imagen = RUTA_PROYECTO.'/usuarios/files/'.$configuracion['conf_logo'];
$pdf->vendedor_nombre = $nombreVendedor;

$pdf->SetCreator('Mi Aplicación');
$pdf->SetTitle('Impresión de Pedido - #'.$datosPedido[Pedido::$primaryKey]);

$pdf->SetMargins(15, 55, 15); // Espacio para encabezado (aumentado de 50 a 55)
$pdf->SetAutoPageBreak(TRUE, 35); // Espacio para footer con información bancaria
$pdf->AddPage();

// Estilos y encabezado de la tabla
$html = '
<h3 >Detalles del Pedido:</h3>
<table  cellpadding="5" style="width:100%; border-collapse: collapse;" >
    <thead>
        <tr style="line-height:10px;background-color: #e9ecef; font-weight: bold;">
            <th style="border: 1px solid #dee2e6;" scope="col" width="20px">#</th>
            <th style="border: 1px solid #dee2e6;" scope="col" width="260px">Descripción del Producto</th>
            <th style="border: 1px solid #dee2e6;" scope="col" width="50px" align="right">Cantidad</th>
            <th style="border: 1px solid #dee2e6;" scope="col" width="100px" align="right">Precio Unitario</th>
            <th style="border: 1px solid #dee2e6;" scope="col" width="100px" align="right">Total</th>
        </tr>
    </thead>
    <tbody>';


$itemAsociado = new ItemAsociado($conexionBdPrincipal);
$listadoCombos = $itemAsociado->listadoAsociadoCombos($datosPedido[Pedido::$primaryKey], ItemAsociado::PROCESO_PEDIDO);
$listadoProductos = $itemAsociado->listadoAsociadoProductos($datosPedido[Pedido::$primaryKey], ItemAsociado::PROCESO_PEDIDO);
//$listadoServicios = $itemAsociado->listadoAsociadoServicios($datosPedido[Pedido::$primaryKey], ItemAsociado::PROCESO_PEDIDO);

$todosLosItemsParaTabla = [];

while ($combos = mysqli_fetch_array($listadoCombos, MYSQLI_BOTH)) {

    $itemActual  = [
        'nombre' => $combos['combo_nombre'],
        'cantidad' => $combos['czpp_cantidad'],
        'valor' => $combos['czpp_valor'],
        'descuento' => $combos['czpp_descuento'],
        'impuesto' => $combos['czpp_impuesto'],
        'observacion' => $combos['czpp_observacion']
    ];

    $todosLosItemsParaTabla[] = $itemActual;
}

$listadoCombos->free();

while ($producto = mysqli_fetch_array($listadoProductos, MYSQLI_BOTH)) {

    $itemActual  = [
        'nombre' => $producto['prod_nombre'],
        'cantidad' => $producto['czpp_cantidad'],
        'valor' => $producto['czpp_valor'],
        'descuento' => $producto['czpp_descuento'],
        'impuesto' => $producto['czpp_impuesto'],
        'observacion' => $producto['czpp_observacion']
    ];

    $todosLosItemsParaTabla[] = $itemActual;
}

$listadoProductos->free();

$subTotal = 0;
$totalDescuento = 0;
$totalDescuentoPorcentaje = 0;
$totalIva = 0;
$i = 1;

foreach ($todosLosItemsParaTabla as $item) {

    $totalPorItem = $item['valor'] * $item['cantidad'];
    $subTotal += $totalPorItem;

    $descuento = !empty($item['descuento']) ? $item['descuento'] : 0;

    $descuentoPorItem = ($descuento / 100) * $totalPorItem;
    $totalDescuento += $descuentoPorItem;
    $totalDescuentoPorcentaje += $descuento;

    $valorConDescuentoPorItem = $totalPorItem - $descuentoPorItem;


    $ivaPorItem = ($item['impuesto'] / 100) * $valorConDescuentoPorItem;
    $totalIva += $ivaPorItem;

    $html .= '
    <tr>
        <th style="border: 1px solid #dee2e6;" scope="row" width="20px">'.$i.'</th>
        <td style="border: 1px solid #dee2e6;" width="260px">'.$item['nombre'].'</td>
        <td style="border: 1px solid #dee2e6;" width="50" align="right">'.$item['cantidad'].'</td>
        <td style="border: 1px solid #dee2e6;" width="100px" align="right">$'.number_format($item['valor'], 0, ',', '.').'</td>
        <td style="border: 1px solid #dee2e6;" width="100px" align="right">$'.number_format($totalPorItem, 0, ',', '.').'</td>
    </tr>';

    $i++; 
} 
$totalPagar = $subTotal - $totalDescuento + $totalIva;

$html .= '
        <hr style="color:#dee2e6;line-height:5px;">
        <tfoot>
            <tr style="line-height:8px">
                <th colspan="3" rowspan="4" align="left" width="330px">
                    <p><strong>Notas del Pedido:</strong></p>
                    <p style="line-height:8px;">Favor revisar los productos al momento de la entrega. Cualquier reclamo, por favor, comunicarse dentro de las 24 horas siguientes.</p>
                </th>
                <th style="font-weight: bold;border: 1px solid #dee2e6;" width="100px" align="right">Subtotal:</th>
                <td align="right" style="border: 1px solid #dee2e6;" width="100px">$'.number_format($subTotal, 0, ',', '.').'</td>
            </tr>
            <tr style="line-height:8px;">
                <th style="font-weight: bold;border: 1px solid #dee2e6;" align="right">Descuento:</th>
                <td align="right" style="border: 1px solid #dee2e6;">-$'.number_format($totalDescuento, 0, ',', '.').'</td>
            </tr>
            <tr style="line-height:8px;">
                <th style="font-weight: bold;border: 1px solid #dee2e6;" align="right">IVA:</th>
                <td align="right" style="border: 1px solid #dee2e6;">$'.number_format($totalIva, 0, ',', '.').'</td>
            </tr>
            <tr style="line-height:8px;"> 
                <th style="font-weight: bold;border: 1px solid #dee2e6;" align="right">Total a Pagar:</th>
                <td style="font-weight: bold;border: 1px solid #dee2e6;" align="right">$'.number_format($totalPagar, 0, ',', '.').'</td>
            </tr>
        </tfoot>
    </tbody>
</table>';

// Escribir HTML al PDF
$pdf->SetFont('helvetica', '', 8);
$pdf->writeHTML($html, true, false, true, false, '');


// Salida del PDF
$pdf->Output('pedido_'.$_GET["id"].'.pdf', 'I');


//echo $html;


