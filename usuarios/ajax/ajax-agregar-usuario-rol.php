<?php
include("../sesion.php");

header('Content-Type: application/json; charset=utf-8');

try {
    $usuarioId = isset($_POST['usuarioId']) ? intval($_POST['usuarioId']) : 0;
    $rolId = isset($_POST['rolId']) ? intval($_POST['rolId']) : 0;
    $idEmpresa = $_SESSION["dataAdicional"]["id_empresa"];
    
    if ($usuarioId <= 0 || $rolId <= 0) {
        throw new Exception("Datos no válidos");
    }
    
    // Verificar que el usuario existe
    $queryVerificar = "SELECT usr_id, usr_nombre, usr_email, usr_login FROM usuarios 
                       WHERE usr_id = '{$usuarioId}' 
                       AND usr_bloqueado != 1
                       AND usr_id_empresa = '{$idEmpresa}'";
    
    $resultVerificar = $conexionBdPrincipal->query($queryVerificar);
    
    if (!$resultVerificar || $resultVerificar->num_rows === 0) {
        throw new Exception("Usuario no encontrado");
    }
    
    $usuario = $resultVerificar->fetch_assoc();
    $email = isset($usuario['usr_email']) && !empty($usuario['usr_email']) ? $usuario['usr_email'] : $usuario['usr_login'];
    
    // Actualizar el rol del usuario
    $queryActualizar = "UPDATE usuarios 
                        SET usr_tipo = '{$rolId}' 
                        WHERE usr_id = '{$usuarioId}'";
    
    if (!$conexionBdPrincipal->query($queryActualizar)) {
        throw new Exception("Error al actualizar usuario: " . $conexionBdPrincipal->error);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Usuario agregado al rol correctamente',
        'usuario' => [
            'id' => intval($usuario['usr_id']),
            'nombre' => $usuario['usr_nombre'],
            'email' => $email,
            'iniciales' => strtoupper(substr($usuario['usr_nombre'], 0, 2))
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

exit();

