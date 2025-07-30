<?php
require_once '../sesion.php';

$idPagina = 373;

require_once RUTA_PROYECTO.'/usuarios/class/Pedido.php';
require_once RUTA_PROYECTO.'/usuarios/class/Cliente.php';
require_once RUTA_PROYECTO.'/usuarios/class/Contacto.php';
require_once RUTA_PROYECTO.'/usuarios/class/ItemAsociado.php';

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
require_once '../sesion.php';

$idPagina = 373;

require_once RUTA_PROYECTO.'/usuarios/class/Pedido.php';
require_once RUTA_PROYECTO.'/usuarios/class/Cliente.php';
require_once RUTA_PROYECTO.'/usuarios/class/Contacto.php';
require_once RUTA_PROYECTO.'/usuarios/class/ItemAsociado.php';

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
?>

<!DOCTYPE html>
<html lang="es">

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impresión de Pedido - #<?=$datosPedido[Pedido::$primaryKey];?></title>
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impresión de Pedido - #<?=$datosPedido[Pedido::$primaryKey];?></title>
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
            <button class="btn btn-primary" onclick="window.print()">Imprimir Pedido</button>
        </div>

        <div class="row header-section">
            <div class="col-md-6">
                <h4 class="mb-1"><?=$configuracion['conf_empresa'];?></h4>
                <p class="mb-0">Nit: <?=$configuracion['conf_nit'];?></p>
                <p class="mb-0">Teléfono: <?=$configuracion['conf_telefono'];?></p>
                <p class="mb-0">Email: <?=$configuracion['conf_email'];?></p>
            </div>
            <div class="col-md-6 text-md-end">
                <h2 class="mb-1">PEDIDO #<?=$datosPedido[Pedido::$primaryKey];?></h2>
                <p class="mb-0"><strong>Fecha del Pedido:</strong> <?=$datosPedido['pedid_fecha_propuesta'];?></p>
                <p class="mb-0"><strong>Cliente:</strong> <?=$datosCliente['cli_nombre'];?></p>
                <p class="mb-0"><strong>NIT/C.C.:</strong> <?=$datosCliente['cli_usuario'];?></p>
                <p class="mb-0"><strong>Dirección Cliente:</strong> <?=$datosCliente['cli_direccion'];?></p>
            </div>
        </div>
<body>

    <div class="container invoice-box">
        <div class="text-center mb-4 no-print">
            <button class="btn btn-primary" onclick="window.print()">Imprimir Pedido</button>
        </div>

        <div class="row header-section">
            <div class="col-md-6">
                <h4 class="mb-1"><?=$configuracion['conf_empresa'];?></h4>
                <p class="mb-0">Nit: <?=$configuracion['conf_nit'];?></p>
                <p class="mb-0">Teléfono: <?=$configuracion['conf_telefono'];?></p>
                <p class="mb-0">Email: <?=$configuracion['conf_email'];?></p>
            </div>
            <div class="col-md-6 text-md-end">
                <h2 class="mb-1">PEDIDO #<?=$datosPedido[Pedido::$primaryKey];?></h2>
                <p class="mb-0"><strong>Fecha del Pedido:</strong> <?=$datosPedido['pedid_fecha_propuesta'];?></p>
                <p class="mb-0"><strong>Cliente:</strong> <?=$datosCliente['cli_nombre'];?></p>
                <p class="mb-0"><strong>NIT/C.C.:</strong> <?=$datosCliente['cli_usuario'];?></p>
                <p class="mb-0"><strong>Dirección Cliente:</strong> <?=$datosCliente['cli_direccion'];?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h5 class="mb-3">Detalles del Pedido:</h5>
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

                            // while ($servicio = mysqli_fetch_array($listadoServicios, MYSQLI_BOTH)) {

                            //     $itemActual  = [
                            //         'nombre' => $servicio['serv_nombre'],
                            //         'cantidad' => $servicio['czpp_cantidad'],
                            //         'valor' => $servicio['czpp_valor'],
                            //         'descuento' => $servicio['czpp_descuento'],
                            //         'impuesto' => $servicio['czpp_impuesto'],
                            //         'observacion' => $servicio['czpp_observacion']
                            //     ];

                            //     $todosLosItemsParaTabla[] = $itemActual;
                            // }

                            // $listadoServicios->free();

                            $subTotal = 0;
                            $totalDescuento = 0;
                            $totalDescuentoPorcentaje = 0;
                            $totalIva = 0;
                            $i = 1;

                            foreach ($todosLosItemsParaTabla as $item) {

                                $totalPorItem = $item['valor'] * $item['cantidad'];
                                $subTotal += $totalPorItem;

                                $descuento = is_int($item['descuento']) ? $item['descuento'] : 0;

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
                                    <td class="text-end">$<?php echo number_format($item['valor'], 0, ',', '.'); ?></td>
                                    <td class="text-end">$<?php echo number_format($totalPorItem, 0, ',', '.'); ?></td>
                                </tr>
                            <?php 
                                $i++; 
                            } 
                            $totalPagar = $subTotal - $totalDescuento + $totalIva;
                            ?>
                            </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Subtotal:</th>
                                <td class="text-end">$<?php echo number_format($subTotal, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">Descuento (<?=$totalDescuentoPorcentaje;?>%):</th>
                                <td class="text-end">-$<?php echo number_format($totalDescuento, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">IVA (19%):</th>
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
                <p><strong>Notas del Pedido:</strong></p>
                <p>Favor revisar los productos al momento de la entrega. Cualquier reclamo, por favor, comunicarse dentro de las 24 horas siguientes.</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <p>¡Gracias por tu pedido!</p>
                <p>Visítanos en: <?=$configuracion['conf_web'];?></p>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 text-center">
                <p>¡Gracias por tu pedido!</p>
                <p>Visítanos en: <?=$configuracion['conf_web'];?></p>
            </div>
        </div>

    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9Gkcqila03B2R6Spm0n/o0" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9Gkcqila03B2R6Spm0n/o0" crossorigin="anonymous"></script>
</body>
</html>