<?php
    require_once("../sesion.php");

    $idPagina = 189;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    
    if ($_FILES['contrato']['name'] != "") {
        $destino = "../files/contratos/";
        $contrato='cont_'.basename($_FILES['contrato']['name']);
        $archivo=$destino.$contrato;
        move_uploaded_file($_FILES['contrato']['tmp_name'],$archivo);

		mysql_query("UPDATE clientes_orion SET clio_contrato='" . $contrato . "' WHERE clio_id='" . $_POST["id"] . "'");
    }

	$conexionBdAdmin->query("UPDATE clientes_orion SET  clio_empresa='" . $_POST["nombre"] . "', clio_email='" . $_POST["email"] . "', clio_telefono='" . $_POST["telefono"] . "', clio_contacto_principal='" . $_POST["contacto"] . "', clio_fecha_inicio='" . $_POST["inicio"] . "', clio_fecha_fin='" . $_POST["fin"] . "' WHERE clio_id='" . $_POST["id"] . "'");

    if(isset($_POST["modulo"])){
        $conexionBdAdmin->query("DELETE FROM modulos_empresa WHERE mxe_id_empresa='" . $_POST["id"] . "'");
    
        $numero = (count($_POST["modulo"]));
        $contador = 0;        
        while ($contador < $numero) {
    
            $conexionBdAdmin->query("INSERT INTO modulos_empresa(mxe_id_modulo, mxe_id_empresa)VALUES('" . $_POST["modulo"][$contador] . "','" . $_POST["id"] . "')");
            
            $contador++;
        }
    }else{
        $conexionBdAdmin->query("DELETE FROM modulos_empresa WHERE mxe_id_empresa='" . $_POST["id"] . "'");
    } 

	echo '<script type="text/javascript">window.location.href="../clientes-orion-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");
    exit();