<?php
session_start();

const RUTA_PROYECTO = "C:/xampp/htdocs/works-projects/softjm";

if($_SESSION["id"]=="")
	header("Location:../salir.php");
else
{
	$tiempo_inicial = microtime(true);
	
	require_once(RUTA_PROYECTO."/conexion.php");
	require_once(RUTA_PROYECTO."/usuarios/config/config.php");

	//USUARIO ACTUAL
	$consultaUsuarioActual = $conexionBdPrincipal->query("SELECT * FROM usuarios WHERE usr_id='".$_SESSION["id"]."'");
	$numUsuarioActual = $consultaUsuarioActual->num_rows;
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

<?php
//CONFIGURACIÓN DEL PROGRAMA
$monedas = array("","COP","USD");
$monedasExt = array("","USD","EURO");
$simbolosMonedas = array("","$","USD");
$tipoTicket = array("", "Comercial", "Soporte técnico", "Soporte Operativo");
$referenciaLlegada = array("", "Publicidad física", "Publicidad digital", "Sitio Web", "Evento", "Email", "Llamada", "Referencia Cliente", "Referencia Empleado", "WhatsApp", "Facebook", "Google", "Otro");
$negociosPerdidos = array("", "Precio", "Calidad", "Atención", "Inventario");
$negociosGanados = array("", "Precio", "Calidad", "Atención");
$estadoRegistros = array("Inactivo", "Activo");
$opcionSINO = array("NO", "SI");
$tipoDocumento = array("Desc.", "Desc.", "NIT", "Cédula");

$tipoBuzon = array("Desc.", "Portafolios", "Cotización");
$estadoBuzon = array("Desc.", "OK", "ERROR");

$tipoFactura = array("Desc.", "Venta", "Compra");
$nacionFactura = array("Nacional", "Extrajera");
$nacionEtiqueta = array("success", "warning");

$origenPrecioProducto = array("N/A", "Costo", "Utilidad", "Costo y utilidad", "Guardado de precios");