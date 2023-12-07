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
$idEmpresa = $_SESSION["dataAdicional"]["id_empresa"];
$meses = array("","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
$opcionesSINO = array("NO","SI");
$estadosCertificados = array("","Vigente","Vencido","Provisional");
$em = array("","NC","NE","EP","IN","AC","PR");
$emColor = array("white","goldenrod","gold","green","limegreen","aqua","tomato");	
$estadosRemision = array("","Entrada","Salida");
$mesesAbre = array("", "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
$opcionesEtapa = array("N/A","En progreso","En espera","Propuesta/Cotización","Negociación/Revisión","Cerrado y ganado","Cerrado y perdido");
$opcionesTipoNegocio = array("N/A","Venta","Servicio","Servicio Post venta");
$opcionesOrigenNegocio = array("N/A","LLamada mercadeo","Email Marketing","Sitio Web","Publicidad","Cliente existente","Recomendación","Exhibición","Otro");