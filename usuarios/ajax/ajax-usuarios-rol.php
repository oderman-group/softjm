<?php
include("../sesion.php");

header('Content-Type: application/json; charset=utf-8');

try {
    $rolId = isset($_GET['rolId']) ? intval($_GET['rolId']) : 0;
    $idEmpresa = $_SESSION["dataAdicional"]["id_empresa"];
    
    if ($rolId <= 0) {
        throw new Exception("Rol no válido");
    }
    
    // Obtener usuarios con este rol
    $query = "SELECT u.usr_id, u.usr_nombre, u.usr_email, u.usr_login
              FROM usuarios u
              WHERE u.usr_tipo = '{$rolId}' 
              AND u.usr_bloqueado != 1
              AND u.usr_id_empresa = '{$idEmpresa}'
              ORDER BY u.usr_nombre ASC";
    
    $result = $conexionBdPrincipal->query($query);
    
    if (!$result) {
        throw new Exception("Error al consultar usuarios: " . $conexionBdPrincipal->error);
    }
    
    $usuarios = [];
    while ($usuario = $result->fetch_assoc()) {
        $email = isset($usuario['usr_email']) && !empty($usuario['usr_email']) ? $usuario['usr_email'] : $usuario['usr_login'];
        $usuarios[] = [
            'id' => intval($usuario['usr_id']),
            'nombre' => $usuario['usr_nombre'],
            'email' => $email,
            'login' => $usuario['usr_login'],
            'iniciales' => strtoupper(substr($usuario['usr_nombre'], 0, 2))
        ];
    }
    
    echo json_encode([
        'success' => true,
        'usuarios' => $usuarios,
        'total' => count($usuarios)
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

exit();

