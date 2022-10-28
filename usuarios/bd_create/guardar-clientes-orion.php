<?php
    require_once("../sesion.php");

    $idPagina = 188;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    
    $destino = "../files/contratos/";
    $contrato='cont_'.basename($_FILES['contrato']['name']);
    $archivo=$destino.$contrato;
    move_uploaded_file($_FILES['contrato']['tmp_name'],$archivo);
    
    $conexionBdAdmin->query("INSERT INTO clientes_orion (clio_empresa, clio_email, clio_telefono, clio_contacto_principal, clio_fecha_inicio, clio_fecha_fin, clio_contrato)VALUES('" . $_POST["nombre"] . "', '" . $_POST["email"] . "', '" . $_POST["telefono"] . "', '" . $_POST["contacto"] . "', '" . $_POST["inicio"] . "', '" . $_POST["fin"] . "', '" . $contrato . "')");
    $idInsert = mysqli_insert_id($conexionBdAdmin);

    $numero = (count($_POST["modulo"]));
    if ($numero > 0) {
        $contador = 0;
        while ($contador < $numero) {
    
            $conexionBdAdmin->query("INSERT INTO modulos_empresa(mxe_id_modulo, mxe_id_empresa)VALUES('" . $_POST["modulo"][$contador] . "','" . $idInsert . "')");
    
            $contador++;
        }
    }

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");
    exit();