<?php
include("../sesion.php");

header('Content-Type: application/json; charset=utf-8');

try {
    $tipoUsuario = isset($_GET['tipoUsuario']) ? intval($_GET['tipoUsuario']) : 0;
    $idEmpresa = $_SESSION["dataAdicional"]["id_empresa"];
    
    if ($tipoUsuario <= 0) {
        throw new Exception("Tipo de usuario no válido");
    }
    
    // Obtener todos los módulos de la empresa
    $queryModulos = "SELECT mod_id, mod_nombre, mod_padre 
                     FROM modulos 
                     INNER JOIN modulos_empresa ON mxe_id_modulo=mod_id 
                        AND mxe_id_empresa='{$idEmpresa}' 
                     WHERE mod_padre IS NULL
                     ORDER BY mod_nombre ASC";
    
    $resultModulos = $conexionBdAdmin->query($queryModulos);
    
    if (!$resultModulos) {
        throw new Exception("Error al consultar módulos: " . $conexionBdAdmin->error);
    }
    
    $modulos = [];
    $todasLasPaginas = [];
    
    while ($modulo = $resultModulos->fetch_assoc()) {
        $moduloId = $modulo['mod_id'];
        $moduloNombre = $modulo['mod_nombre'];
        
        // Obtener las páginas de este módulo con su estado de permiso
        $queryPaginas = "SELECT 
                            p.pag_id, 
                            p.pag_nombre, 
                            p.pag_ruta,
                            p.pag_descripcion,
                            p.pag_tipo_crud,
                            p.pag_id_modulo,
                            pp.pper_id,
                            CASE 
                                WHEN pp.pper_id IS NOT NULL THEN 1 
                                ELSE 0 
                            END as tiene_permiso
                         FROM paginas p 
                         LEFT JOIN ".MAINBD.".paginas_perfiles pp 
                            ON p.pag_id = pp.pper_pagina 
                            AND pp.pper_tipo_usuario = '{$tipoUsuario}'
                         WHERE p.pag_id_modulo = '{$moduloId}'
                         ORDER BY p.pag_nombre ASC";
        
        $resultPaginas = $conexionBdAdmin->query($queryPaginas);
        
        if (!$resultPaginas) {
            throw new Exception("Error al consultar páginas: " . $conexionBdAdmin->error);
        }
        
        $paginas = [];
        
        while ($pagina = $resultPaginas->fetch_assoc()) {
            $paginaData = [
                'id' => intval($pagina['pag_id']),
                'nombre' => $pagina['pag_nombre'],
                'ruta' => $pagina['pag_ruta'] ?? '',
                'descripcion' => $pagina['pag_descripcion'] ?? '',
                'tipo_crud' => intval($pagina['pag_tipo_crud'] ?? 0),
                'modulo_id' => intval($pagina['pag_id_modulo']),
                'modulo_nombre' => $moduloNombre,
                'tiene_permiso' => (bool)$pagina['tiene_permiso']
            ];
            
            $paginas[] = $paginaData;
            $todasLasPaginas[] = $paginaData;
        }
        
        $modulos[] = [
            'id' => intval($moduloId),
            'nombre' => $moduloNombre,
            'padre' => $modulo['mod_padre'],
            'paginas' => $paginas
        ];
    }
    
    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'modulos' => $modulos,
        'paginas' => $todasLasPaginas,
        'total_modulos' => count($modulos),
        'total_paginas' => count($todasLasPaginas)
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
} catch (Exception $e) {
    // Respuesta de error
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

exit();

