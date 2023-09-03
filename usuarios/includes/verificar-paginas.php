<?php
$paso=true;
if(!isset($idPagina)){
	$rutaSalida= "index.php";
	$mensaje= "Falta el ID de esta página.";
	$paso=false;
}else{
	$consultaPaginaActual = $conexionBdAdmin->query("SELECT * FROM paginas WHERE pag_id='".$idPagina."'");
	$paginaActual = mysqli_fetch_array($consultaPaginaActual, MYSQLI_BOTH);

	if(!validarAccesoModulo($configuracion['conf_id_empresa'], $paginaActual['pag_id_modulo'])){
		$rutaSalida= "index.php";
		$mensaje= "La empresa NO tiene permiso a este modulo.";
		$paso=false;
	}
}
if ($paso!=true) {
?>
	<!DOCTYPE html>
	<html lang="es">
		<head>
			<meta charset="UTF-8">
			<meta http-equiv="refresh" content="2;url=<?= $rutaSalida ?>">
			<title>Redireccionando...</title>
			<style>
				html, body {
					height: 100%;
					margin: 0;
					display: flex;
					justify-content: center;
					align-items: center;
					background-color: #FAFAFA;
					/* font-family: Arial, sans-serif; */
				}

				.mensaje {
					text-align: center;
				}
			</style>
		</head>
		<body>
			<div class="mensaje">
				<p><span style='font-family:Arial; font-weight:bold; color: blue;'><?= $mensaje ?></samp></p>
				<p>Redireccionando en 2 segundos...</p>
			</div>
			<script>
				setTimeout(function() {
					window.location.href = '<?= $rutaSalida ?>';
				}, 2000);
			</script>
		</body>
	</html>
<?php
	exit();	
}

//PAGINAS A LAS QUE TIENE PERMISO EL ROL DEL USUARIO
$consultaPaginaUsuario = $conexionBdPrincipal->query("SELECT * FROM paginas_perfiles
WHERE pper_tipo_usuario='".$datosUsuarioActual[3]."' AND pper_pagina='".$idPagina."'");
$numPaginaUsuario = $consultaPaginaUsuario->num_rows;

/*
La segunda parte de la condición es para darle permiso a los administradores 
a todas las paginas del sistema.
*/
if($numPaginaUsuario == 0 and $datosUsuarioActual[3]!=1){
?>
		<span style='font-family:Arial; color:red;'>No tienes permiso para acceder a este pagina. Ser&aacute;s redireccionado al inicio...</samp>
        <script type="text/javascript">
		function sacar(){
			window.location.href="index.php";
		} 
		setInterval('sacar()', 3000);
        </script>
<?php
	exit();	
}