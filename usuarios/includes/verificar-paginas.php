<?php
if(!isset($idPagina)){
	echo "Falta el ID de esta página.";
	exit();
	$idPagina = 1;
}

//PAGINAS A LAS QUE TIENE PERMISO EL ROL DEL USUARIO
$consultaPaginaUsuario = $conexionBdPrincipal->query("SELECT * FROM paginas_perfiles
WHERE pper_tipo_usuario='".$datosUsuarioActual[3]."' AND pper_pagina='".$idPagina."'");
$numPaginaUsuario = $consultaPaginaUsuario->num_rows;

/*
La segunda parte de la condición es para darle permiso a los administradores 
a todas las paginas del sistema.
*/
if($numPaginaUsuario == 0 and $datosUsuarioActual[3]!=1)
{
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

$consultaPaginaActual = $conexionBdAdmin->query("SELECT * FROM paginas WHERE pag_id='".$idPagina."'");
$paginaActual = mysqli_fetch_array($consultaPaginaActual, MYSQLI_BOTH);