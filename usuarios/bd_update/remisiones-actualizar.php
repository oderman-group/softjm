<?php
	include("../sesion.php");
	$idPagina = 337;

	include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");


	
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
	}
	
	mysqli_query($conexionBdPrincipal,"UPDATE remisiones SET rem_tipo_equipo='".$_POST["tipoEquipo"]."', rem_equipo='".$_POST["equipo"]."', rem_referencia='".$_POST["referencia"]."', rem_serial='".$_POST["serial"]."', rem_descripcion='".$_POST["descripcion"]."', rem_detalles='".$_POST["detalles"]."', rem_dias_entrega='".$_POST["tiempoEntrega"]."', rem_dias_reclamar='".$_POST["tiempoReclamar"]."', rem_precision_angular='".$_POST["pAngular"]."', rem_precision_distancia='".$_POST["pDistancia"]."', rem_observacion_salida='".$_POST["obsSalida"]."', rem_marca='".$_POST["marca"]."', rem_fecha='".$_POST["fecha"]."', rem_tiempo_certificado='".$_POST["vigenciaCerificado"]."', rem_tipos_equipos='".$_POST["tiposEquipos"]."', rem_supervisor='".$_POST["supervisor"]."',
	
	rem_p1vd_grados='".$_POST["p1vd_grados"]."',
	rem_p1vd_minutos='".$_POST["p1vd_minutos"]."',
	rem_p1vd_segundos='".$_POST["p1vd_segundos"]."',
	rem_p1hd_grados='".$_POST["p1hd_grados"]."',
	rem_p1hd_minutos='".$_POST["p1hd_minutos"]."',
	rem_p1hd_segundos='".$_POST["p1hd_segundos"]."',
	rem_p1vi_grados='".$_POST["p1vi_grados"]."',
	rem_p1vi_minutos='".$_POST["p1vi_minutos"]."',
	rem_p1vi_segundos='".$_POST["p1vi_segundos"]."',
	rem_p1hi_grados='".$_POST["p1hi_grados"]."',
	rem_p1hi_minutos='".$_POST["p1hi_minutos"]."',
	rem_p1hi_segundos='".$_POST["p1hi_segundos"]."',
	
	rem_p2vd_grados='".$_POST["p2vd_grados"]."',
	rem_p2vd_minutos='".$_POST["p2vd_minutos"]."',
	rem_p2vd_segundos='".$_POST["p2vd_segundos"]."',
	rem_p2hd_grados='".$_POST["p2hd_grados"]."',
	rem_p2hd_minutos='".$_POST["p2hd_minutos"]."',
	rem_p2hd_segundos='".$_POST["p2hd_segundos"]."',
	rem_p2vi_grados='".$_POST["p2vi_grados"]."',
	rem_p2vi_minutos='".$_POST["p2vi_minutos"]."',
	rem_p2vi_segundos='".$_POST["p2vi_segundos"]."',
	rem_p2hi_grados='".$_POST["p2hi_grados"]."',
	rem_p2hi_minutos='".$_POST["p2hi_minutos"]."',
	rem_p2hi_segundos='".$_POST["p2hi_segundos"]."',
	
	rem_n1_patron='".$_POST["n1_patron"]."',
	rem_n1_equipo='".$_POST["n1_equipo"]."',
	rem_n1_diferencia='".$_POST["n1_diferencia"]."',
	rem_n2_patron='".$_POST["n2_patron"]."',
	rem_n2_equipo='".$_POST["n2_equipo"]."',
	rem_n2_diferencia='".$_POST["n2_diferencia"]."',
	rem_n3_patron='".$_POST["n3_patron"]."',
	rem_n3_equipo='".$_POST["n3_equipo"]."',
	rem_n3_diferencia='".$_POST["n3_diferencia"]."',
	
	rem_l1a='".$_POST["l1a"]."',
	rem_l1b='".$_POST["l1b"]."',
	rem_l1c='".$_POST["l1c"]."',
	rem_l2a='".$_POST["l2a"]."',
	rem_l2b='".$_POST["l2b"]."',
	rem_l2c='".$_POST["l2c"]."',
	rem_error_detectado='".$_POST["errorDetectado"]."'
	
	WHERE rem_id='".$_POST["id"]."'");
	
	if (!empty($_POST["servicios"])) {
	$numero =(count($_POST["servicios"]));
	$contador=0;
	mysqli_query($conexionBdPrincipal,"DELETE FROM remisiones_servicios WHERE remxs_id_remision='".$_POST["id"]."'");
	
	while($contador<$numero){
		mysqli_query($conexionBdPrincipal,"INSERT INTO remisiones_servicios(remxs_id_remision, remxs_id_servicio)VALUES('".$_POST["id"]."',".$_POST["servicios"][$contador].")");
		
		$contador++;
	}
	
	
	if(!empty($_FILES['imgCertificado']['name'])){
		$extensionParte1 = explode(".", $_FILES['imgCertificado']['name']);
		$extension = end($extensionParte1);
		$archivo = uniqid('rem_') . "." . $extension;
		$destino = "../files/adjuntos";
		move_uploaded_file($_FILES['imgCertificado']['tmp_name'], $destino . "/" . $archivo);

		mysqli_query($conexionBdPrincipal,"UPDATE remisiones SET rem_foto_certificado='".$archivo."' WHERE rem_id='".$_POST["id"]."'");
	}
}else{
}

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="../remisiones-editar.php?id='.$_POST["id"].'&msg=2";</script>';
exit();
