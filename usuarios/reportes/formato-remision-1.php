<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impresión de Pedido - #12345</title>
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
            /* Forzar el salto de página después del encabezado principal si es muy largo,
               o después de un bloque específico que quieras que comience en una nueva página.
               NO USAR ESTO PARA EL ENCABEZADO PRINCIPAL QUE QUIERES REPETIR.
               Esto es solo un ejemplo si tuvieras una sección grande entre el encabezado
               y la tabla que no quieres que se rompa. */
            /* .header-section { page-break-after: always; } */

            /* Para el encabezado y pie de página de toda la "página de impresión"
               (no de la tabla) que se repita en cada hoja.
               Requiere un posicionamiento más avanzado y puede ser un poco más complicado
               de alinear perfectamente con el contenido si el contenido varía mucho en altura.
               Generalmente, es mejor dejar que el navegador repita los thead/tfoot de la tabla.
               Si necesitas un encabezado/pie de página GLOBAL que se repita en CADA hoja,
               tendrías que usar position: fixed y jugar con los márgenes del @page
               para dejar espacio. Aquí un ejemplo si lo necesitaras para elementos fuera de la tabla:
            */
            /*
            .global-print-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 100px; /* Ajusta esto a la altura de tu encabezado *
                background-color: white;
                padding: 10px;
                border-bottom: 1px solid #ccc;
            }
            .global-print-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                height: 50px; /* Ajusta esto a la altura de tu pie de página *
                background-color: white;
                padding: 10px;
                border-top: 1px solid #ccc;
            }
            body {
                padding-top: 100px; /* Deja espacio para el encabezado fijo *
                padding-bottom: 50px; /* Deja espacio para el pie de página fijo *
            }
            */
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
                <h4 class="mb-1">Nombre de Tu Empresa S.A.S.</h4>
                <p class="mb-0">Dirección: Calle 123 #45-67, Tu Ciudad</p>
                <p class="mb-0">Teléfono: +57 300 123 4567</p>
                <p class="mb-0">Email: info@tuempresa.com</p>
            </div>
            <div class="col-md-6 text-md-end">
                <h2 class="mb-1">REMISIÓN #12345</h2>
                <p class="mb-0"><strong>Fecha del Pedido:</strong> 27 de Junio de 2025</p>
                <p class="mb-0"><strong>Fecha de Entrega:</strong> 30 de Junio de 2025</p>
                <p class="mb-0"><strong>Cliente:</strong> Juan Pérez S.A.</p>
                <p class="mb-0"><strong>NIT/C.C.:</strong> 900.123.456-7</p>
                <p class="mb-0"><strong>Dirección Cliente:</strong> Av. Principal #89-01, Otra Ciudad</p>
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
                            <?php for ($i = 1; $i <= 3; $i++): ?>
                            <tr>
                                <th scope="row"><?php echo $i; ?></th>
                                <td>Producto <?php echo chr(64 + $i); ?> - Descripción detallada del item <?php echo $i; ?></td>
                                <td class="text-end"><?php echo rand(1, 5); ?></td>
                                <td class="text-end">$<?php echo number_format(rand(10000, 100000), 0, ',', '.'); ?></td>
                                <td class="text-end">$<?php echo number_format(rand(10000, 500000), 0, ',', '.'); ?></td>
                            </tr>
                            <?php endfor; ?>
                            </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Subtotal:</th>
                                <td class="text-end">$200.000</td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">Descuento (5%):</th>
                                <td class="text-end">-$10.000</td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">IVA (19%):</th>
                                <td class="text-end">$36.100</td>
                            </tr>
                            <tr class="fw-bold">
                                <th colspan="4" class="text-end">Total a Pagar:</th>
                                <td class="text-end">$226.100</td>
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