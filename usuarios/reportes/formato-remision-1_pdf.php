<?php
require_once '../sesion.php';
$idPagina = 417;

require_once RUTA_PROYECTO.'/usuarios/class/Remision.php';
require_once RUTA_PROYECTO.'/usuarios/class/Cliente.php';
require_once RUTA_PROYECTO.'/usuarios/class/Contacto.php';
require_once RUTA_PROYECTO.'/usuarios/class/ItemAsociado.php';
require_once RUTA_PROYECTO.'/librerias/tcpdf/tcpdf.php';

$predicado = [
    Remision::$primaryKey => $_GET["id"],
    'remi_id_empresa' => $idEmpresa
];

$consultaPedido = Remision::Select($predicado);
$datosPedido = mysqli_fetch_array($consultaPedido, MYSQLI_BOTH);

//Cliente
$predicado = [
    Cliente::$primaryKey => $datosPedido['remi_cliente'],
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
    public $ruta_imagen;

    public function Header() {

        $html = '
            <table width="100%" cellpadding="2">
                <tr>
                    <td width="50%" align="left">
                        <span ><img src="'. $this->ruta_imagen.'" width="109"></span><br>
                        <p class="mb-0" style="line-height:1px;"><strong>Nit:</strong> ' . $this->conf_nit . '</p>
                        <p class="mb-0" style="line-height:1px;"><strong>Teléfono:</strong> ' . $this->conf_telefono . '</p>
                        <p class="mb-0" style="line-height:1px;"><strong>Email:</strong> ' . $this->conf_email . '</p>
                    </td>
                    <td width="50%" align="right">
                        <br><br>
                        <span style="font-weight: bold;font-size: 20;"> REMISIÓN #' . $this->pedid_numero . '</span><br><br>
                        <p class="mb-0" style="line-height:3px;margin:1px"><strong>Fecha de la remisión:</strong> ' . $this->pedid_fecha_propuesta . '</p>
                        <p class="mb-0" style="line-height:3px;"><strong>Nit/C.C.:</strong> ' . $this->cli_usuario . '</p>
                        <p class="mb-0" style="line-height:3px;"><strong>Cliente:</strong> ' . $this->cli_nombre . '</p>
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
                <td width="15%" align="left">
                </td>
                <td width="70%" align="center">
                   <p>Documento no válido como factura de venta. Para cualquier consulta, contáctenos</p>
                    <p>¡Gracias por su confianza!</p>
                </td>
                <td width="15%" align="right">
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
$pdf->pedid_numero = $datosPedido[Remision::$primaryKey];
$pdf->pedid_fecha_propuesta = $datosPedido['remi_fecha_propuesta'];
$pdf->cli_nombre = $datosCliente['cli_nombre'];
$pdf->cli_usuario = $datosCliente['cli_usuario'];
$pdf->cli_direccion = $datosCliente['cli_direccion'];
$pdf->conf_web = $configuracion['conf_web'];
$pdf->ruta_imagen = RUTA_PROYECTO.'/usuarios/files/'.$configuracion['conf_logo'];

$pdf->SetCreator('Mi Aplicación');
$pdf->SetTitle('Impresión de Remision - #'.$datosPedido[Remision::$primaryKey]);

$pdf->SetMargins(15, 50, 15); // Espacio para encabezado
$pdf->SetAutoPageBreak(TRUE, 30);
$pdf->AddPage();

// Estilos y encabezado de la tabla
$html = '
<h3 >Detalles de la remisión:</h3>
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
$listadoProductos = $itemAsociado->listadoAsociadoProductos($datosPedido[Remision::$primaryKey], ItemAsociado::PROCESO_REMSION);

$todosLosItemsParaTabla = [];


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
                    <p><strong>Observaciones:</strong></p>
                    <p style="line-height:8px;">Esta remisión sirve como constancia de la entrega de los productos descritos. Los productos han sido revisados y recibidos a satisfacción.</p>
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



$pdf->SetFont('helvetica', '', 8);
$pdf->writeHTML($html, true, false, true, false, '');


$pdf->Ln(20);

$htmlFirma = '
<table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
        <td width="50%" align="center">
            _________________________________________________<br>
            Firma y Sello: Recibido por
        </td>
        <td width="50%" align="center">
            _________________________________________________<br>
            Firma: Entregado por
        </td>
    </tr>
</table>';

$pdf->writeHTML($htmlFirma, true, false, true, false, '');


// Salida del PDF
$pdf->Output('remision_'.$_GET["id"].'.pdf', 'I');