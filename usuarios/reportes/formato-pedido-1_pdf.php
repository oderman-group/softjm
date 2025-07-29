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

    public function Header() {

        $html = '
            <table width="100%" cellpadding="2">
                <tr>
                    <td width="50%" align="left">
                        <h2 class="mb-1">' . $this->conf_empresa . '</h2>
                        <p class="mb-0" style="line-height:3px;">Nit: ' . $this->conf_nit . '</p>
                        <p class="mb-0" style="line-height:3px;">Teléfono: ' . $this->conf_telefono . '</p>
                        <p class="mb-0" style="line-height:3px;">Email: ' . $this->conf_email . '</p>
                    </td>
                    <td width="50%" align="right">
                        <h2 class="mb-1">PEDIDO #' . $this->pedid_numero . '</h2>
                        <p class="mb-0" style="line-height:3px;"><strong>Fecha del Pedido:</strong> ' . $this->pedid_fecha_propuesta . '</p>
                        <p class="mb-0" style="line-height:3px;"><strong>Cliente:</strong> ' . $this->cli_nombre . '</p>
                        <p class="mb-0" style="line-height:3px;"><strong>NIT/C.C.:</strong> ' . $this->cli_usuario . '</p>
                        <p class="mb-0" style="line-height:3px;"><strong>Dirección Cliente:</strong> ' . $this->cli_direccion . '</p>
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
        $this->SetY(-25); // Posición desde el fondo
        $this->SetFont('helvetica', '', 8);

        $html = '
        <hr style="color:#dee2e6;">
        <table width="100%" cellpadding="0">            
            <tr>
                <td width="33%" align="left">
                </td>
                <td width="34%" align="center">
                   <p>¡Gracias por tu pedido!</p>
                    <p>Visítanos en: ' . $this->conf_web . '</p>
                </td>
                <td width="33%" align="right">
                    <p></p>
                    <p></p>
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

$pdf->SetCreator('Mi Aplicación');
$pdf->SetTitle('Impresión de Pedido - #'.$datosPedido[Pedido::$primaryKey]);

$pdf->SetMargins(15, 50, 15); // Espacio para encabezado
$pdf->SetAutoPageBreak(TRUE, 30);
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
                <th style="font-weight: bold;border: 1px solid #dee2e6;" align="right">Descuento (<?=$totalDescuentoPorcentaje;?>%):</th>
                <td align="right" style="border: 1px solid #dee2e6;">-$'.number_format($totalDescuento, 0, ',', '.').'</td>
            </tr>
            <tr style="line-height:8px;">
                <th style="font-weight: bold;border: 1px solid #dee2e6;" align="right">IVA (19%):</th>
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


