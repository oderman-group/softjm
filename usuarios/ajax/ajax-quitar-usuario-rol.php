<?php
include("../sesion.php");

header('Content-Type: application/json; charset=utf-8');

try {
    $usuarioId = isset($_POST['usuarioId']) ? intval($_POST['usuarioId']) : 0;
    $idEmpresa = $_SESSION["dataAdicional"]["id_empresa"];
    
    if ($usuarioId <= 0) {
        throw new Exception("Usuario no válido");
    }
    
    // Obtener el ID del rol "Sin Rol" o crear uno temporal
    // Por seguridad, no dejamos usuarios sin rol, los movemos a un rol predeterminado
    $queryrRolDefault = "SELECT utipo_id FROM usuarios_tipos 
                         WHERE utipo_id_empresa = '{$idEmpresa}' 
                         AND (utipo_nombre LIKE '%sin rol%' OR utipo_nombre LIKE '%default%')
                         LIMIT 1";
    
    $resultRolDefault = $conexionBdPrincipal->query($queryrRolDefault);
    
    if ($resultRolDefault && $resultRolDefault->num_rows > 0) {
        $rolDefault = $resultRolDefault->fetch_assoc();
        $rolDefaultId = $rolDefault['utipo_id'];
    } else {
        // Si no existe, usamos el ID 1 como fallback (asegúrate de tener un rol con ID 1)
        $rolDefaultId = 1;
    }
    
    // Actualizar el usuario para quitarlo del rol actual
    $queryActualizar = "UPDATE usuarios 
                        SET usr_tipo = '{$rolDefaultId}' 
                        WHERE usr_id = '{$usuarioId}'";
    
    if (!$conexionBdPrincipal->query($queryActualizar)) {
        throw new Exception("Error al actualizar usuario: " . $conexionBdPrincipal->error);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Usuario removido del rol correctamente'
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

exit();

