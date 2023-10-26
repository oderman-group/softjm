<?php
$consultaConfig2 = $conexionBdPrincipal->query("SELECT * FROM configuracion WHERE conf_id_empresa=".$_SESSION["dataAdicional"]["id_empresa"]);
$configuracion   = mysqli_fetch_array($consultaConfig2, MYSQLI_BOTH);

require_once(RUTA_PROYECTO."/usuarios/class/Modulos.php");

//CONFIGURACIÓN DE VARIABLES DEL PROGRAMA
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

$tipoCrud= array("", "Read", "Create", "Update", "Delete");

$formaPago = array("", "CONTADO", "CRÉDITO");

$tipoBuzon = array("Desc.", "Portafolios", "Cotización");
$estadoBuzon = array("Desc.", "OK", "ERROR");

$tipoFactura = array("Desc.", "Venta", "Compra");
$nacionFactura = array("Nacional", "Extrajera");
$nacionEtiqueta = array("success", "warning");

$origenPrecioProducto = array("N/A", "Costo", "Utilidad", "Costo y utilidad", "Guardado de precios");