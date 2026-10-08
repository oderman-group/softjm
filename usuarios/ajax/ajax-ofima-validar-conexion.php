<?php
require_once("../sesion.php");
require_once(RUTA_PROYECTO . "/usuarios/includes/api-ofima-conexion.php");

header('Content-Type: application/json; charset=utf-8');

try {
    $idPagina = 265;
    include(RUTA_PROYECTO . "/usuarios/includes/verificar-paginas.php");

    $idEmpresa = (int) $_SESSION["dataAdicional"]["id_empresa"];
    asegurarTablaApiOfimaConexion($conexionBdPrincipal);

    $ambiente = ($_POST['ambiente'] ?? '') === 'produccion' ? 'produccion' : 'pruebas';
    $urlBase = trim((string) ($_POST['url_base'] ?? ''));
    $usuario = trim((string) ($_POST['usuario'] ?? ''));
    $clave = (string) ($_POST['clave'] ?? '');

    // Si no envían clave en el request, usar la guardada
    $config = obtenerApiOfimaConexion($conexionBdPrincipal, $idEmpresa);
    if ($clave === '' && $config) {
        $credencialesGuardadas = resolverCredencialesOfima(array_merge($config, ['aoc_ambiente' => $ambiente]));
        if ($urlBase === '') {
            $urlBase = $credencialesGuardadas['url_base'];
        }
        if ($usuario === '') {
            $usuario = $credencialesGuardadas['usuario'];
        }
        $clave = $credencialesGuardadas['clave'];
    }

    $resultado = solicitarTokenOfima($urlBase, $usuario, $clave);

    if (!$resultado['success']) {
        echo json_encode([
            'success' => false,
            'error' => $resultado['error'] ?? 'Validación fallida',
            'codigo_http' => $resultado['codigo_http'] ?? 0,
            'url' => $resultado['url'] ?? null,
            'detalle' => $resultado['datos'] ?? $resultado['respuesta_raw'] ?? null,
        ]);
        exit;
    }

    $token = $resultado['token'];
    // Documentación Ofima: el token dura 1 hora
    $expira = date('Y-m-d H:i:s', time() + 3600);

    $tokenGuardado = false;
    if ($config) {
        $stmt = $conexionBdPrincipal->prepare(
            "UPDATE api_ofima_conexion
             SET aoc_token = ?, aoc_token_expira = ?, aoc_ambiente = ?
             WHERE aoc_id_empresa = ?"
        );
        $stmt->bind_param('sssi', $token, $expira, $ambiente, $idEmpresa);
        $stmt->execute();
        $tokenGuardado = true;
    }

    $tokenPreview = strlen($token) > 16
        ? substr($token, 0, 8) . '…' . substr($token, -6)
        : $token;

    echo json_encode([
        'success' => true,
        'message' => $tokenGuardado
            ? 'Conexión validada. Token generado y guardado.'
            : 'Conexión validada. Guarda la configuración Ofima para persistir el token.',
        'token_preview' => $tokenPreview,
        'token_expira' => $expira,
        'ambiente' => $ambiente,
        'url' => $resultado['url'],
        'codigo_http' => $resultado['codigo_http'],
        'token_guardado' => $tokenGuardado,
    ]);
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Error al validar: ' . $e->getMessage(),
    ]);
}
