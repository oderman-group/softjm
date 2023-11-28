<?php
$paso=true;
if(!isset($idPagina)){
	$rutaSalida= "index.php";
	$mensaje= "Falta el ID de esta página.";
	$paso=false;
}else{
	$consultaPaginaActual = $conexionBdAdmin->query("SELECT * FROM paginas WHERE pag_id='".$idPagina."'");
	$paginaActual = mysqli_fetch_array($consultaPaginaActual, MYSQLI_BOTH);

	if(!Modulos::validarAccesoModulo($configuracion['conf_id_empresa'], $paginaActual['pag_id_modulo'], $conexionBdAdmin, $datosUsuarioActual)){
		$rutaSalida= "index.php";
		$mensaje= "La empresa NO tiene permiso a este modulo.";
		$paso=false;
	}
	if($paso && !Modulos::validarRol([$idPagina], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){
		$rutaSalida= "index.php";
		$mensaje= "No tienes permiso para acceder a este pagina. Serás redireccionado al inicio.";
		$paso=false;
	}
}
if (!$paso) {
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

