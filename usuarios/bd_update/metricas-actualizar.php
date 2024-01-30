<?php
require_once("../sesion.php");

$idPagina = 266;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$query = $conexionBdPrincipal->query("UPDATE metricas SET 
met_meta_venta_mes='" . $_POST["metames"] . "', 
met_dias_habiles='" . $_POST["dhabiles"] . "', 
met_bonificacion_mes='" . $_POST["bonificacion"] . "', 
met_punto_equilibrio='" . $_POST["pequilibrio"] . "', 
met_bono_meta='" . $_POST["bonoMeta"] . "'
WHERE met_id='".$_POST['id']. "' AND met_id_empresa = '".$_SESSION["dataAdicional"]["id_empresa"]."'");

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../metricas.php?msg=2&id='.$_POST['id'].'";</script>';	
exit();
