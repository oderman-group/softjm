<?php
    require_once("../sesion.php");
    $idPagina = 51;
    include(RUTA_PROYECTO."/usuarios/verificar-paginas.php");

    $conexionBdPrincipal->query("INSERT INTO usuarios(usr_login, usr_clave, usr_tipo, usr_nombre, usr_email, usr_bloqueado, usr_ciudad, usr_area, usr_sucursal)VALUES('" . $_POST["usuario"] . "',SHA1('" . $_POST["clave"] . "'),'" . $_POST["tipoU"] . "','" . $_POST["nombre"] . "','" . $_POST["email"] . "',0,'" . $_POST["ciudad"] . "','" . $_POST["area"] . "', '" . $_POST["sucursal"] . "')");

    $idInsertU = mysqli_insert_id($conexionBdPrincipal);
    $numero = (count($_POST["zona"]));
    $contador = 0;
    $conexionBdPrincipal->query("DELETE FROM zonas_usuarios WHERE zpu_usuario='" . $idInsertU . "'");

    while ($contador < $numero) {
        $conexionBdPrincipal->query("INSERT INTO zonas_usuarios(zpu_usuario, zpu_zona)VALUES('" . $idInsertU . "'," . $_POST["zona"][$contador] . ")");
        $contador++;
    }

    include(RUTA_PROYECTO."/usuarios/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../usuarios-editar.php?id=' . $idInsertU . '&msg=1";</script>';
    exit();