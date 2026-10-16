<?php

class Modulos {

    /** @var array<string, bool> */
    private static $cacheValidarRol = [];

    /** @var array<string, int[]>|null */
    private static $cachePaginasUsuario = null;

    /**
     * Este metodo sirve para validar el acceso a los modulos
     * 
     * @param int       $empresa
     * @param int       $modulo
     * @param mysqli    $conexionBdAdmin
     * @param array     $datosUsuarioActual
     * 
     * @return bool
    **/
    public static function validarAccesoModulo($empresa, $modulo, $conexionBdAdmin, $datosUsuarioActual){

        if ($datosUsuarioActual['usr_tipo'] == ADMIN) {
            return true;
        }

        $consulta = $conexionBdAdmin->query("SELECT * FROM modulos_empresa
        WHERE mxe_id_empresa='".$empresa."' AND mxe_id_modulo='".$modulo."'");
        $numRegistros = $consulta->num_rows;
        
        if ($numRegistros>0) {
            return true;
        }

        return false;
    }

    /**
     * Páginas permitidas del usuario actual (una sola carga por request).
     *
     * @return int[]|null null = ADMIN o sin roles (acceso total)
     */
    private static function obtenerPaginasPermitidasUsuario(
        $conexionBdPrincipal,
        $conexionBdAdmin,
        $datosUsuarioActual,
        $configuracion
    ): ?array {
        if (self::$cachePaginasUsuario !== null) {
            return self::$cachePaginasUsuario['paginas'] ?? null;
        }

        if ($datosUsuarioActual['usr_tipo'] == ADMIN) {
            self::$cachePaginasUsuario = ['paginas' => null, 'acceso_total' => true];
            return null;
        }

        $consultaRoles = mysqli_query(
            $conexionBdAdmin,
            "SELECT upr_id_rol FROM usuarios_roles
             WHERE upr_id_usuario='" . intval($datosUsuarioActual['usr_id']) . "'
               AND upr_id_empresa='" . intval($configuracion['conf_id_empresa']) . "'"
        );

        $numRoles = $consultaRoles ? mysqli_num_rows($consultaRoles) : 0;
        if ($numRoles < 1) {
            self::$cachePaginasUsuario = ['paginas' => null, 'acceso_total' => true];
            return null;
        }

        $Roles = mysqli_fetch_all($consultaRoles, MYSQLI_ASSOC);
        $valoresArray = array_map('intval', array_column($Roles, 'upr_id_rol'));
        $valoresArray = array_values(array_filter($valoresArray));
        if (empty($valoresArray)) {
            self::$cachePaginasUsuario = ['paginas' => null, 'acceso_total' => true];
            return null;
        }

        $valoresCadena = implode(',', $valoresArray);
        $consultaPaginaRoles = mysqli_query(
            $conexionBdPrincipal,
            "SELECT pper_pagina FROM paginas_perfiles WHERE pper_tipo_usuario IN ($valoresCadena)"
        );

        $paginas = [];
        while ($consultaPaginaRoles && ($fila = mysqli_fetch_assoc($consultaPaginaRoles))) {
            $paginas[] = intval($fila['pper_pagina']);
        }
        $paginas = array_values(array_unique($paginas));

        self::$cachePaginasUsuario = ['paginas' => $paginas, 'acceso_total' => false];
        return $paginas;
    }

    /**
     * Este metodo sirve para validar el acceso a las diferentes paginas dependiendo de los roles del usuario
     * 
     * @param array     $paginas
     * @param mysqli    $conexionBdPrincipal
     * @param mysqli    $conexionBdAdmin
     * @param array     $datosUsuarioActual
     * @param array     $configuracion
     * 
     * @return bool
    **/
    public static function validarRol(
        $paginas, 
        $conexionBdPrincipal, 
        $conexionBdAdmin, 
        $datosUsuarioActual, 
        $configuracion
    ) {
        if (!is_array($paginas)) {
            $paginas = [$paginas];
        }
        $paginas = array_values(array_map('intval', $paginas));
        sort($paginas);
        $cacheKey = intval($datosUsuarioActual['usr_id'] ?? 0) . ':' . implode(',', $paginas);

        if (array_key_exists($cacheKey, self::$cacheValidarRol)) {
            return self::$cacheValidarRol[$cacheKey];
        }

        if ($datosUsuarioActual['usr_tipo'] == ADMIN) {
            return self::$cacheValidarRol[$cacheKey] = true;
        }

        $paginasPermitidas = self::obtenerPaginasPermitidasUsuario(
            $conexionBdPrincipal,
            $conexionBdAdmin,
            $datosUsuarioActual,
            $configuracion
        );

        // null = acceso total (admin o sin roles asignados, comportamiento histórico)
        if ($paginasPermitidas === null) {
            return self::$cacheValidarRol[$cacheKey] = true;
        }

        $permitidos = array_intersect($paginas, $paginasPermitidas);
        return self::$cacheValidarRol[$cacheKey] = !empty($permitidos);
    }
}
