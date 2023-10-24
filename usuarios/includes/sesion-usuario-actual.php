
<?php
$datosUsuarioActual = $_SESSION["dataAdicional"]["datos_usuario_actual"];

if( empty($datosUsuarioActual) ){
	echo "<span style='font-family:Arial; color:red;'>El usuario con ID <b>".$_SESSION["id"]."</b> no existe.</samp>";
	exit();
}

//SABER SI ESTA BLOQUEADO
if($datosUsuarioActual['usr_bloqueado'] == 1)
{
?>		
	<span style='font-family:Arial; color:red;'>Su usuario ha sido bloqueado. Por tanto no tiene permisos para acceder al Sistema.</samp>
	<script type="text/javascript">
	function sacar(){
		window.location.href="../salir.php";
	} 
	setInterval('sacar()', 3000);
	</script>
<?php	
	exit();		
}