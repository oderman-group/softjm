<?php   
require_once("../sesion.php");
require(RUTA_PROYECTO."/usuarios/funciones-para-el-sistema.php");

$idPagina = 179;
include(RUTA_PROYECTO."/usuarios/verificar-paginas.php");

	if ($_POST["fechaIngreso"] == "") $_POST["fechaIngreso"] = '0000-00-00';
	if ($_POST["retiroFecha"] == "") $_POST["retiroFecha"] = '0000-00-00';
    $consultaZona = $conexionBdAdmin->query("SELECT * FROM localidad_ciudades WHERE ciu_id='" . $_POST["ciudad"] . "'");
	$zona = mysqli_fetch_array($consultaZona, MYSQLI_BOTH);

	if (isset($_POST["usuario"]) and trim($_POST["usuario"]) != "") {
        $consultaClienteV = $conexionBdPrincipal->query("SELECT * FROM clientes WHERE cli_usuario='" . trim($_POST["usuario"]) . "' AND cli_id!='" . $_POST["id"] . "'");
		$clienteV = mysql_num_rows($consultaClienteV);
		if ($clienteV > 0) {
			echo "<div style='font-family:arial; text-align:center'>Ya existe un cliente con este n&uacute;mero de NIT. Verifique para que no lo registre nuevamente.<br><br>
			<a href='javascript:history.go(-1);'>[P&aacute;gina anterior]</a></span> | <a href='clientes.php'>[Ir a clientes]</a></div>";
			exit();
		}
	}

	$conexionBdPrincipal->query("UPDATE clientes SET 
    cli_nombre='" . $_POST["nombre"] . "', 
    cli_referencia='" . $_POST["referencia"] . "', 
    cli_categoria='" . $_POST["categoria"] . "', 
    cli_email='" . $_POST["email"] . "', 
    cli_telefono='" . $_POST["telefono"] . "', 
    cli_ciudad='" . $_POST["ciudad"] . "', 
    cli_usuario='" . trim($_POST["usuarioCliente"]) . "', 
    cli_clave='" . $_POST["claveCliente"] . "', 
    cli_direccion='" . $_POST["direccion"] . "', 
    cli_zona='" . $zona[2] . "', 
    cli_fecha_ingreso='" . $_POST["fechaIngreso"] . "', 
    cli_nivel='" . $_POST["nivel"] . "', 
    cli_celular='" . $_POST["celular"] . "', 
    cli_telefonos='" . $_POST["telefonos"] . "', 
    cli_sigla='" . $_POST["sigla"] . "', 
    cli_causa_retiro='" . $_POST["retiroCausa"] . "', 
    cli_responsable_retiro='" . $_POST["retiroResponsable"] . "', 
    cli_fecha_retiro='" . $_POST["retiroFecha"] . "', 
    cli_retirado='" . $_POST["retirado"] . "', 
    cli_ultima_modificacion=now(), 
    cli_usuario_modificacion='" . $_SESSION["id"] . "', 
    cli_servicios='" . $_POST["servicios"] . "', 
    cli_saldo='" . $_POST["saldo"] . "', 
    cli_clave_documentos='" . $_POST["claveDocumentos"] . "', 
    cli_credito='" . $_POST["credito"] . "', 
    cli_tipo_documento='" . $_POST["tipoDocumento"] . "' 
    WHERE cli_id='" . $_POST["id"] . "'");

if(isset($_POST["grupos"])){    
	$numero = (count($_POST["grupos"]));
	$contador = 0;
	
	while ($contador < $numero) {
		$conexionBdPrincipal->query("INSERT INTO clientes_categorias(cpcat_cliente, cpcat_categoria)VALUES('" . $_POST["id"] . "'," . $_POST["grupos"][$contador] . ")");
		
		$contador++;
	}
}else{
    $conexionBdPrincipal->query("DELETE FROM clientes_categorias WHERE cpcat_cliente='" . $_POST["id"] . "'");
}    


	if ($_POST["asesor"] != "") {
		$conexionBdPrincipal->query("DELETE FROM clientes_usuarios WHERE cliu_cliente='" . $_POST["id"] . "'");

		$conexionBdPrincipal->query("INSERT INTO clientes_usuarios(cliu_usuario, cliu_cliente, cliu_fecha)VALUES('" . $_POST["asesor"] . "'," . $_POST["id"] . ", now())");
	}

    include(RUTA_PROYECTO."/usuarios/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../clientes-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
	exit();
