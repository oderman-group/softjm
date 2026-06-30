<?php

/**
 * Renderiza filas del listado de clientes.
 * Requiere: $filasClientes, $etiquetasPorCliente, $contadoresPorCliente,
 *           $permisosVisibilidad, $filtrarPorPermisos, $tipoDocumento, $configuracion...
 */

$no = 1;
$filasVisibles = 0;

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

    $filasVisibles++;

    $rowClass = '';
    $titleEstado = '';
    if ($res['cli_estado_mercadeo'] == 6 || $res['cli_papelera'] == 1) {
        $rowClass = 'is-papelera';
        $titleEstado = 'Papelera - ' . ($res['cli_estado_mercadeo_fecha'] ?? '');
    } elseif ($res['cli_estado_mercadeo'] == 2) {
        $rowClass = 'is-equivocado';
        $titleEstado = 'Número equivocado - ' . ($res['cli_estado_mercadeo_fecha'] ?? '');
    } elseif ($res['cli_estado_mercadeo'] == 5) {
        $rowClass = 'is-actualizado';
        $titleEstado = 'Actualizado - ' . ($res['cli_estado_mercadeo_fecha'] ?? '');
    }

    switch (intval($res['cli_categoria'])) {
        case 1:
            $categ = 'Prospecto';
            $pillCategoria = 'is-warning';
            break;
        case 2:
            $categ = 'Cliente';
            $pillCategoria = 'is-info';
            break;
        case 3:
            $categ = 'Dealer';
            $pillCategoria = 'is-success';
            break;
        default:
            $categ = '—';
            $pillCategoria = 'is-neutral';
    }

    $clienteId = intval($res['cli_id']);
    $numeros   = $contadoresPorCliente[$clienteId] ?? [0, 0, 0, 0, 0, 0];
    $infoClass = intval($res['cli_categoria']) === 3 ? ' col-info' : '';
    ?>
    <tr class="<?= htmlspecialchars($rowClass) ?>" title="<?= htmlspecialchars($titleEstado) ?>">
        <td>
            <?php if ($res['cli_retirado'] == 1) { ?>
                <span style="color:#dc2626;"><strike>(R) <?= $no ?></strike></span>
            <?php } else { echo $no; } ?>
        </td>
        <td>
            <?= htmlspecialchars(trim(($res['ciu_nombre'] ?? '') . ', ' . ($res['dep_nombre'] ?? '') . ' (03' . ($res['dep_indicativo'] ?? '') . ')'), ENT_QUOTES, 'UTF-8') ?>
        </td>
        <td class="clientes-cliente-cell<?= $infoClass ?>">
            <strong><?= htmlspecialchars($res['cli_nombre'] ?? ''); ?></strong>
            <div class="clientes-cliente-meta">
                <span class="clientes-pill <?= $pillCategoria; ?>"><?= $categ; ?></span>
                &nbsp;·&nbsp;
                <b>Creado:</b> <?= htmlspecialchars(substr($res['cli_fecha_registro'] ?? '', 0, 16)); ?>
                <br>
                <b><?= htmlspecialchars($tipoDocumento[$res['cli_tipo_documento']] ?? 'Doc.'); ?>:</b>
                <?= htmlspecialchars($res['cli_usuario'] ?? ''); ?>
                <?php if (!empty($res['cli_telefono'])) { ?>
                    <br><b>Tel:</b> <?= htmlspecialchars($res['cli_telefono']); ?>
                <?php } ?>
                <?php if (!empty($res['cli_celular'])) { ?>
                    <?php if (empty($res['cli_telefono'])) { ?><br><?php } else { ?> · <?php } ?>
                    <b>Cel:</b> <?= htmlspecialchars($res['cli_celular']); ?>
                <?php } ?>
                <?php if (!empty($res['cli_email'])) { ?>
                    <br><b>Email:</b> <?= htmlspecialchars($res['cli_email']); ?>
                <?php } ?>
            </div>
            <?php
            $etiquetasClienteListado = $etiquetasPorCliente[$clienteId] ?? [];
            if (!empty($etiquetasClienteListado)) {
                echo Etiqueta::renderBadges($etiquetasClienteListado, 'crm-etiquetas--compact cliente-listado-etiquetas');
            }
            ?>
            <div class="clientes-actions-cell" style="margin-top:0.5rem;">
                <?php if (Modulos::validarRol([11], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
                    <a href="clientes-editar.php?id=<?= $clienteId ?>" data-toggle="tooltip" title="Editar" target="_blank"><i class="icon-edit"></i></a>
                <?php } ?>
                <?php if (Modulos::validarRol([83], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
                    <a href="clientes-sucursales.php?cte=<?= $clienteId ?>&emg=1" data-toggle="tooltip" title="Sucursales" target="new"><i class="icon-home"></i></a>
                <?php } ?>
                <?php if (Modulos::validarRol([44], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
                    <a href="clientes-contactos.php?cte=<?= $clienteId ?>&emg=1" data-toggle="tooltip" title="Contactos" target="new"><i class="icon-group"></i></a>
                <?php } ?>
                <?php if (Modulos::validarRol([88], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
                    <a href="clientes-tikets.php?cte=<?= $clienteId ?>&emg=1" data-toggle="tooltip" title="Tickets de seguimiento" target="new"><i class="icon-list-ol"></i></a>
                <?php } ?>
                <?php if (Modulos::validarRol([12], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
                    <a href="clientes-seguimiento.php?cte=<?= $clienteId ?>&emg=1" data-toggle="tooltip" title="Seguimiento de clientes" target="new"><i class="icon-list-alt"></i></a>
                <?php } ?>
                <?php if (Modulos::validarRol([259], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
                    <a href="facturas.php?cte=<?= $clienteId ?>&emg=1" data-toggle="tooltip" title="Facturación" target="new"><i class="icon-money"></i></a>
                <?php } ?>
                <?php if (Modulos::validarRol([110], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
                    <a href="enviar-portafolios.php?cte=<?= $clienteId ?>" data-toggle="tooltip" title="Enviar portafolios" target="_blank"><i class="icon-list-ul"></i></a>
                <?php } ?>
                <a href="#"
                   class="js-notas-internas-cliente"
                   data-cliente-id="<?= $clienteId ?>"
                   data-cliente-nombre="<?= htmlspecialchars($res['cli_nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   data-toggle="tooltip"
                   title="Notas internas"><i class="icon-comment"></i></a>
            </div>
        </td>
        <?php
        $valoresClientes = [
            ['url' => 'clientes-tikets.php?cte=' . $clienteId, 'id' => 88, 'numero' => $numeros[0]],
            ['url' => 'clientes-seguimiento.php?cte=' . $clienteId, 'id' => 88, 'numero' => $numeros[1]],
            ['url' => 'clientes-sucursales.php?cte=' . $clienteId, 'id' => 88, 'numero' => $numeros[2]],
            ['url' => 'clientes-contactos.php?cte=' . $clienteId, 'id' => 88, 'numero' => $numeros[3]],
            ['url' => 'facturacion.php?cte=' . $clienteId, 'id' => 88, 'numero' => $numeros[4]],
            ['url' => '../v2.0/usuarios/empresa/lab-remisiones.php?cte=' . $clienteId, 'id' => 88, 'numero' => $numeros[5]],
        ];
        foreach ($valoresClientes as $pagina) {
            if (Modulos::validarRol([$pagina['id']], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
                $countClass = $pagina['numero'] == 0 ? ' is-empty' : '';
                ?>
                <td class="col-count<?= $countClass ?>">
                    <a href="<?= $pagina['url'] ?>" target="_blank" class="clientes-count-link<?= $countClass ?>"><?= intval($pagina['numero']) ?></a>
                </td>
            <?php }
        } ?>
    </tr>
    <?php
    $no++;
}

if ($filasVisibles === 0) {
    echo '<tr><td colspan="20" class="clientes-empty">No hay clientes que coincidan con los filtros aplicados.</td></tr>';
}
