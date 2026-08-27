<?php

/**
 * Paginación del listado de clientes preservando todos los filtros activos.
 * Requiere: $SQLCount, $conexionBdPrincipal, $configuracion
 */

$resultadoConteo = $conexionBdPrincipal->query($SQLCount);
$filaConteo      = mysqli_fetch_array($resultadoConteo, MYSQLI_NUM);
$numTotal        = intval($filaConteo[0] ?? 0);
$limitePag       = intval($configuracion['conf_paginacion'] ?? 50);
$paginaActual    = isset($_GET['inicio']) && is_numeric($_GET['inicio']) ? max(1, intval($_GET['inicio'])) : 1;
$paginas         = $limitePag > 0 ? max(1, (int) ceil($numTotal / $limitePag)) : 1;

$paramsPaginacion = $_GET;
unset($paramsPaginacion['inicio']);

$clientesPaginacionUrl = static function (int $pagina) use ($paramsPaginacion): string {
    $params = $paramsPaginacion;
    if ($pagina > 1) {
        $params['inicio'] = $pagina;
    } else {
        unset($params['inicio']);
    }

    $query = http_build_query($params);

    return 'clientes.php' . ($query !== '' ? '?' . $query : '');
};

?>
<div class="pagination clientes-pagination-block" data-total="<?= $numTotal; ?>">
    <div style="text-align:center">
        <ul>
            <?php if ($paginaActual > 1 && $paginas > 1) { ?>
                <li><a href="<?= htmlspecialchars($clientesPaginacionUrl($paginaActual - 1)); ?>" data-pagina="<?= $paginaActual - 1; ?>">Anterior</a></li>
            <?php } else { ?>
                <li class="disabled"><a href="#">Anterior</a></li>
            <?php } ?>

            <?php for ($i = 1; $i <= $paginas; $i++) {
                if ($i === 1 || $i === $paginas || ($i >= $paginaActual - 2 && $i <= $paginaActual + 2)) {
                    if ($i === $paginaActual) { ?>
                        <li class="active" style="padding-left: 5px!important;"><a><?= $i; ?></a></li>
                    <?php } else { ?>
                        <li style="padding-left: 5px!important;">
                            <a href="<?= htmlspecialchars($clientesPaginacionUrl($i)); ?>" data-pagina="<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php }
                } elseif (($i === 2 && $paginaActual > 3) || ($i === $paginas - 1 && $paginaActual < $paginas - 2)) { ?>
                    <li style="padding-left: 5px!important;"><span>...</span></li>
                <?php }
            } ?>

            <?php if ($paginaActual < $paginas) { ?>
                <li><a href="<?= htmlspecialchars($clientesPaginacionUrl($paginaActual + 1)); ?>" data-pagina="<?= $paginaActual + 1; ?>">Siguiente</a></li>
            <?php } else { ?>
                <li class="disabled"><a href="#">Siguiente</a></li>
            <?php } ?>
        </ul>
    </div>
</div>
