<?php
include_once RUTA_PROYECTO."/usuarios/class/Producto.php";

function subirArchivosAlServidor($archivoCargado, $prefijo, $destino){
	$extensionParte1 = explode(".", $archivoCargado['name']);
	$extension = end($extensionParte1);
	$nombreArchivoFinal = uniqid($prefijo."_") . "." . $extension;
	$rutaCompleta = $destino . "/" . $nombreArchivoFinal;
	move_uploaded_file($archivoCargado['tmp_name'], $rutaCompleta);

	return $nombreArchivoFinal;
}

function validarClave($clave) {
    $regex = "/^[a-zA-Z0-9\.\*]{4,20}$/";
    return preg_match($regex, $clave);
}

function generarClaves($length=10)
{
	$key = "";
	$pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
	$max = strlen($pattern)-1;
	for($i = 0; $i < $length; $i++){
		$key .= substr($pattern, mt_rand(0,$max), 1);
	}
	return $key;
}

function informarErrorAlUsuario($linea, $error){
	date_default_timezone_set('America/Bogota');
	$DateAndTime = date('m-d-Y h:i:s a', time());

	return "
	<div style='background-color:tomato; color: black; padding:10px; font-family:Arial;'>
		<p>Ha ocurrido un error al ejecutar esta acción.<br>
		Por favor informe al webmaster sobre este asunto.</p>

		<p>
		DETALLES:<br>
		<b>URL:</b> ".$_SERVER['REQUEST_URI']."<br>
		<b>Linea:</b> $linea<br>
		<b>Fecha y hora:</b> $DateAndTime
		</p>

		<p>
			<pre>".$error."</pre>
		</p>
	</div>
	";
}

function productosPrecioListaUSD($porcentajeUtilidad, $costoEnDolares){
	$utilidadPrincipal   = $porcentajeUtilidad / 100;
	$precioListaUSD      = Producto::CalcularPrecioLista($costoEnDolares, $utilidadPrincipal);

	return $precioListaUSD;
}

function contarClientesPorDepto($depto){

	global $conexionBdAdmin, $conexionBdPrincipal;

	$consultaDeptos = $conexionBdAdmin->query("SELECT ciu_id FROM localidad_ciudades
	WHERE ciu_departamento='".$depto."'");

	while($deptos = mysqli_fetch_array($consultaDeptos, MYSQLI_BOTH)){
		
		$consultaContarClientes = $conexionBdPrincipal->query("SELECT * FROM ".MAINBD.".clientes 
		INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad AND ciu_departamento='".$depto."'
		WHERE (cli_papelera IS NULL OR cli_papelera=0)
		");
		
		return $contarClientes = $consultaContarClientes->num_rows;
	}	

}

function validarVariableGet($get){
	if(isset($get) and is_numeric($get)){
		return true;
	}else{
		return false;
	}
}

function validarReferencia($paginaDeReferencia){
	if( !isset($_SERVER['HTTP_REFERER']) ){
		echo "No hay pagina de referencia de origen.";
		exit();
	}
	
	if( !str_contains($_SERVER['HTTP_REFERER'], $paginaDeReferencia) || !isset($_SERVER['HTTP_REFERER']) ){
		echo "No estás accediendo correctamente.";
		exit();
	}
}

function validarAccesoModulo($empresa, $modulo){

	global $conexionBdAdmin, $datosUsuarioActual;

	if($datosUsuarioActual[3]==1){
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



