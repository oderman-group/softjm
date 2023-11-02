<?php
    require_once("../sesion.php");
    $idPagina = 51;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    $usr_login = $_POST["usuario"] . $_POST["dominio"];
    $conexionBdPrincipal->query("INSERT INTO usuarios(usr_login, usr_clave, usr_nombre, usr_email, usr_bloqueado, usr_ciudad, usr_area, usr_sucursal)VALUES('" . $usr_login . "',SHA1('" . $_POST["clave"] . "'),'" . $_POST["nombre"] . "','" . $_POST["email"] . "',0,'" . $_POST["ciudad"] . "','" . $_POST["area"] . "', '" . $_POST["sucursal"] . "')");

    $idInsertU = mysqli_insert_id($conexionBdPrincipal);
    $id_empresa = $_SESSION["dataAdicional"]["id_empresa"];
    foreach ($_POST["tipoU"] as $rol) {
        $query = "INSERT INTO usuarios_roles(upr_id_usuario, upr_id_empresa,upr_id_rol,upr_fec, upr_responsable) VALUES(?, ?, ?, ?, ?)";

        if ($stmtRoles = $conexionBdAdmin->prepare($query)) {
            $upr_fec = date("Y-m-d H:i:s");
            $upr_responsable = $_SESSION["id"];
            $stmtRoles->bind_param("iissi", $idInsertU, $id_empresa, $rol, $upr_fec, $upr_responsable);
            $stmtRoles->execute();
            $stmtRoles->close();
        }
    }


    $numero = (count($_POST["zona"]));
    $contador = 0;
    $conexionBdPrincipal->query("DELETE FROM zonas_usuarios WHERE zpu_usuario='" . $idInsertU . "'");

    while ($contador < $numero) {
        $conexionBdPrincipal->query("INSERT INTO zonas_usuarios(zpu_usuario, zpu_zona)VALUES('" . $idInsertU . "'," . $_POST["zona"][$contador] . ")");
        $contador++;
    }

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../usuarios-editar.php?id=' . $idInsertU . '&msg=1";</script>';
    exit();