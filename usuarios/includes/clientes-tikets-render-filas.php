<?php

/**
 * Renderiza filas del listado de tickets.
 * Requiere: $filasTickets, $ticketsPermisos, $opcionesEtapa, $clienteIdPagina
 */

$no = 1;
$ocultarColumnaCliente = !empty($clienteIdPagina);

if (empty($filasTickets)) {
    $colspan = $ocultarColumnaCliente ? 12 : 13;
    echo '<tr><td colspan="' . $colspan . '" class="tickets-empty">No hay tickets que coincidan con los filtros aplicados.</td></tr>';
    return;
}

foreach ($filasTickets as $res) {
    switch (intval($res['tik_tipo_tiket'])) {
        case 1: $tipoS = 'Comercial'; $pillTipo = 'is-success'; break;
        case 3: $tipoS = 'Soporte'; $pillTipo = 'is-info'; break;
        default: $tipoS = '—'; $pillTipo = 'is-neutral';
    }

    switch (intval($res['tik_estado'])) {
        case 1: $estado = 'Abierto'; $pillEstado = 'is-warning'; break;
        case 2: $estado = 'Cerrado'; $pillEstado = 'is-neutral'; break;
        default: $estado = '—'; $pillEstado = 'is-neutral';
    }

    switch (intval($res['tik_prioridad'])) {
        case 1: $prioridad = 'Normal'; $pillPrioridad = 'is-success'; break;
        case 2: $prioridad = 'Urgente'; $pillPrioridad = 'is-warning'; break;
        case 3: $prioridad = 'Muy urgente'; $pillPrioridad = 'is-danger'; break;
        default: $prioridad = '—'; $pillPrioridad = 'is-neutral';
    }

    $clienteIdTicket = intval($res['tik_cliente']);
    $ticketIdRow = intval($res['tik_id']);
    $numSeg = intval($res['num_seguimientos'] ?? 0);
    $cotizId = !empty($res['tik_id_cotizacion']) ? intval($res['tik_id_cotizacion']) : 0;
    $etapaLabel = $opcionesEtapa[intval($res['tik_etapa'])] ?? '—';
    ?>
    <tr>
        <td><?= $no; ?></td>
        <td><strong>#<?= $ticketIdRow; ?></strong></td>
        <td><span class="tickets-pill <?= $pillTipo; ?>"><?= $tipoS; ?></span></td>
        <td><?= htmlspecialchars(substr($res['tik_fecha_creacion'] ?? '', 0, 16)); ?></td>
        <?php if (!$ocultarColumnaCliente) { ?>
        <td class="tickets-cliente-cell">
            <strong><?= htmlspecialchars($res['cli_nombre'] ?? ''); ?></strong>
            <?php if (!empty($res['cli_telefono']) || !empty($res['cli_email'])) { ?>
            <div class="tickets-cliente-meta">
                <?php if (!empty($res['cli_telefono'])) echo htmlspecialchars($res['cli_telefono']); ?>
                <?php if (!empty($res['cli_telefono']) && !empty($res['cli_email'])) echo ' · '; ?>
                <?php if (!empty($res['cli_email'])) echo htmlspecialchars($res['cli_email']); ?>
            </div>
            <?php } ?>
        </td>
        <?php } ?>
        <td><?= htmlspecialchars($res['sucu_nombre'] ?? '—'); ?></td>
        <td><?= htmlspecialchars($res['tik_asunto_principal'] ?? '—'); ?></td>
        <td><?= htmlspecialchars($res['usr_nombre'] ?? '—'); ?></td>
        <td>
            <?php if ($cotizId > 0 && $ticketsPermisos['verCotizacion']) { ?>
                <a href="#" class="tickets-drawer-link js-abrir-cotizacion-drawer" data-cotizacion-id="<?= $cotizId; ?>" data-cliente-id="<?= $clienteIdTicket; ?>" data-toggle="tooltip" title="Ver cotización">#<?= $cotizId; ?></a>
            <?php } elseif ($cotizId > 0) { echo '#' . $cotizId; } else { echo '—'; } ?>
        </td>
        <td><span class="tickets-pill <?= $pillEstado; ?>"><?= $estado; ?></span></td>
        <td class="col-etapa"><span class="tickets-pill is-info"><?= htmlspecialchars($etapaLabel); ?></span></td>
        <td><span class="tickets-pill <?= $pillPrioridad; ?>"><?= $prioridad; ?></span></td>
        <td align="center">
            <?php if ($numSeg > 0 && $ticketsPermisos['verSeguimientos']) { ?>
                <a href="#" class="tickets-drawer-link is-count js-abrir-ticket-seguimientos-drawer" data-ticket-id="<?= $ticketIdRow; ?>" data-cliente-id="<?= $clienteIdTicket; ?>" data-toggle="tooltip" title="Ver seguimientos"><?= $numSeg; ?></a>
            <?php } else { echo $numSeg; } ?>
        </td>
        <td class="tickets-actions-cell">
            <?php if ($ticketsPermisos['verTicketDrawer']) { ?>
                <a href="#" class="js-abrir-ticket-drawer" data-ticket-id="<?= $ticketIdRow; ?>" data-cliente-id="<?= $clienteIdTicket; ?>" data-toggle="tooltip" title="Ver ticket"><i class="icon-edit"></i></a>
            <?php } ?>
        </td>
    </tr>
    <?php
    $no++;
}
