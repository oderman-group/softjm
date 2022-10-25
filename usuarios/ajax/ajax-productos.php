<?php 
include("../sesion.php");
//EDITAR PRODUCTOS
if($_POST["proceso"]==1){
	mysql_query("UPDATE ".$_POST["tabla"]." SET ".$_POST["campo"]."='".$_POST["valor"]."', prod_ultima_actualizacion=now(), prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' WHERE ".$_POST["pk"]."='".$_POST["producto"]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	
	if($_POST["campo"]=='prod_utilidad'){
		$datos = mysql_fetch_array(mysql_query("SELECT * FROM productos WHERE prod_id='".$_POST["producto"]."'",$conexion));

		$utilidad = $_POST["valor"]/100;
		$precio1 = $datos['prod_costo'] + ($datos['prod_costo']*$utilidad);

		mysql_query("INSERT INTO productos_historial_precios(php_producto, php_precio_anterior, php_precio_nuevo, php_usuario, php_causa)VALUES('".$_POST["producto"]."', '".$datos['prod_precio']."', '".$precio1."', '".$_SESSION["id"]."', 2)");
		if(mysql_errno()!=0){echo mysql_error(); exit();}

		mysql_query("UPDATE productos SET prod_precio='".$precio1."', prod_ultima_actualizacion=now(), prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' WHERE prod_id='".$_POST["producto"]."'",$conexion);
		if(mysql_errno()!=0){echo mysql_error(); exit();}

	}
	
	if($_POST["campo"]=='prod_costo'){
		$datos = mysql_fetch_array(mysql_query("SELECT * FROM productos WHERE prod_id='".$_POST["producto"]."'",$conexion));
		$utilidad = $datos['prod_utilidad']/100;
		$precio1 = $_POST["valor"] + ($_POST["valor"]*$utilidad);

		mysql_query("INSERT INTO productos_historial_precios(php_producto, php_precio_anterior, php_precio_nuevo, php_usuario, php_causa)VALUES('".$_POST["producto"]."', '".$datos['prod_precio']."', '".$precio1."', '".$_SESSION["id"]."', 1)");
		if(mysql_errno()!=0){echo mysql_error(); exit();}

		mysql_query("UPDATE productos SET prod_precio='".$precio1."', prod_ultima_actualizacion=now(), prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' WHERE prod_id='".$_POST["producto"]."'",$conexion);
		if(mysql_errno()!=0){echo mysql_error(); exit();}
	}
}

//PRODUCTOS DE LA COTIZACIÓN
if($_POST["proceso"]==2){
	$consultaProducto=$conexionBdPrincipal->query("SELECT * FROM cotizacion_productos INNER JOIN productos ON prod_id=czpp_producto WHERE czpp_id='".$_POST["producto"]."' ");
	$datosProducto = mysqli_fetch_array($consultaProducto, MYSQLI_BOTH);

	if($_POST["campo"]=='czpp_descuento'){
		
		if($_POST["valor"]>$datosProducto['prod_descuento1']){
			echo '<script type="text/javascript">alert("El descuento que está otorgando es mayor al máximo permitido para este producto, el cual es de '.$datosProducto['prod_descuento1'].'%.");</script>';
			exit();	
		}
	}

	if($_POST["campo"]=='czpp_valor'){
		
		if($_POST["valor"]<$datosProducto['prod_precio'] and $_POST['tipoCliente'] == 1){

			echo '<script type="text/javascript">alert("El precio que está otorgando es menor al máximo permitido para este producto, el cual es de $'.number_format($datosProducto['prod_precio'],0,".",".").'.");</script>';
			exit();	
		}
	}

	$conexionBdPrincipal->query("UPDATE cotizacion_productos SET ".$_POST["campo"]."='".$conexionBdPrincipal->real_escape_string( $_POST["valor"])."' WHERE czpp_id='".$_POST["producto"]."'");
	
	//echo '<script type="text/javascript">location.reload();</script>';
}

//PRODUCTOS DESDE LA CATEGORIA
if($_POST["proceso"]==3){
	mysql_query("UPDATE productos SET ".$_POST["campo"]."='".$_POST["valor"]."', prod_ultima_actualizacion=now(), prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' WHERE prod_categoria='".$_POST["producto"]."' AND prod_precio_predeterminado=0",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	
	if($_POST["campo"]=='prod_utilidad'){
		$utilidad = $_POST["valor"]/100;
		$productos = mysql_query("SELECT * FROM productos WHERE prod_categoria='".$_POST["producto"]."' AND prod_precio_predeterminado=0",$conexion);
		while($datos = mysql_fetch_array($productos)){
			$precio1 = $datos['prod_costo'] + ($datos['prod_costo']*$utilidad);
			mysql_query("UPDATE productos SET prod_precio='".$precio1."', prod_ultima_actualizacion=now(), prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' WHERE prod_id='".$datos['prod_id']."' AND prod_precio_predeterminado=0",$conexion);
			if(mysql_errno()!=0){echo mysql_error(); exit();}
		}	
	}
	
	mysql_query("UPDATE productos_categorias SET ".$_POST["nombreCat"]."='".$_POST["valor"]."', catp_usuario='".$_SESSION["id"]."', catp_fecha=now() WHERE catp_id='".$_POST["producto"]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
}

//PRODUCTOS DESDE EL GRUPO
if($_POST["proceso"]==4){
	mysql_query("UPDATE productos SET ".$_POST["campo"]."='".$_POST["valor"]."', prod_ultima_actualizacion=now(), prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' WHERE prod_grupo1='".$_POST["producto"]."' AND prod_precio_predeterminado=0",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	
	if($_POST["campo"]=='prod_utilidad'){
		$utilidad = $_POST["valor"]/100;
		$productos = mysql_query("SELECT * FROM productos WHERE prod_grupo1='".$_POST["producto"]."' AND prod_precio_predeterminado=0",$conexion);
		while($datos = mysql_fetch_array($productos)){
			$precio1 = $datos['prod_costo'] + ($datos['prod_costo']*$utilidad);
			mysql_query("UPDATE productos SET prod_precio='".$precio1."', prod_ultima_actualizacion=now(), prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' WHERE prod_id='".$datos['prod_id']."' AND prod_precio_predeterminado=0",$conexion);
			if(mysql_errno()!=0){echo mysql_error(); exit();}
		}
		
	}
	
	mysql_query("UPDATE productos_categorias SET ".$_POST["nombreCat"]."='".$_POST["valor"]."', catp_usuario='".$_SESSION["id"]."', catp_fecha=now() WHERE catp_id='".$_POST["producto"]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
}

//PRODUCTOS DESDE LA MARCA
if($_POST["proceso"]==5){
	mysql_query("UPDATE productos SET ".$_POST["campo"]."='".$_POST["valor"]."', prod_ultima_actualizacion=now(), prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' WHERE prod_marca='".$_POST["producto"]."' AND prod_precio_predeterminado=0",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	
	if($_POST["campo"]=='prod_utilidad'){
		$utilidad = $_POST["valor"]/100;
		$productos = mysql_query("SELECT * FROM productos WHERE prod_marca='".$_POST["producto"]."' AND prod_precio_predeterminado=0",$conexion);
		while($datos = mysql_fetch_array($productos)){
			$precio1 = $datos['prod_costo'] + ($datos['prod_costo']*$utilidad);
			mysql_query("UPDATE productos SET prod_precio='".$precio1."', prod_ultima_actualizacion=now(), prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' WHERE prod_id='".$datos['prod_id']."' AND prod_precio_predeterminado=0",$conexion);
			if(mysql_errno()!=0){echo mysql_error(); exit();}
		}
		
	}
	/*
	//Actualizar los datos en la marca pagar el historial
	mysql_query("UPDATE marcas SET ".$_POST["nombreCat"]."='".$_POST["valor"]."', mar_usuario='".$_SESSION["id"]."', mar_fecha=now() WHERE mar_id='".$_POST["producto"]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	*/
}
//PRODUCTOS PREDETERMINADOS
if($_POST["proceso"]==6){
	$datos = mysql_fetch_array(mysql_query("SELECT * FROM productos WHERE prod_id='".$_POST["producto"]."'",$conexion));
	$estado = 0;
	if($datos['prod_precio_predeterminado']=='0') $estado = 1;
	
	mysql_query("UPDATE productos SET prod_precio_predeterminado='".$estado."' WHERE prod_id='".$_POST["producto"]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
}
//PRODUCTOS DE LOS COMBOS
if($_POST["proceso"]==7){
	mysql_query("UPDATE combos_productos SET ".$_POST["campo"]."='".$_POST["valor"]."' WHERE copp_id='".$_POST["producto"]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	
	//echo '<script type="text/javascript">location.reload();</script>';
}
//PRODUCTOS DE LA REMISIÓN
if($_POST["proceso"]==8){
	if($_POST["campo"]=='czpp_descuento'){
		$datosProducto = mysql_fetch_array(mysql_query("SELECT * FROM cotizacion_productos INNER JOIN productos ON prod_id=czpp_producto
		WHERE czpp_id='".$_POST["producto"]."'
		",$conexion));
		
		if($_POST["valor"]>$datosProducto['prod_descuento1']){
			echo '<script type="text/javascript">alert("El descuento que está otorgando es mayor al máximo permitido para este producto, el cual es de '.$datosProducto['prod_descuento1'].'%.");</script>';
			exit();	
		}
	}

	if($_POST["campo"]=='czpp_cantidad'){
		$datosProducto = mysql_fetch_array(mysql_query("SELECT * FROM cotizacion_productos 
		INNER JOIN productos_bodegas ON prodb_producto=czpp_producto AND prodb_bodega=czpp_bodega
		WHERE czpp_id='".$_POST["producto"]."'
		",$conexion));
		
		if($_POST["valor"]>$datosProducto['prodb_existencias']){
			echo '<script type="text/javascript">alert("Excedió el limite: Solo existen '.$datosProducto['prodb_existencias'].' unidades de este producto en esta bodega. No es posible colocar '.$_POST["valor"].'.");</script>';
			exit();	
		}

		//Restamos a las existencias
		if($_POST["valor"]>$datosProducto['czpp_cantidad']){
			$result = $_POST["valor"] - $datosProducto['czpp_cantidad'];

			mysql_query("UPDATE productos_bodegas SET prodb_existencias=(prodb_existencias - $result) WHERE prodb_id='".$datosProducto["prodb_id"]."'",$conexion);
			if(mysql_errno()!=0){echo mysql_error(); exit();}
		}else{
			$result = $datosProducto['czpp_cantidad'] - $_POST["valor"] ;

			mysql_query("UPDATE productos_bodegas SET prodb_existencias=(prodb_existencias + $result) WHERE prodb_id='".$datosProducto["prodb_id"]."'",$conexion);
			if(mysql_errno()!=0){echo mysql_error(); exit();}
		}

		
	}

	mysql_query("UPDATE cotizacion_productos SET ".$_POST["campo"]."='".mysql_real_escape_string($_POST["valor"])."' WHERE czpp_id='".$_POST["producto"]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	
	//echo '<script type="text/javascript">location.reload();</script>';
}
//PRODUCTOS DE LA IMPORTACIÓN
if($_POST["proceso"]==9){
	if($_POST["campo"]=='czpp_descuento'){
		$datosProducto = mysql_fetch_array(mysql_query("SELECT * FROM cotizacion_productos INNER JOIN productos ON prod_id=czpp_producto
		WHERE czpp_id='".$_POST["producto"]."'
		",$conexion));
		
		if($_POST["valor"]>$datosProducto['prod_descuento1']){
			echo '<script type="text/javascript">alert("El descuento que está otorgando es mayor al máximo permitido para este producto, el cual es de '.$datosProducto['prod_descuento1'].'%.");</script>';
			exit();	
		}
	}

	mysql_query("UPDATE cotizacion_productos SET ".$_POST["campo"]."='".mysql_real_escape_string($_POST["valor"])."' WHERE czpp_id='".$_POST["producto"]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	
	//echo '<script type="text/javascript">location.reload();</script>';
}
//PRODUCTOS VISIBLES WEB
if($_POST["proceso"]==10){
	$datos = mysql_fetch_array(mysql_query("SELECT * FROM productos WHERE prod_id='".$_POST["producto"]."'",$conexion));
	$estado = 0;
	if($datos['prod_visible_web']=='0') $estado = 1;
	
	mysql_query("UPDATE productos SET prod_visible_web='".$estado."' WHERE prod_id='".$_POST["producto"]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
}

//DESCUENTO DE COMBOS DE LA COTIZACIÓN
if($_POST["proceso"]==11){

	$datosProducto = mysql_fetch_array(mysql_query("SELECT * FROM cotizacion_productos 
	INNER JOIN combos ON combo_id=czpp_combo
	WHERE czpp_id='".$_POST["producto"]."'
	",$conexion));

	if($_POST["campo"]=='czpp_descuento'){
		
		if($_POST["valor"]>$datosProducto['combo_descuento_maximo']){
			echo '<script type="text/javascript">alert("El descuento que está otorgando es mayor al máximo permitido para este combo, el cual es de '.$datosProducto['combo_descuento_maximo'].'%.");</script>';
			exit();	
		}
	}


	mysql_query("UPDATE cotizacion_productos SET ".$_POST["campo"]."='".mysql_real_escape_string($_POST["valor"])."' WHERE czpp_id='".$_POST["producto"]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	
	//echo '<script type="text/javascript">location.reload();</script>';
}
?>

<div class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<i class="icon-exclamation-sign"></i><strong>Exito!</strong> Los cambios ya se guardaron y todo está bien.
</div>