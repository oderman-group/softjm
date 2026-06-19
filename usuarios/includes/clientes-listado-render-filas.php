<?php

/**
 * Renderiza filas del listado de clientes.
 * Requiere: $filasClientes, $etiquetasPorCliente, $contadoresPorCliente,
 *           $permisosVisibilidad, $filtrarPorPermisos, $tipoDocumento, $configuracion...
 */

$no = 1;

foreach ($filasClientes as $res) {
    if (isset($_GET['pap']) && $res['cli_papelera'] == 1 && $_GET['pap'] != 1) {
        continue;
    }

    if ($filtrarPorPermisos && !Cliente::usuarioPuedeVerClienteEnListado(
        $permisosVisibilidad,
        intval($res['cli_zona']),
        intval($res['cli_id'])
    )) {
        continue;
    }

    $estadoSesion = ($res['cli_sesion'] == 1) ? 'verde.jpg' : 'gris.jpg';

    $fondoPapelera = 'none';
    $titleEstado   = '';
    if ($res['cli_estado_mercadeo'] == 6 || $res['cli_papelera'] == 1) {
        $fondoPapelera = 'tomato';
        $titleEstado   = 'Papelera - ' . $res['cli_estado_mercadeo_fecha'];
    } elseif ($res['cli_estado_mercadeo'] == 2) {
        $fondoPapelera = 'goldenrod';
        $titleEstado   = 'Número equivocado - ' . $res['cli_estado_mercadeo_fecha'];
    } elseif ($res['cli_estado_mercadeo'] == 5) {
        $fondoPapelera = 'aqua';
        $titleEstado   = 'Actualizado - ' . $res['cli_estado_mercadeo_fecha'];
    }

    switch ($res['cli_categoria']) {
        case 1:
            $categ = 'Prospecto';
            $etiquetaC = 'warning';
            $fondoColorCat = '';
            break;
        case 2:
            $categ = 'Cliente';
            $etiquetaC = 'info';
            $fondoColorCat = '';
            break;
        case 3:
            $categ = 'Dealer';
            $etiquetaC = 'info';
            $fondoColorCat = 'aquamarine';
            break;
        default:
            $categ = '';
            $etiquetaC = 'info';
            $fondoColorCat = '';
    }

    $clienteId = intval($res['cli_id']);
    $numeros   = $contadoresPorCliente[$clienteId] ?? [0, 0, 0, 0, 0, 0];

    $colores = ['#FFF', '#FFF', '#FFF', '#FFF', '#FFF', '#FFF'];
    for ($i = 0; $i < 6; $i++) {
        if ($numeros[$i] == 0) {
            $colores[$i] = '#FFF090';
        }
    }
    ?>
    <tr title="<?= htmlspecialchars($titleEstado) ?>">
        <td style="background-color: <?= $fondoPapelera ?>;">
            <?php if ($res['cli_retirado'] == 1) { ?>
                <span style="color:red;"><strike>(R) <?= $no ?></strike></span>
            <?php } else { echo $no; } ?>
        </td>
        <td style="background-color: <?= $fondoPapelera ?>;">
            <?= htmlspecialchars($res['ciu_nombre'] . ', ' . $res['dep_nombre'] . ' (03' . $res['dep_indicativo'] . ')') ?>
        </td>
        <td style="background-color: <?= $fondoColorCat ?>;">
            <?php echo '<b>Creado:</b> ' . htmlspecialchars($res['cli_fecha_registro']); ?><br>
            <?php echo '<b>Tipo documento</b>:' . ($tipoDocumento[$res['cli_tipo_documento']] ?? '') . ' | '; ?>
            <?php echo '<b>Nro. Documento</b>:' . htmlspecialchars($res['cli_usuario']); ?> | <?php echo '<b>Categoría:</b> ' . $categ; ?><br>
            <?php echo '<span style="font-size:16px;">' . htmlspecialchars($res['cli_nombre']); ?></span>
            <?php
            $etiquetasClienteListado = $etiquetasPorCliente[$clienteId] ?? [];
            if (!empty($etiquetasClienteListado)) {
                echo Etiqueta::renderBadges($etiquetasClienteListado, 'crm-etiquetas--compact cliente-listado-etiquetas');
            }
            ?>
            <?php if ($res['cli_telefono'] != '') echo '<br><b>Tel:</b> ' . htmlspecialchars($res['cli_telefono']); ?>
            <?php if ($res['cli_celular'] != '') echo '<br><b>Cel:</b> ' . htmlspecialchars($res['cli_celular']); ?>
            <?php if ($res['cli_email'] != '') echo ' | <b>Email:</b> ' . htmlspecialchars($res['cli_email']); ?>

            <h4 style="margin-top:5px;">
                <?php if (Modulos::validarRol([11], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
                    <a href="clientes-editar.php?id=<?= $clienteId ?>" data-toggle="tooltip" title="Editar" target="_blank"><i class="icon-edit"></i></a>&nbsp;
                <?php } ?>
                <?php if (Modulos::validarRol([83], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
                    <a href="clientes-sucursales.php?cte=<?= $clienteId ?>&emg=1" data-toggle="tooltip" title="Sucursales" target="new"><i class="icon-home"></i></a>&nbsp;
                <?php } ?>
                <?php if (Modulos::validarRol([44], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
                    <a href="clientes-contactos.php?cte=<?= $clienteId ?>&emg=1" data-toggle="tooltip" title="Contactos" target="new"><i class="icon-group"></i></a>&nbsp;
                <?php } ?>
                <?php if (Modulos::validarRol([88], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
                    <a href="clientes-tikets.php?cte=<?= $clienteId ?>&emg=1" data-toggle="tooltip" title="Tikets de seguimiento" target="new"><i class="icon-list-ol"></i></a>&nbsp;
                <?php } ?>
                <?php if (Modulos::validarRol([12], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
                    <a href="clientes-seguimiento.php?cte=<?= $clienteId ?>&emg=1" data-toggle="tooltip" title="Seguimiento de clientes" target="new"><i class="icon-list-alt"></i></a>&nbsp;
                <?php } ?>
                <?php if (Modulos::validarRol([259], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
                    <a href="facturas.php?cte=<?= $clienteId ?>&emg=1" data-toggle="tooltip" title="Facturación" target="new"><i class="icon-money"></i></a>&nbsp;
                <?php } ?>
                <?php if (Modulos::validarRol([110], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
                    <a href="enviar-portafolios.php?cte=<?= $clienteId ?>" data-toggle="tooltip" title="Enviar portafolios" target="_blank"><i class="icon-list-ul"></i></a>&nbsp;
                <?php } ?>
                <a href="#"
                   class="js-notas-internas-cliente"
                   data-cliente-id="<?= $clienteId ?>"
                   data-cliente-nombre="<?= htmlspecialchars($res['cli_nombre'], ENT_QUOTES, 'UTF-8') ?>"
                   data-toggle="tooltip"
                   title="Notas internas"><i class="icon-comment"></i></a>&nbsp;
            </h4>
        </td>
        <?php
        $valoresClientes = [
            ['url' => 'clientes-tikets.php?cte=' . $clienteId, 'id' => 88, 'color' => $colores[0], 'numero' => $numeros[0]],
            ['url' => 'clientes-seguimiento.php?cte=' . $clienteId, 'id' => 88, 'color' => $colores[1], 'numero' => $numeros[1]],
            ['url' => 'clientes-sucursales.php?cte=' . $clienteId, 'id' => 88, 'color' => $colores[2], 'numero' => $numeros[2]],
            ['url' => 'clientes-contactos.php?cte=' . $clienteId, 'id' => 88, 'color' => $colores[3], 'numero' => $numeros[3]],
            ['url' => 'facturacion.php?cte=' . $clienteId, 'id' => 88, 'color' => $colores[4], 'numero' => $numeros[4]],
            ['url' => '../v2.0/usuarios/empresa/lab-remisiones.php?cte=' . $clienteId, 'id' => 88, 'color' => $colores[5], 'numero' => $numeros[5]],
        ];
        foreach ($valoresClientes as $pagina) {
            if (Modulos::validarRol([$pagina['id']], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
                <td align="center" style="background:<?= $pagina['color'] ?>;"><a href="<?= $pagina['url'] ?>" target="_blank"><?= $pagina['numero'] ?></a></td>
            <?php }
        } ?>
    </tr>
    <?php
    $no++;
}
