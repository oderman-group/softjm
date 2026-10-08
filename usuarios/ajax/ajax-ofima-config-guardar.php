<?php
require_once("../sesion.php");
require_once(RUTA_PROYECTO . "/usuarios/includes/api-ofima-conexion.php");

header('Content-Type: application/json; charset=utf-8');

try {
    $idPagina = 265;
    include(RUTA_PROYECTO . "/usuarios/includes/verificar-paginas.php");

    $idEmpresa = (int) $_SESSION["dataAdicional"]["id_empresa"];
    asegurarTablaApiOfimaConexion($conexionBdPrincipal);

    $ambiente = ($_POST['ambiente'] ?? 'pruebas') === 'produccion' ? 'produccion' : 'pruebas';
    $urlPruebas = trim((string) ($_POST['url_pruebas'] ?? ''));
    $urlProduccion = trim((string) ($_POST['url_produccion'] ?? ''));
    $usuarioPruebas = trim((string) ($_POST['usuario_pruebas'] ?? ''));
    $usuarioProduccion = trim((string) ($_POST['usuario_produccion'] ?? ''));
    $clavePruebasNueva = (string) ($_POST['clave_pruebas'] ?? '');
    $claveProduccionNueva = (string) ($_POST['clave_produccion'] ?? '');
    $activo = isset($_POST['activo']) && (string) $_POST['activo'] === '1' ? 1 : 0;

    if ($urlPruebas === '') {
        echo json_encode(['success' => false, 'error' => 'La URL de pruebas es obligatoria']);
        exit;
    }

    if ($ambiente === 'produccion' && $urlProduccion === '') {
        echo json_encode(['success' => false, 'error' => 'La URL de producción es obligatoria cuando el ambiente activo es producción']);
        exit;
    }

    $actual = obtenerApiOfimaConexion($conexionBdPrincipal, $idEmpresa);
    $clavePruebas = $clavePruebasNueva !== ''
        ? base64_encode($clavePruebasNueva)
        : (string) ($actual['aoc_clave_pruebas'] ?? '');
    $claveProduccion = $claveProduccionNueva !== ''
        ? base64_encode($claveProduccionNueva)
        : (string) ($actual['aoc_clave_produccion'] ?? '');

    if ($actual) {
        $stmt = $conexionBdPrincipal->prepare(
            "UPDATE api_ofima_conexion SET
                aoc_ambiente = ?,
                aoc_url_pruebas = ?,
                aoc_url_produccion = ?,
                aoc_usuario_pruebas = ?,
                aoc_clave_pruebas = ?,
                aoc_usuario_produccion = ?,
                aoc_clave_produccion = ?,
                aoc_activo = ?
             WHERE aoc_id_empresa = ?"
        );
        $stmt->bind_param(
            'sssssssii',
            $ambiente,
            $urlPruebas,
            $urlProduccion,
            $usuarioPruebas,
            $clavePruebas,
            $usuarioProduccion,
            $claveProduccion,
            $activo,
            $idEmpresa
        );
        $stmt->execute();
    } else {
        $stmt = $conexionBdPrincipal->prepare(
            "INSERT INTO api_ofima_conexion (
                aoc_id_empresa, aoc_ambiente, aoc_url_pruebas, aoc_url_produccion,
                aoc_usuario_pruebas, aoc_clave_pruebas, aoc_usuario_produccion, aoc_clave_produccion, aoc_activo
             ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            'isssssssi',
            $idEmpresa,
            $ambiente,
            $urlPruebas,
            $urlProduccion,
            $usuarioPruebas,
            $clavePruebas,
            $usuarioProduccion,
            $claveProduccion,
            $activo
        );
        $stmt->execute();
    }

    $config = obtenerApiOfimaConexion($conexionBdPrincipal, $idEmpresa);
    $credenciales = resolverCredencialesOfima($config);

    if ($credenciales['url_base'] !== '' && $credenciales['usuario'] !== '' && $credenciales['clave'] !== '') {
        sincronizarEndpointsApiConfiguracion(
            $conexionBdPrincipal,
            $idEmpresa,
            $credenciales['url_base'],
            $credenciales['usuario'],
            $credenciales['clave'],
            $activo
        );
    }

    echo json_encode([
        'success' => true,
        'message' => 'Configuración Ofima guardada correctamente',
        'ambiente' => $ambiente,
        'url_activa' => $credenciales['url_base'],
    ]);
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Error al guardar: ' . $e->getMessage(),
    ]);
}
