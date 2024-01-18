<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/softjm/constantes.php");
if($_SESSION["id"]=="")
header("Location:../../controlador/salir.php");
else
{
	/*if($_SERVER['HTTP_REFERER']==""){
		echo "<span style='font-family:Arial; color:red;'>Usted no est&aacute; accediendo de manera correcta. Utilice las opciones del sistema.</samp>";
		exit();	
	}
	*/
	include("../config/config.php");
	
	//USUARIO ACTUAL
	$consultaUsuarioActual = mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='".$_SESSION["id"]."'");
	$numUsuarioActual = mysqli_num_rows($consultaUsuarioActual);
	$datosUsuarioActual = mysqli_fetch_array($consultaUsuarioActual, MYSQLI_BOTH);
	//SABER SI ESTA BLOQUEADO
	if($datosUsuarioActual['usr_bloqueado']==1)
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
}
?>
