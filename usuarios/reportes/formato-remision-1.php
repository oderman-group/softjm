<?php
require_once '../sesion.php';

$idPagina = 417;

require_once RUTA_PROYECTO.'/usuarios/class/Remision.php';
require_once RUTA_PROYECTO.'/usuarios/class/Cliente.php';
require_once RUTA_PROYECTO.'/usuarios/class/Contacto.php';
require_once RUTA_PROYECTO.'/usuarios/class/ItemAsociado.php';
require_once RUTA_PROYECTO.'/usuarios/class/Producto.php';

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impresión de Remisión - #<?=$datosPedido[Remision::$primaryKey];?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Estilos personalizados para impresión */
        @media print {
            body {
                font-family: Arial, sans-serif;
                font-size: 10pt;
            }
            .container-fluid {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .table th, .table td {
                padding: 0.5rem;
                font-size: 9pt;
            }
            .no-print {
                display: none !important;
            }
            /* Eliminar márgenes de página si es posible */
            @page {
                margin: 0.5cm;
            }
            /* Esto es CRUCIAL para que los encabezados y pies de tabla se repitan */
            .table thead { display: table-header-group; }
            .table tfoot { display: table-footer-group; }
            /* Evitar que las filas de la tabla se rompan entre páginas */
            .table tbody tr {
                page-break-inside: avoid;
            }

        }
        /* Estilos generales */
        body {
            background-color: #f8f9fa;
        }
        .invoice-box {
            background-color: #fff;
            padding: 30px;
            border: 1px solid #dee2e6;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .header-section, .footer-section {
            padding-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        .footer-section {
            border-bottom: none; /* Quitamos el borde inferior si solo es un bloque de notas */
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
            margin-top: 20px;
        }
        .text-end {
            text-align: right !important;
        }
        .table thead th {
            background-color: #e9ecef;
        }
        .logo {
            max-width: 150px;
            height: auto;
        }
    </style>
</head>
<body>

    <div class="container invoice-box">
        <div class="text-center mb-4 no-print">
            <button class="btn btn-primary" onclick="window.print()">Imprimir Remisión</button>
        </div>

        <div class="row header-section">
            <div class="col-md-6">
                <h4 class="mb-1"><?=$configuracion['conf_empresa'];?></h4>
                <p class="mb-0">Nit: <?=$configuracion['conf_nit'];?></p>
                <p class="mb-0">Teléfono: <?=$configuracion['conf_telefono'];?></p>
                <p class="mb-0">Email: <?=$configuracion['conf_email'];?></p>
            </div>
            <div class="col-md-6 text-md-end">
                <h2 class="mb-1">REMISIÓN #<?=$datosPedido[Remision::$primaryKey];?></h2>
                <p class="mb-0"><strong>Fecha de la remisión:</strong> <?=$datosPedido['remi_fecha_propuesta'];?></p>
                <p class="mb-0"><strong>Cliente:</strong> <?=$datosCliente['cli_nombre'];?></p>
                <p class="mb-0"><strong>NIT/C.C.:</strong> <?=$datosCliente['cli_usuario'];?></p>
                <p class="mb-0"><strong>Dirección Cliente:</strong> <?=$datosCliente['cli_direccion'];?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h5 class="mb-3">Detalles de la remisión:</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Descripción del Producto</th>
                                <th scope="col" class="text-end">Cantidad</th>
                                <th scope="col" class="text-end">Precio Unitario</th>
                                <th scope="col" class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $itemAsociado = new ItemAsociado($conexionBdPrincipal);
                            $listadoProductos = $itemAsociado->listadoAsociadoProductos($datosPedido[Remision::$primaryKey], ItemAsociado::PROCESO_REMSION);

                            $todosLosItemsParaTabla = [];
                            $combosAsociados = [];

                            while ($producto = mysqli_fetch_array($listadoProductos, MYSQLI_BOTH)) {

                                $nombreProducto = $producto['prod_nombre'];
                                $esValorOk = true;

                                Producto::procesarProductosEnCombos($producto, $nombreProducto, $esValorOk, $combosAsociados);

                                $itemActual  = [
                                    'nombre'      => $nombreProducto,
                                    'cantidad'    => $producto['czpp_cantidad'],
                                    'valor'       => $producto['czpp_valor'],
                                    'descuento'   => $producto['czpp_descuento'],
                                    'impuesto'    => $producto['czpp_impuesto'],
                                    'observacion' => $producto['czpp_observacion'],
                                    'es_valor_ok' => $esValorOk
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

                                $valorUnitarioMostrar = $item['es_valor_ok'] ? number_format($item['valor'], 0, ',', '.') : "<strike>".number_format($item['valor'], 0, ',', '.')."</strike>";

                                $totalPorItem = $item['es_valor_ok'] ? $item['valor'] * $item['cantidad'] : 0;
                                $totalPorItemParaMostrar = $item['valor'] * $item['cantidad'];
                                $subTotal += $totalPorItem;

                                $valorTotalPorItemMostrar = $item['es_valor_ok'] ? number_format($totalPorItemParaMostrar, 0, ',', '.') : "<strike>".number_format($totalPorItemParaMostrar, 0, ',', '.')."</strike>";

                                $descuento = !empty($item['descuento']) && $item['es_valor_ok'] ? $item['descuento'] : 0;

                                $descuentoPorItem = ($descuento / 100) * $totalPorItem;
                                $totalDescuento += $descuentoPorItem;
                                $totalDescuentoPorcentaje += $descuento;

                                $valorConDescuentoPorItem = $totalPorItem - $descuentoPorItem;


                                $ivaPorItem = ($item['impuesto'] / 100) * $valorConDescuentoPorItem;
                                $totalIva += $ivaPorItem;
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $i; ?></th>
                                    <td><?php echo $item['nombre']; ?></td>
                                    <td class="text-end"><?php echo $item['cantidad']; ?></td>
                                    <td class="text-end">$<?php echo $valorUnitarioMostrar; ?></td>
                                    <td class="text-end">$<?php echo $valorTotalPorItemMostrar; ?></td>
                                </tr>
                            <?php 
                                $i++; 
                            }
                            
                            $totalValorTodosLosCombos        = 0;
                            $totalValorFinalTodosLosCombos   = 0;
                            $totalDescuentosCombos           = 0;
                            $totalDescuentosCombosPorcentaje = 0;
                            $totalIvaCombos                  = 0;

                            if (!empty($combosAsociados)) {
                                foreach ($combosAsociados as $idCombo => $datosCombo) {
                                    $totalValorTodosLosCombos        += $datosCombo['valor_combo_total'];
                                    $totalValorFinalTodosLosCombos   += $datosCombo['valor_final_combo'];
                                    $totalDescuentosCombos           += $datosCombo['valor_descuento_combo'];
                                    $totalDescuentosCombosPorcentaje += $datosCombo['descuento_combo'];
                                    $totalIvaCombos                  += $datosCombo['valor_iva_combo'];
                                }
                            }

                            $subTotal                 += $totalValorTodosLosCombos;
                            $totalIva                 += $totalIvaCombos;
                            $totalDescuento           += $totalDescuentosCombos;
                            $totalDescuentoPorcentaje += $totalDescuentosCombosPorcentaje;

                            $totalPagar = $subTotal - $totalDescuento + $totalIva;
                            ?>
                            </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Subtotal:</th>
                                <td class="text-end">$<?php echo number_format($subTotal, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">Descuento:</th>
                                <td class="text-end">-$<?php echo number_format($totalDescuento, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">IVA:</th>
                                <td class="text-end">$<?php echo number_format($totalIva, 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="fw-bold">
                                <th colspan="4" class="text-end">Total a Pagar:</th>
                                <td class="text-end">$<?php echo number_format($totalPagar, 0, ',', '.'); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <p><strong>Observaciones:</strong></p>
                <p>Esta remisión sirve como constancia de la entrega de los productos descritos. Los productos han sido revisados y recibidos a satisfacción.</p>
            </div>
        </div>

		<div class="row mt-5">
            <div class="col-6 text-center">
                <hr style="width: 70%; margin: auto;">
                <p class="mt-2">Firma y Sello: Recibido por</p>
            </div>
            <div class="col-6 text-center">
                <hr style="width: 70%; margin: auto;">
                <p class="mt-2">Firma: Entregado por</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <p>Documento no válido como factura de venta. Para cualquier consulta, contáctenos</p>
                <p>¡Gracias por su confianza!</p>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9Gkcqila03B2R6Spm0n/o0" crossorigin="anonymous"></script>
</body>
</html>