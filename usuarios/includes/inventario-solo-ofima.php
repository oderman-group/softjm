<?php
/**
 * Helper: comprueba si la política de inventario es "solo Ofima puede dar entrada".
 * Cuando está activa, el CRM no debe permitir crear/editar existencias por bodega
 * (bodegas-productos, importar Excel, drawer, etc.); solo la API inventario-recibir de Ofima.
 *
 * Se considera activa si:
 * - la integración Ofima está activa para la empresa (api_ofima_conexion.aoc_activo), o
 * - el flag explícito apin_solo_ofima_entrada = 1 en api_inventario_config.
 *
 * @param mysqli $conexionBdPrincipal
 * @param int $idEmpresa
 * @return bool true si solo Ofima puede dar entrada de existencias
 */
function inventarioSoloOfimaEntrada($conexionBdPrincipal, $idEmpresa) {
    $idEmpresa = (int) $idEmpresa;
    if ($idEmpresa <= 0) {
        return false;
    }

    if (!function_exists('ofimaIntegracionActiva')) {
        require_once __DIR__ . '/api-ofima-conexion.php';
    }
    if (ofimaIntegracionActiva($conexionBdPrincipal, $idEmpresa)) {
        return true;
    }

    $stmt = $conexionBdPrincipal->prepare("SELECT apin_solo_ofima_entrada FROM api_inventario_config WHERE apin_id_empresa = ? LIMIT 1");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("i", $idEmpresa);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        return false;
    }
    $row = $result->fetch_assoc();
    return (int) $row['apin_solo_ofima_entrada'] === 1;
}
