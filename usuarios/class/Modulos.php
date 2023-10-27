<?php

class Modulos {
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

        if($datosUsuarioActual['usr_tipo']==1){
            return true;
        }

        $consulta = $conexionBdAdmin->query("SELECT * FROM modulos_empresa
        WHERE mxe_id_empresa='".$empresa."' AND mxe_id_modulo='".$modulo."'");
        $numRegistros = $consulta->num_rows;
        
        if($numRegistros>0){
            return true;
        }

        return false;
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
    public static function validarRol($paginas, $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion){

        if ($datosUsuarioActual['usr_tipo'] == 1) { 
            return true;
        }

        $consultaRoles = mysqli_query($conexionBdAdmin, "SELECT upr_id_rol FROM usuarios_roles 
        WHERE upr_id_usuario='".$datosUsuarioActual['usr_id']."' AND upr_id_empresa='".$configuracion['conf_id_empresa']."'");
        
        $numRoles=mysqli_num_rows($consultaRoles);
        if ($numRoles<1) { 
            return true;
        }else{
            $Roles = mysqli_fetch_all($consultaRoles, MYSQLI_ASSOC);
            $valoresArray = array_column($Roles, 'upr_id_rol');
            $valoresCadena = implode(',', $valoresArray);
            
            $consultaPaginaRoles = mysqli_query($conexionBdPrincipal, "SELECT * FROM paginas_perfiles 
            WHERE pper_tipo_usuario IN ($valoresCadena)");
                
            $RolesPaginas = mysqli_fetch_all($consultaPaginaRoles, MYSQLI_ASSOC);
            $valoresPaginas = array_column($RolesPaginas, 'pper_pagina');
            $permitidos= array_intersect($paginas,$valoresPaginas);
            if(!empty($permitidos)){
                return true;
            }
        }
        return false;
    }
}