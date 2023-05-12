<?php
session_start();
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
	$consultaUsuarioActual = mysql_query("SELECT * FROM usuarios WHERE usr_id='".$_SESSION["id"]."'",$conexion);
	$numUsuarioActual = mysql_num_rows($consultaUsuarioActual);
	$datosUsuarioActual = mysql_fetch_array($consultaUsuarioActual);
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
