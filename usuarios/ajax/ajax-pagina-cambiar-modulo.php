<?php
/**
 * Cambia el módulo asociado a una página (tabla paginas en BDADMIN).
 * Guardado automático; requiere permiso de edición de páginas (página 76).
 */
include("../sesion.php");

header('Content-Type: application/json; charset=utf-8');

if (!Modulos::validarRol([76], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    echo json_encode(['success' => false, 'error' => 'No tiene permiso para cambiar el módulo de una página.'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $paginaId = isset($_POST['pagina_id']) ? intval($_POST['pagina_id']) : 0;
    $moduloId = isset($_POST['modulo_id']) ? intval($_POST['modulo_id']) : 0;

    if ($paginaId <= 0 || $moduloId <= 0) {
        throw new Exception("Datos inválidos: página y módulo son requeridos.");
    }

    $idEmpresa = $_SESSION["dataAdicional"]["id_empresa"];

    // Verificar que el módulo pertenece a la empresa
    $consultaMod = $conexionBdAdmin->query(
        "SELECT mod_id FROM modulos m 
         INNER JOIN modulos_empresa me ON me.mxe_id_modulo = m.mod_id AND me.mxe_id_empresa = '" . $conexionBdAdmin->real_escape_string($idEmpresa) . "' 
         WHERE m.mod_id = " . $moduloId
    );
    if (!$consultaMod || $consultaMod->num_rows === 0) {
        throw new Exception("El módulo no existe o no está disponible para esta empresa.");
    }

    $stmt = $conexionBdAdmin->prepare("UPDATE paginas SET pag_id_modulo = ? WHERE pag_id = ?");
    $stmt->bind_param("ii", $moduloId, $paginaId);

    if (!$stmt->execute()) {
        throw new Exception("Error al actualizar la asociación: " . $conexionBdAdmin->error);
    }

    if ($stmt->affected_rows === 0) {
        // Puede ser que ya estaba en ese módulo
        $check = $conexionBdAdmin->query("SELECT pag_id FROM paginas WHERE pag_id = " . $paginaId);
        if (!$check || $check->num_rows === 0) {
            throw new Exception("Página no encontrada.");
        }
    }

    $stmt->close();

    echo json_encode([
        'success' => true,
        'message' => 'Módulo actualizado correctamente.',
        'pagina_id' => $paginaId,
        'modulo_id' => $moduloId
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
