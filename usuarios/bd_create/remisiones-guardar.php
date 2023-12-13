<?php
	include("../sesion.php");
	$idPagina = 336;

	include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

    if(!empty($_POST["nombreCliente"]) and !empty($_POST["usuarioCliente"]) and !empty($_POST["ciudadCliente"])){
		
		$clienteV = mysqli_num_rows(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_usuario='".trim($_POST["usuarioCliente"])."'"));
		if($clienteV>0){
			echo "<div style='font-family:arial; text-align:center'>Ya existe un cliente con este n&uacute;mero de NIT. Verifique para que no lo registre nuevamente.<br><br>
			<a href='javascript:history.go(-1);'>[P&aacute;gina anterior]</a></span> | <a href='clientes.php'>[Ir a clientes]</a></div>";
			exit();
		}
		
		$zona = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM ".BDADMIN.".localidad_ciudades WHERE ciu_id='".$_POST["ciudadCliente"]."'"));
		
		mysqli_query($conexionBdPrincipal,"INSERT INTO clientes(cli_nombre, cli_categoria, cli_email, cli_ciudad, cli_usuario, cli_clave, cli_zona, cli_fecha_registro, cli_fecha_ingreso, cli_celular, cli_responsable, cli_id_empresa)VALUES('".$_POST["nombreCliente"]."',2,'".$_POST["emailCliente"]."','".$_POST["ciudadCliente"]."','".trim($_POST["usuarioCliente"])."','".$_POST["usuarioCliente"]."','".$zona[2]."',now(),now(),'".$_POST["celularCliente"]."','".$_SESSION["id"]."', '".$idEmpresa."')");
		
		$idInsertU = mysqli_insert_id($conexionBdPrincipal);
		
		$_POST["cliente"] = $idInsertU;

		mysqli_query($conexionBdPrincipal,"INSERT INTO sucursales(sucu_cliente_principal, sucu_ciudad, sucu_celular, sucu_nombre)
		VALUES('".$idInsertU."', '".$_POST["ciudadCliente"]."', '".$_POST["celularCliente"]."','Sede principal')");
		
		$idSucursal = mysqli_insert_id($conexionBdPrincipal);
		
		
		mysqli_query($conexionBdPrincipal,"INSERT INTO contactos(cont_nombre, cont_email, cont_cliente_principal, cont_celular, cont_sucursal)
		VALUES('".$_POST["nombreContacto"]."', '".$_POST["emailContacto"]."', '".$_POST["cliente"]."', '".$_POST["celularContacto"]."', '".$idSucursal."')");
		
		$idContacto = mysqli_insert_id($conexionBdPrincipal);
		$_POST["contacto"] = $idContacto;
	}
	
	switch($_POST["tipoEquipo"]){
		case 1: $_POST["equipo"] = 'Estación total'; break;
		case 2: $_POST["equipo"] = 'Teodolito'; break;
		case 3: $_POST["equipo"] = 'Nivel'; break;
		case 4: $_POST["equipo"] = 'GPS'; break;
		case 5: $_POST["equipo"] = 'Nivel digital'; break;
		case 6: $_POST["equipo"] = 'Distanciómetro'; break;
		case 7: $_POST["equipo"] = 'Nivel laser'; break;
		case 8: $_POST["equipo"] = 'Semi-estación'; break;
		case 9: $_POST["equipo"] = 'Colector'; break;
		case 10: $_POST["equipo"] = 'Brújula'; break;
		case 11: $_POST["equipo"] = 'Bastón'; break;
		case 12: $_POST["equipo"] = 'Trípode'; break;
		case 13: $_POST["equipo"] = 'Prisma'; break;
		case 14: $_POST["equipo"] = 'Batería'; break;
		case 15: $_POST["equipo"] = 'Radio'; break;
		case 16: $_POST["equipo"] = 'Estuche'; break;
		case 17: $_POST["equipo"] = 'Drone'; break;
		case 18: $_POST["equipo"] = 'MATENIMIENTO GENERAL DRON'; break;
	}
	
	if(!empty($_POST["nombreContacto"]) and !empty($_POST["emailContacto"])){	
		mysqli_query($conexionBdPrincipal,"INSERT INTO contactos(cont_nombre, cont_email, cont_cliente_principal, cont_celular)
		VALUES('".$_POST["nombreContacto"]."', '".$_POST["emailContacto"]."', '".$_POST["cliente"]."', '".$_POST["celularContacto"]."')");
		
		$idContacto = mysqli_insert_id($conexionBdPrincipal);
		$_POST["contacto"] = $idContacto;
	}
	
	if(!empty($_FILES['imagen']['name'])){
		$extensionParte1 = explode(".", $_FILES['imagen']['name']);
		$extension = end($extensionParte1);
		$archivo = uniqid('rem_') . "." . $extension;
		$destino = "../files/adjuntos";
		move_uploaded_file($_FILES['imagen']['tmp_name'], $destino . "/" . $archivo);
	}
	
	mysqli_query($conexionBdPrincipal,"INSERT INTO remisiones(rem_fecha, rem_cliente, rem_equipo, rem_referencia, rem_serial, rem_descripcion, rem_estado, rem_asesor, rem_detalles, rem_dias_entrega, rem_dias_reclamar, rem_marca, rem_tipo_equipo, rem_precision_angular, rem_precision_distancia, rem_observacion_salida, rem_contacto, rem_fecha_registro, rem_tiempo_certificado, rem_archivo, rem_tipos_equipos, rem_id_empresa)VALUES(now(), '".$_POST["cliente"]."', '".$_POST["equipo"]."', '".$_POST["referencia"]."', '".$_POST["serial"]."', '".$_POST["descripcion"]."', 1, '".$_SESSION["id"]."', '".$_POST["detalles"]."', '".$_POST["tiempoEntrega"]."', '".$_POST["tiempoReclamar"]."', '".$_POST["marca"]."', '".$_POST["tipoEquipo"]."', '".$_POST["pAngular"]."', '".$_POST["pDistancia"]."', '".$_POST["obsSalida"]."', '".$_POST["contacto"]."', now(), '".$_POST["vigenciaCerificado"]."', '".$archivo."', '".$_POST["tiposEquipos"]."', '".$idEmpresa."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	if (!empty($_POST["servicios"])) {
		$numero = count($_POST["servicios"]);
		$contador = 0;
	
		mysqli_query($conexionBdPrincipal, "DELETE FROM remisiones_servicios WHERE remxs_id_remision='".$idInsertU."'");
		while ($contador < $numero) {
			mysqli_query($conexionBdPrincipal, "INSERT INTO remisiones_servicios(remxs_id_remision, remxs_id_servicio) VALUES ('".$idInsertU."', ".$_POST["servicios"][$contador].")");
			$contador++;
		}
	}

	include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="../remisiones.php?id='.$idInsertU.'&msg=1&cte='.$_POST["cliente"].'";</script>';
	exit();