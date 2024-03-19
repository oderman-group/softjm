<?php
$conexion = mysqli_connect("localhost", "odermancom_jm_crm", ")S{q9V7hBJv;");
mysqli_select_db($conexion, "odermancom_jm_crm");


$consulta = mysqli_query($conexion, "SELECT * FROM usuarios_tipos WHERE utipo_id=7");

while ($resultado = mysqli_fetch_array($consulta)) {

	$consultaDos = mysqli_query($conexion, "SELECT * FROM paginas");


	while ($resultadoDos = mysqli_fetch_array($consultaDos)) {

		$resultadoTres = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM paginas_perfiles WHERE pper_tipo_usuario='".$resultado['utipo_id']."' AND pper_pagina='".$resultadoDos['pag_id']."'"));

		if($resultadoTres[0]!=""){
			mysqli_query($conexion, "DELETE FROM paginas_perfiles WHERE pper_tipo_usuario='".$resultado['utipo_id']."' AND pper_pagina='".$resultadoDos['pag_id']."'");
		}else{
			mysqli_query($conexion, "INSERT INTO paginas_perfiles (pper_tipo_usuario, pper_pagina)VALUES('".$resultado['utipo_id']."', '".$resultadoDos['pag_id']."')");
		}


	}

}