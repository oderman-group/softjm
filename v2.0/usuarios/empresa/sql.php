<?php
include("sesion.php");
$configuracion = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id=1"));

//AGREGAR USUARIOS
if($_POST["idSql"]==1){
	mysqli_query($conexionBdPrincipal,"INSERT INTO usuarios(usr_login, usr_clave, usr_tipo, usr_nombre, usr_email, usr_bloqueado, usr_ciudad)VALUES('".$_POST["usuario"]."','".$_POST["clave"]."','".$_POST["tipoU"]."','".$_POST["nombre"]."','".$_POST["email"]."',0,'".$_POST["ciudad"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	$numero =(count($_POST["zona"]));
	$contador=0;
	mysqli_query($conexionBdPrincipal,"DELETE FROM zonas_usuarios WHERE zpu_usuario='".$idInsertU."'");
	
	while($contador<$numero){
		mysqli_query($conexionBdPrincipal,"INSERT INTO zonas_usuarios(zpu_usuario, zpu_zona)VALUES('".$idInsertU."',".$_POST["zona"][$contador].")");
		
		$contador++;
	}
	echo '<script type="text/javascript">window.location.href="usuarios-editar.php?id='.$idInsertU.'&msg=1";</script>';
	exit();
}
//EDITAR USUARIOS
if($_POST["idSql"]==2){
	mysqli_query($conexionBdPrincipal,"UPDATE usuarios SET usr_login='".$_POST["usuario"]."', usr_clave='".$_POST["clave"]."', usr_nombre='".$_POST["nombre"]."', usr_email='".$_POST["email"]."', usr_tipo='".$_POST["tipoU"]."', usr_ciudad='".$_POST["ciudad"]."' WHERE usr_id='".$_POST["id"]."'");
	
	$numero =(count($_POST["zona"]));
	$contador=0;
	mysqli_query($conexionBdPrincipal,"DELETE FROM zonas_usuarios WHERE zpu_usuario='".$_POST["id"]."'");
	
	while($contador<$numero){
		mysqli_query($conexionBdPrincipal,"INSERT INTO zonas_usuarios(zpu_usuario, zpu_zona)VALUES('".$_POST["id"]."',".$_POST["zona"][$contador].")");
		
		$contador++;
	}	
	echo '<script type="text/javascript">window.location.href="usuarios-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}
//AGREGAR ROLES
if($_POST["idSql"]==3){
	mysqli_query($conexionBdPrincipal,"INSERT INTO usuarios_tipos(utipo_nombre)VALUES('".$_POST["nombre"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	$numeroA =(count($_POST["accionesNP"]));
	if($numeroA==0){
		$numero =(count($_POST["paginasNP"]));
		$contador=0;
		mysqli_query($conexionBdPrincipal,"DELETE FROM paginas_perfiles WHERE pper_tipo_usuario='".$idInsertU."'");
		
		while($contador<$numero){
			mysqli_query($conexionBdPrincipal,"INSERT INTO paginas_perfiles(pper_pagina, pper_tipo_usuario)VALUES(".$_POST["paginasNP"][$contador].",'".$idInsertU."')");
			
			$contador++;
		}
	}else{
		mysqli_query($conexionBdPrincipal,"DELETE FROM paginas_perfiles WHERE pper_tipo_usuario='".$idInsertU."'");
		
		$contador=0;
		while($contador<$numeroA){
			$paginas = mysqli_query($conexionBdPrincipal,"SELECT * FROM paginas WHERE pag_tipo_crud='".$_POST["accionesNP"][$contador]."'");
			while($pag=mysqli_fetch_array($paginas)){
				mysqli_query($conexionBdPrincipal,"INSERT INTO paginas_perfiles(pper_pagina, pper_tipo_usuario)VALUES(".$pag[0].",'".$idInsertU."')");
				
			}
			$contador++;
		}
	}
	echo '<script type="text/javascript">window.location.href="roles-editar.php?id='.$idInsertU.'&msg=1";</script>';
	exit();
}
//EDITAR ROLES
if($_POST["idSql"]==4){
	mysqli_query($conexionBdPrincipal,"UPDATE usuarios_tipos SET utipo_nombre='".$_POST["nombre"]."' WHERE utipo_id='".$_POST["id"]."'");
	
	$numeroA =(count($_POST["accionesNP"]));
	if($numeroA==0){
		$numero =(count($_POST["paginasNP"]));
		$contador=0;
		mysqli_query($conexionBdPrincipal,"DELETE FROM paginas_perfiles WHERE pper_tipo_usuario='".$_POST["id"]."'");
		
		while($contador<$numero){
			mysqli_query($conexionBdPrincipal,"INSERT INTO paginas_perfiles(pper_pagina, pper_tipo_usuario)VALUES(".$_POST["paginasNP"][$contador].",'".$_POST["id"]."')");
			
			$contador++;
		}
	}else{
		mysqli_query($conexionBdPrincipal,"DELETE FROM paginas_perfiles WHERE pper_tipo_usuario='".$_POST["id"]."'");
		
		$contador=0;
		while($contador<$numeroA){
			$paginas = mysqli_query($conexionBdPrincipal,"SELECT * FROM paginas WHERE pag_tipo_crud='".$_POST["accionesNP"][$contador]."'");
			while($pag=mysqli_fetch_array($paginas)){
				mysqli_query($conexionBdPrincipal,"INSERT INTO paginas_perfiles(pper_pagina, pper_tipo_usuario)VALUES(".$pag[0].",'".$_POST["id"]."')");
				
			}
			$contador++;
		}
	}
	echo '<script type="text/javascript">window.location.href="roles-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}
//AGREGAR CLIENTES
if($_POST["idSql"]==5){
	if($_POST["fechaIngreso"]=="") $_POST["fechaIngreso"]='0000-00-00';
	$zona = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM ".BDADMIN.".localidad_ciudades WHERE ciu_id='".$_POST["ciudad"]."'"));
	$clienteV = mysqli_num_rows(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_usuario='".trim($_POST["usuario"])."'"));
	if($clienteV>0){
		echo "<div style='font-family:arial; text-align:center'>Ya existe un cliente con este n&uacute;mero de NIT. Verifique para que no lo registre nuevamente.<br><br>
		<a href='javascript:history.go(-1);'>[P&aacute;gina anterior]</a></span> | <a href='clientes.php'>[Ir a clientes]</a></div>";
		exit();
	}
	//$direccion = $_POST["op1"]." ".$_POST["op2"]." ".$_POST["op3"]." # ".$_POST["op4"]." ".$_POST["op5"]." - ".$_POST["op6"]." - ".$_POST["op7"];
	mysqli_query($conexionBdPrincipal,"INSERT INTO clientes(cli_nombre, cli_referencia, cli_categoria, cli_email, cli_telefono, cli_ciudad, cli_usuario, cli_clave, cli_direccion, cli_zona, cli_fecha_registro, cli_fecha_ingreso, cli_celular, cli_telefonos, cli_sigla)VALUES('".$_POST["nombre"]."','".$_POST["referencia"]."','".$_POST["categoria"]."','".$_POST["email"]."','".$_POST["telefono"]."','".$_POST["ciudad"]."','".trim($_POST["usuario"])."','".$_POST["clave"]."','".strtoupper($_POST["direccion"])."','".$zona[2]."',now(),'".$_POST["fechaIngreso"]."','".$_POST["celular"]."','".$_POST["telefonos"]."','".$_POST["sigla"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	$numero =(count($_POST["grupos"]));
	$contador=0;
	mysqli_query($conexionBdPrincipal,"DELETE FROM clientes_categorias WHERE cpcat_cliente='".$idInsertU."'");
	
	while($contador<$numero){
		mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_categorias(cpcat_cliente, cpcat_categoria)VALUES('".$idInsertU."',".$_POST["grupos"][$contador].")");
		
		$contador++;
	}
	
	mysqli_query($conexionBdPrincipal,"INSERT INTO sucursales(sucu_cliente_principal, sucu_ciudad, sucu_direccion, sucu_telefono, sucu_celular, sucu_telefonos, sucu_nombre)VALUES('".$idInsertU."', '".$_POST["ciudad"]."', '".$_POST["direccion"]."', '".$_POST["telefono"]."', '".$_POST["celular"]."', '".$_POST["telefonos"]."','Sede principal')");
	
	if($_POST["contactoP"]==1){
		mysqli_query($conexionBdPrincipal,"INSERT INTO contactos(cont_nombre, cont_telefono, cont_email, cont_cliente_principal, cont_celular, cont_telefonos)VALUES('".$_POST["nombre"]."', '".$_POST["telefono"]."', '".$_POST["email"]."', '".$idInsertU."', '".$_POST["celular"]."','".$_POST["telefonos"]."')");
	}
	
	echo '<script type="text/javascript">window.location.href="clientes-editar.php?id='.$idInsertU.'&msg=1";</script>';
	exit();
}
//EDITAR CLIENTES
if($_POST["idSql"]==6){
	if($_POST["fechaIngreso"]=="") $_POST["fechaIngreso"]='0000-00-00'; if($_POST["retiroFecha"]=="") $_POST["retiroFecha"]='0000-00-00';
	$zona = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM ".BDADMIN.".localidad_ciudades WHERE ciu_id='".$_POST["ciudad"]."'"));
	$clienteV = mysqli_num_rows(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_usuario='".trim($_POST["usuario"])."' AND cli_id!='".$_POST["id"]."'"));
	if($clienteV>0){
		echo "<div style='font-family:arial; text-align:center'>Ya existe un cliente con este n&uacute;mero de NIT. Verifique para que no lo registre nuevamente.<br><br>
		<a href='javascript:history.go(-1);'>[P&aacute;gina anterior]</a></span> | <a href='clientes.php'>[Ir a clientes]</a></div>";
		exit();
	}
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_nombre='".$_POST["nombre"]."', cli_referencia='".$_POST["referencia"]."', cli_categoria='".$_POST["categoria"]."', cli_email='".$_POST["email"]."', cli_telefono='".$_POST["telefono"]."', cli_ciudad='".$_POST["ciudad"]."', cli_usuario='".trim($_POST["usuario"])."', cli_clave='".$_POST["clave"]."', cli_direccion='".$_POST["direccion"]."', cli_zona='".$zona[2]."', cli_fecha_ingreso='".$_POST["fechaIngreso"]."', cli_celular='".$_POST["celular"]."', cli_telefonos='".$_POST["telefonos"]."', cli_sigla='".$_POST["sigla"]."' WHERE cli_id='".$_POST["id"]."'");
	
	$numero =(count($_POST["grupos"]));
	$contador=0;
	mysqli_query($conexionBdPrincipal,"DELETE FROM clientes_categorias WHERE cpcat_cliente='".$_POST["id"]."'");
	
	while($contador<$numero){
		mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_categorias(cpcat_cliente, cpcat_categoria)VALUES('".$_POST["id"]."',".$_POST["grupos"][$contador].")");
		
		$contador++;
	}
	echo '<script type="text/javascript">window.location.href="clientes-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}
//AGREGAR SEGUIMIENTO CLIENTES
if($_POST["idSql"]==7){
	/*if($_POST["idTK"]==""){
		mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_tikets(tik_asunto_principal, tik_tipo_tiket, tik_fecha_creacion, tik_usuario_responsable, tik_estado, tik_cliente, tik_prioridad, tik_observaciones, tik_canal)VALUES('TIKET AUTOMÁTICO','".$_POST["tipoS"]."','".$_POST["fechaContacto"]."','".$_SESSION["id"]."',2,'".$_POST["cliente"]."',1,'".$_POST["observaciones"]."','".$_POST["canal"]."')");
		
		$tiketID = mysqli_insert_id($conexionBdPrincipal);
	}else{
		$tiketID = $_POST["idTK"];
	}*/
	
	if($_POST["fechaPC"]=="") $_POST["fechaPC"] = '0000-00-00'; if($_POST["encargado"]=="") $_POST["encargado"] = 0;
	
	mysqli_query($conexionBdPrincipal,"INSERT INTO cliente_seguimiento(cseg_cliente, cseg_fecha_reporte, cseg_observacion, cseg_usuario_responsable, cseg_fecha_proximo_contacto, cseg_usuario_encargado, cseg_fecha_contacto, cseg_contacto, cseg_tiket, cseg_canal)VALUES('".$_POST["cliente"]."',now(),'".$_POST["observaciones"]."','".$_SESSION["id"]."','".$_POST["fechaPC"]."','".$_POST["encargado"]."',now(),'".$_POST["contacto"]."','".$_POST["IDticket"]."','".$_POST["canal"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	
	if($_POST["cerrarTK"]==1){
		mysqli_query($conexionBdPrincipal,"UPDATE clientes_tikets SET tik_estado=2 WHERE tik_id='".$_POST["IDticket"]."'");
		
	}
	
	if($_POST["notf"]==1){
		mysqli_query($conexionBdPrincipal,"INSERT INTO notificaciones(not_asunto, not_cliente, not_usuario, not_visto, not_estado, not_seguimiento, not_fecha)VALUES('".$_POST["asunto"]."', '".$_POST["cliente"]."', '".$_POST["encargado"]."', 0, 1, '".$idInsertU."', now())");
		
		
		$cliente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_POST["cliente"]."'"));
		$contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='".$_POST["encargado"]."'"));
		$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
		$fin .= '
					<center>
						<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
						<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
							
							<p style="color:'.$configuracion["conf_color_letra"].';">'.strtoupper($contacto['usr_nombre']).',<br>
							Te han encargado un nuevo seguimiento para uno de los clientes.<br>
							<b>ALGUNOS DETALLES</b><br>
							Asunto: '.$_POST["asunto"].'<br>
							Cliente: '.$cliente['cli_nombre'].'<br>
							Para revisar este pendiente ingresa al CRM con tus datos de acceso, mediante el siguiente link.</p>
							
							<p align="center"><a href="http://softjm.com/index.php?idseg='.$idInsertU.'" target="_blank" style="color:'.$configuracion["conf_color_link"].';">IR AL SEGUIMIENTO</a></p>
							
							<p align="center" style="color:'.$configuracion["conf_color_letra"].';">
								<img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="80"><br>
								'.$configuracion["conf_mensaje_pie"].'<br>
								<a href="'.$configuracion["conf_web"].'" style="color:'.$configuracion["conf_color_link"].';">'.$configuracion["conf_web"].'</a>
							</p>
							
						</div>
					</center>
					<p>&nbsp;</p>
				';	
		$fin .='';						
		$fin .=  '<html><body>';							
		$sfrom=$configuracion['conf_email']; //LA CUETA DEL QUE ENVIA EL MENSAJE			
		$sdestinatario=$contacto['usr_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
		$ssubject="CRM - Seguimiento a clientes"; //ASUNTO DEL MENSAJE 				
		$shtml=$fin; //MENSAJE EN SI			
		$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
		$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
		$sheader=$sheader."Mime-Version: 1.0\n"; 		
		$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
		@mail($sdestinatario,$ssubject,$shtml,$sheader);
	}
	if($_POST["notfCliente"]==1){
		$cliente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_POST["cliente"]."'"));
		$contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='".$_POST["encargado"]."'"));
		$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
		$fin .= '
					<center>
						<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
						<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
							
							<p style="color:'.$configuracion["conf_color_letra"].';">'.strtoupper($cliente['cli_nombre']).',<br>
							Le informamos que se está haciendo un seguimiento.<br>
							<b>ALGUNOS DETALLES</b><br>
							Asunto: '.$_POST["asunto"].'<br>
							Fecha próximo contacto: '.$_POST["fechaPC"].'<br>
							Encargado próximo contacto: '.$contacto['usr_nombre'].'<br>
							</p>
							
							<p align="center" style="color:'.$configuracion["conf_color_letra"].';">
								<img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="80"><br>
								'.$configuracion["conf_mensaje_pie"].'<br>
								<a href="'.$configuracion["conf_web"].'" style="color:'.$configuracion["conf_color_link"].';">'.$configuracion["conf_web"].'</a>
							</p>
							
						</div>
					</center>
					<p>&nbsp;</p>
				';	
		$fin .='';						
		$fin .=  '<html><body>';							
		$sfrom=$configuracion['conf_email']; //LA CUETA DEL QUE ENVIA EL MENSAJE			
		$sdestinatario=$cliente['cli_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
		$ssubject="CRM - Seguimiento a clientes"; //ASUNTO DEL MENSAJE 				
		$shtml=$fin; //MENSAJE EN SI			
		$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
		$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
		$sheader=$sheader."Mime-Version: 1.0\n"; 		
		$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
		@mail($sdestinatario,$ssubject,$shtml,$sheader);
	}
	echo '<script type="text/javascript">window.location.href="tickets-detalles.php?idTK='.$_POST["IDticket"].'";</script>';
	exit();
}
//EDITAR SEGUIMIENTO CLIENTES
if($_POST["idSql"]==8){
	mysqli_query($conexionBdPrincipal,"UPDATE cliente_seguimiento SET cseg_cliente='".$_POST["cliente"]."', cseg_observacion='".$_POST["observaciones"]."', cseg_fecha_proximo_contacto='".$_POST["fechaPC"]."', cseg_asunto='".$_POST["asunto"]."', cseg_usuario_encargado='".$_POST["encargado"]."', cseg_cotizacion='".$_POST["cotizacion"]."', cseg_fecha_contacto='".$_POST["fechaContacto"]."', cseg_tipo='".$_POST["tipoS"]."', cseg_contacto='".$_POST["contacto"]."', cseg_canal='".$_POST["canal"]."' WHERE cseg_id='".$_POST["id"]."'");
	
	if($_POST["notf"]==1){
		mysqli_query($conexionBdPrincipal,"INSERT INTO notificaciones(not_asunto, not_cliente, not_usuario, not_visto, not_estado, not_seguimiento, not_fecha)VALUES('".$_POST["asunto"]."', '".$_POST["cliente"]."', '".$_POST["encargado"]."', 0, 1, '".$_POST["id"]."', now())");
		
		$cliente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_POST["cliente"]."'"));
		$contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='".$_POST["encargado"]."'"));
		$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
		$fin .= '
					<center>
						<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
						<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
							
							<p style="color:'.$configuracion["conf_color_letra"].';">'.strtoupper($contacto['usr_nombre']).',<br>
							Te han encargado un nuevo seguimiento para uno de los clientes.<br>
							<b>ALGUNOS DETALLES</b><br>
							Asunto: '.$_POST["asunto"].'<br>
							Cliente: '.$cliente['cli_nombre'].'<br>
							Para revisar este pendiente ingresa al CRM con tus datos de acceso, mediante el siguiente link.</p>
							
							<p align="center"><a href="http://softjm.com/index.php?idseg='.$_POST["id"].'" target="_blank" style="color:'.$configuracion["conf_color_link"].';">IR AL SEGUIMIENTO</a></p>
							
							<p align="center" style="color:'.$configuracion["conf_color_letra"].';">
								<img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="80"><br>
								'.$configuracion["conf_mensaje_pie"].'<br>
								<a href="'.$configuracion["conf_web"].'" style="color:'.$configuracion["conf_color_link"].';">'.$configuracion["conf_web"].'</a>
							</p>
							
						</div>
					</center>
					<p>&nbsp;</p>
				';	
		$fin .='';						
		$fin .=  '<html><body>';							
		$sfrom=$configuracion['conf_email']; //LA CUETA DEL QUE ENVIA EL MENSAJE			
		$sdestinatario=$cliente['cli_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
		$ssubject="CRM - Seguimiento a clientes"; //ASUNTO DEL MENSAJE 				
		$shtml=$fin; //MENSAJE EN SI			
		$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
		$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
		$sheader=$sheader."Mime-Version: 1.0\n"; 		
		$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
		@mail($sdestinatario,$ssubject,$shtml,$sheader);
	}
	if($_POST["notfCliente"]==1){
		$cliente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_POST["cliente"]."'"));
		$contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='".$_POST["encargado"]."'"));
		$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
		$fin .= '
					<center>
						<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
						<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
							
							<p style="color:'.$configuracion["conf_color_letra"].';">'.strtoupper($cliente['cli_nombre']).',<br>
							Le informamos que se está haciendo un seguimiento.<br>
							<b>ALGUNOS DETALLES</b><br>
							Asunto: '.$_POST["asunto"].'<br>
							Fecha próximo contacto: '.$_POST["fechaPC"].'<br>
							Encargado próximo contacto: '.$contacto['usr_nombre'].'<br>
							</p>
							
							<p align="center" style="color:'.$configuracion["conf_color_letra"].';">
								<img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="80"><br>
								'.$configuracion["conf_mensaje_pie"].'<br>
								<a href="'.$configuracion["conf_web"].'" style="color:'.$configuracion["conf_color_link"].';">'.$configuracion["conf_web"].'</a>
							</p>
							
						</div>
					</center>
					<p>&nbsp;</p>
				';	
		$fin .='';						
		$fin .=  '<html><body>';							
		$sfrom=$configuracion['conf_email']; //LA CUETA DEL QUE ENVIA EL MENSAJE			
		$sdestinatario=$contacto['usr_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
		$ssubject="CRM - Seguimiento a clientes"; //ASUNTO DEL MENSAJE 				
		$shtml=$fin; //MENSAJE EN SI			
		$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
		$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
		$sheader=$sheader."Mime-Version: 1.0\n"; 		
		$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
		@mail($sdestinatario,$ssubject,$shtml,$sheader);
	}
	echo '<script type="text/javascript">window.location.href="clientes-seguimiento-editar.php?id='.$_POST["id"].'&msg=2&idTK='.$_POST["idTK"].'";</script>';
	exit();
}
//AGREGAR AUDITORES
if($_POST["idSql"]==9){
	mysqli_query($conexionBdPrincipal,"INSERT INTO auditores(aud_nombre, aud_ciudad, aud_tipo_auditor)VALUES('".$_POST["nombre"]."','".$_POST["ciudad"]."','".$_POST["tipoAuditor"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="auditores-editar.php?id='.$idInsertU.'&msg=1";</script>';
	exit();
}
//EDITAR AUDITORES
if($_POST["idSql"]==10){
	mysqli_query($conexionBdPrincipal,"UPDATE auditores SET aud_nombre='".$_POST["nombre"]."', aud_ciudad='".$_POST["ciudad"]."', aud_tipo_auditor='".$_POST["tipoAuditor"]."' WHERE aud_id='".$_POST["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="auditores-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}
//AGREGAR FACTURAS
if($_POST["idSql"]==11){
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_categoria=2, cli_fecha_ingreso=now() WHERE cli_id='".$_POST["cliente"]."' AND cli_categoria=1");
	
	if($_POST["valor"]=="") $_POST["valor"]=0; if($_POST["descuento"]=="") $_POST["descuento"]=0; if($_POST["impuestos"]=="") $_POST["impuestos"]=0; if($_POST["retencion"]=="") $_POST["retencion"]=0;
	mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion(fact_cliente, fact_fecha, fact_valor, fact_estado, fact_usuario_responsable, fact_descripcion, fact_observacion, fact_descuento, fact_numero_fisica, fact_usuario_influyente, fact_fecha_real, fact_fecha_vencimiento, fact_tipo, fact_impuestos, fact_retencion)VALUES('".$_POST["cliente"]."',now(),'".$_POST["valor"]."','".$_POST["estado"]."','".$_SESSION["id"]."','".$_POST["descripcion"]."','".$_POST["observacion"]."','".$_POST["descuento"]."','".$_POST["numFisica"]."','".$_POST["influyente"]."','".$_POST["fechaFactura"]."','".$_POST["fechaVencimiento"]."','".$_POST["tipo"]."','".$_POST["impuestos"]."','".$_POST["retencion"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	$numero =(count($_POST["producto"]));
	$contador=0;
	mysqli_query($conexionBdPrincipal,"DELETE FROM facturacion_productos WHERE fpp_factura='".$idInsertU."'");
	
	while($contador<$numero){
		mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion_productos(fpp_factura, fpp_producto)VALUES('".$idInsertU."',".$_POST["producto"][$contador].")");
		
		$contador++;
	}
	if($_POST["estado"]==1){
		if($_FILES['archivo']['name']!=""){
			$archivo = $_FILES['archivo']['name'];
			$destino = "files/comprobantes";
			move_uploaded_file($_FILES['archivo']['tmp_name'], $destino ."/".$archivo);
		}
		mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion_abonos(fpab_factura, fpab_fecha_abono, fpab_valor, fpab_fecha_registro, fpab_observaciones, fpab_medio_pago, fpab_responsable_registro, fpab_comprobante)VALUES('".$idInsertU."',now(),'".$_POST["valor"]."',now(),'".$_POST["observacion"]."','".$_POST["medio"]."','".$_SESSION["id"]."','".$archivo."')");
		
	}
	echo '<script type="text/javascript">window.location.href="facturacion-editar.php?id='.$idInsertU.'&msg=1";</script>';
	exit();
}
//EDITAR FACTURAS
if($_POST["idSql"]==12){
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_categoria=2, cli_fecha_ingreso=now() WHERE cli_id='".$_POST["cliente"]."' AND cli_categoria=1");
	
	if($_POST["valor"]=="") $_POST["valor"]=0; if($_POST["descuento"]=="") $_POST["descuento"]=0; if($_POST["impuestos"]=="") $_POST["impuestos"]=0; if($_POST["retencion"]=="") $_POST["retencion"]=0;
	mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_cliente='".$_POST["cliente"]."', fact_valor='".$_POST["valor"]."', fact_descripcion='".$_POST["descripcion"]."', fact_estado='".$_POST["estado"]."', fact_observacion='".$_POST["observacion"]."', fact_ultima_modificacion=now(), fact_usuario_modificacion='".$_SESSION["id"]."', fact_descuento='".$_POST["descuento"]."', fact_numero_fisica='".$_POST["numFisica"]."', fact_usuario_influyente='".$_POST["influyente"]."', fact_fecha_real='".$_POST["fechaFactura"]."', fact_fecha_vencimiento='".$_POST["fechaVencimiento"]."', fact_tipo='".$_POST["tipo"]."', fact_impuestos='".$_POST["impuestos"]."', fact_retencion='".$_POST["retencion"]."' WHERE fact_id='".$_POST["id"]."'");
	
	$numero =(count($_POST["producto"]));
	$contador=0;
	mysqli_query($conexionBdPrincipal,"DELETE FROM facturacion_productos WHERE fpp_factura='".$_POST["id"]."'");
	
	while($contador<$numero){
		mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion_productos(fpp_factura, fpp_producto)VALUES('".$_POST["id"]."',".$_POST["producto"][$contador].")");
		
		$contador++;
	}
	echo '<script type="text/javascript">window.location.href="facturacion-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}
//AGREGAR DOCUMENTOS
if($_POST["idSql"]==13){
	if($_FILES['archivo']['name']!=""){
		$archivo = $_FILES['archivo']['name'];
		$destino = "files/documentos";
		move_uploaded_file($_FILES['archivo']['tmp_name'], $destino ."/".$archivo);
	}
	mysqli_query($conexionBdPrincipal,"INSERT INTO documentos(doc_nombre, doc_documento, doc_cliente)VALUES('".$_POST["nombre"]."','".$archivo."','".$_POST["cliente"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="documentos-editar.php?id='.$idInsertU.'&msg=1&cte='.$_POST["cte"].'";</script>';
	exit();
}
//EDITAR DOCUMENTOS
if($_POST["idSql"]==14){
	if($_FILES['archivo']['name']!=""){
		$archivo = $_FILES['archivo']['name'];
		$destino = "files/documentos";
		move_uploaded_file($_FILES['archivo']['tmp_name'], $destino ."/".$archivo);
		mysqli_query($conexionBdPrincipal,"UPDATE documentos SET doc_documento='".$archivo."' WHERE doc_id='".$_POST["id"]."'");
	}
	mysqli_query($conexionBdPrincipal,"UPDATE documentos SET doc_nombre='".$_POST["nombre"]."', doc_cliente='".$_POST["cliente"]."' WHERE doc_id='".$_POST["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="documentos-editar.php?id='.$_POST["id"].'&msg=2&cte='.$_POST["cte"].'";</script>';
	exit();
}
//AGREGAR MOMENTOS
if($_POST["idSql"]==15){
	mysqli_query($conexionBdPrincipal,"INSERT INTO momentos(mom_cliente, mom_nombre, mom_fecha_creacion)VALUES('".$_POST["cte"]."','".$_POST["nombre"]."',now())");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="clientes-momentos-editar.php?id='.$idInsertU.'&msg=1&cte='.$_POST["cte"].'";</script>';
	exit();
}
//EDITAR MOMENTOS
if($_POST["idSql"]==16){
	mysqli_query($conexionBdPrincipal,"UPDATE momentos SET mom_nombre='".$_POST["nombre"]."' WHERE mom_id='".$_POST["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="clientes-momentos-editar.php?id='.$_POST["id"].'&msg=2&cte='.$_POST["cte"].'";</script>';
	exit();
}
//AGREGAR DEALER/GRUPOS
if($_POST["idSql"]==17){
	mysqli_query($conexionBdPrincipal,"INSERT INTO dealer(deal_nombre)VALUES('".$_POST["nombre"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	$numero =(count($_POST["clientes"]));
	$contador=0;
	mysqli_query($conexionBdPrincipal,"DELETE FROM clientes_categorias WHERE cpcat_categoria='".$idInsertU."'");
	
	while($contador<$numero){
		mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_categorias(cpcat_cliente, cpcat_categoria)VALUES(".$_POST["clientes"][$contador].",'".$idInsertU."')");
		
		$contador++;
	}
	echo '<script type="text/javascript">window.location.href="dealer-editar.php?id='.$idInsertU.'&msg=1";</script>';
	exit();
}
//EDITAR DEALER/GRUPOS
if($_POST["idSql"]==18){
	mysqli_query($conexionBdPrincipal,"UPDATE dealer SET deal_nombre='".$_POST["nombre"]."' WHERE deal_id='".$_POST["id"]."'");
	
	$numero =(count($_POST["clientes"]));
	$contador=0;
	mysqli_query($conexionBdPrincipal,"DELETE FROM clientes_categorias WHERE cpcat_categoria='".$_POST["id"]."'");
	
	while($contador<$numero){
		mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_categorias(cpcat_cliente, cpcat_categoria)VALUES(".$_POST["clientes"][$contador].",'".$_POST["id"]."')");
		
		$contador++;
	}
	echo '<script type="text/javascript">window.location.href="dealer-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}
//AGREGAR PRODUCTOS
if($_POST["idSql"]==19){
	mysqli_query($conexionBdPrincipal,"INSERT INTO productos(prod_nombre, prod_categoria)VALUES('".$_POST["nombre"]."','".$_POST["categoria"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="productos-editar.php?id='.$idInsertU.'&msg=1";</script>';
	exit();
}
//EDITAR PRODUCTOS
if($_POST["idSql"]==20){
	mysqli_query($conexionBdPrincipal,"UPDATE productos SET prod_nombre='".$_POST["nombre"]."', prod_categoria='".$_POST["categoria"]."' WHERE prod_id='".$_POST["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="productos-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}
//AGREGAR CATEGORIA PRODUCTOS
if($_POST["idSql"]==21){
	mysqli_query($conexionBdPrincipal,"INSERT INTO productos_categorias(catp_nombre)VALUES('".$_POST["nombre"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="categoriasp-editar.php?id='.$idInsertU.'&msg=1";</script>';
	exit();
}
//EDITAR CATEGORIA PRODUCTOS
if($_POST["idSql"]==22){
	mysqli_query($conexionBdPrincipal,"UPDATE productos_categorias SET catp_nombre='".$_POST["nombre"]."' WHERE catp_id='".$_POST["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="categoriasp-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}
//EDITAR CONFIGURACIÓN
if($_POST["idSql"]==23){
	if($_FILES['logo']['name']!=""){
		$archivo = $_FILES['logo']['name'];
		$destino = "files";
		move_uploaded_file($_FILES['logo']['tmp_name'], $destino ."/".$archivo);
		mysqli_query($conexionBdPrincipal,"UPDATE configuracion SET conf_logo='".$archivo."' WHERE conf_id=1");
	}
	mysqli_query($conexionBdPrincipal,"UPDATE configuracion SET conf_meta_venta='".$_POST["metaVenta"]."', conf_empresa='".$_POST["nombre"]."', conf_email='".$_POST["email"]."', conf_web='".$_POST["web"]."', conf_url_encuestas='".$_POST["urlEncuestas"]."', conf_nit='".$_POST["nit"]."', conf_telefono='".$_POST["telefono"]."', conf_fondo_boletin='".$_POST["fondoBoletin"]."', conf_fondo_mensaje='".$_POST["fondoMensaje"]."', conf_color_letra='".$_POST["colorLetra"]."', conf_color_link='".$_POST["colorLink"]."', conf_mensaje_pie='".$_POST["mensajePie"]."', conf_nombre_boton='".$_POST["botonNombre"]."', conf_url_boton='".$_POST["botonUrl"]."', conf_paginacion='".$_POST["paginacion"]."', conf_agno_inicio='".$_POST["agnoInicio"]."', conf_ancho_logo='".$_POST["anchoLogo"]."', conf_alto_logo='".$_POST["altoLogo"]."' WHERE conf_id=1");
	
	echo '<script type="text/javascript">window.location.href="configuracion.php?msg=2";</script>';
	exit();
}
//AGREGAR CONTACTOS
if($_POST["idSql"]==24){
	mysqli_query($conexionBdPrincipal,"INSERT INTO contactos(cont_nombre, cont_telefono, cont_email, cont_area, cont_cargo, cont_cliente_principal, cont_celular, cont_telefonos, cont_sucursal)VALUES('".$_POST["nombre"]."','".$_POST["telefono"]."','".$_POST["email"]."','".$_POST["area"]."','".$_POST["cargo"]."','".$_POST["cliente"]."','".$_POST["celular"]."','".$_POST["telefonos"]."','".$_POST["sucursal"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="clientes-contactos-editar.php?id='.$idInsertU.'&msg=1&cte='.$_POST["cte"].'";</script>';
	exit();
}
//EDITAR CONTACTOS
if($_POST["idSql"]==25){
	mysqli_query($conexionBdPrincipal,"UPDATE contactos SET cont_nombre='".$_POST["nombre"]."', cont_telefono='".$_POST["telefono"]."', cont_email='".$_POST["email"]."', cont_area='".$_POST["area"]."', cont_cargo='".$_POST["cargo"]."', cont_cliente_principal='".$_POST["cliente"]."', cont_celular='".$_POST["celular"]."', cont_telefonos='".$_POST["telefonos"]."', cont_sucursal='".$_POST["sucursal"]."' WHERE cont_id='".$_POST["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="clientes-contactos-editar.php?id='.$_POST["id"].'&msg=2&cte='.$_POST["cte"].'";</script>';
	exit();
}
//AGREGAR ZONAS
if($_POST["idSql"]==26){
	mysqli_query($conexionBdPrincipal,"INSERT INTO zonas(zon_nombre, zon_observaciones)VALUES('".$_POST["nombre"]."','".$_POST["observaciones"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="zonas-editar.php?id='.$idInsertU.'&msg=1";</script>';
	exit();
}
//EDITAR ZONAS
if($_POST["idSql"]==27){
	mysqli_query($conexionBdPrincipal,"UPDATE zonas SET zon_nombre='".$_POST["nombre"]."', zon_observaciones='".$_POST["observaciones"]."' WHERE zon_id='".$_POST["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="zonas-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}
//AGREGAR ENCUESTAS
if($_POST["idSql"]==28){
	mysqli_query($conexionBdPrincipal,"INSERT INTO encuesta_satisfaccion(encs_fecha, encs_cliente, encs_atendido, encs_producto, encs_contacto)VALUES(now(),'".$_POST["cliente"]."','".$_POST["usuario"]."','".$_POST["producto"]."','".$_POST["contacto"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	
	$contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_id='".$_POST["contacto"]."'"));
	$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
	$fin .= '
				<center>
					<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
					<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
						
						<p style="color:'.$configuracion["conf_color_letra"].';">'.strtoupper($contacto['cont_nombre']).',<br>
						Agradecemos se tome 3 minutos para responder una escuesta sobre la atención brindada por nuestra empresa.<br>
						Haga click en el siguiente enlace para responder la encuesta.</p>
						
						<p align="center"><a href="'.$configuracion["conf_url_encuestas"].'/formato-encuesta.php?id='.$idInsertU.'" target="_blank" style="color:'.$configuracion["conf_color_link"].';">RESPONDER ENCUESTA</a></p>
						
						<p align="center" style="color:'.$configuracion["conf_color_letra"].';">
							<img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="80"><br>
							'.$configuracion["conf_mensaje_pie"].'<br>
							<a href="'.$configuracion["conf_web"].'" style="color:'.$configuracion["conf_color_link"].';">'.$configuracion["conf_web"].'</a>
						</p>
						
					</div>
				</center>
				<p>&nbsp;</p>
			';	
	$fin .='';						
	$fin .=  '<html><body>';							
	$sfrom=$configuracion['conf_email']; //LA CUETA DEL QUE ENVIA EL MENSAJE			
	$sdestinatario=$contacto['cont_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
	$ssubject="Encuesta de satisfaccion"; //ASUNTO DEL MENSAJE 				
	$shtml=$fin; //MENSAJE EN SI			
	$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
	$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
	$sheader=$sheader."Mime-Version: 1.0\n"; 		
	$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
	@mail($sdestinatario,$ssubject,$shtml,$sheader);
	echo '<script type="text/javascript">window.open("../formato-encuesta.php?id='.$idInsertU.'");</script>';
	echo '<script type="text/javascript">window.location.href="encuesta-editar.php?id='.$idInsertU.'&msg=1";</script>';
	exit();
}
//EDITAR ENCUESTAS
if($_POST["idSql"]==29){
	mysqli_query($conexionBdPrincipal,"UPDATE encuesta_satisfaccion SET encs_cliente='".$_POST["cliente"]."', encs_atendido='".$_POST["usuario"]."', encs_producto='".$_POST["producto"]."' WHERE encs_id='".$_POST["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="encuesta-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}
//AGREGAR MATERIALES A PRODUCTOS
if($_POST["idSql"]==30){
	$numero =(count($_FILES['documento']['name']));
	if($numero>0 and $_FILES['documento']['name'][0]!=""){
		$contador=0;
		while($contador<$numero){
			$archivo = $_FILES['documento']['name'][$contador];
			$destino = "files/materiales";
			move_uploaded_file($_FILES['documento']['tmp_name'][$contador], $destino ."/".$archivo);
			$material = $archivo;
			mysqli_query($conexionBdPrincipal,"INSERT INTO productos_materiales(ppmt_material, ppmt_tipo, ppmt_activo, ppmt_producto, ppmt_nombre)VALUES('".$material."','".$_POST["tipo"]."','".$_POST["activo"]."','".$_POST["pdto"]."','".$_POST["nombre"]."')");
			
			$contador++;
		}
	}else{
		$material = $_POST["video"];
		mysqli_query($conexionBdPrincipal,"INSERT INTO productos_materiales(ppmt_material, ppmt_tipo, ppmt_activo, ppmt_producto, ppmt_nombre)VALUES('".$material."','".$_POST["tipo"]."','".$_POST["activo"]."','".$_POST["pdto"]."','".$_POST["nombre"]."')");
		
		$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	}
	echo '<script type="text/javascript">window.location.href="productos-materiales.php?msg=1&pdto='.$_POST["pdto"].'";</script>';
	exit();
}
//EDITAR MATERIALES A PRODUCTOS
if($_POST["idSql"]==31){
	$numero =(count($_FILES['documento']['name']));
	if($numero>0 and $_FILES['documento']['name'][0]!=""){
		$contador=0;
		while($contador<$numero){
			$archivo = $_FILES['documento']['name'][$contador];
			$destino = "files/materiales";
			move_uploaded_file($_FILES['documento']['tmp_name'][$contador], $destino ."/".$archivo);
			$material = $archivo;
			mysqli_query($conexionBdPrincipal,"INSERT INTO productos_materiales(ppmt_material, ppmt_tipo, ppmt_activo, ppmt_producto, ppmt_nombre)VALUES('".$material."','".$_POST["tipo"]."','".$_POST["activo"]."','".$_POST["pdto"]."','".$_POST["nombre"]."')");
			
			$contador++;
		}
	}else{
		$material = $_POST["video"];
		if($_POST["tipo"]==2){
			mysqli_query($conexionBdPrincipal,"UPDATE productos_materiales SET ppmt_material='".$material."' WHERE ppmt_id='".$_POST["id"]."'");
		}
	}
	mysqli_query($conexionBdPrincipal,"UPDATE productos_materiales SET ppmt_tipo='".$_POST["tipo"]."', ppmt_activo='".$_POST["activo"]."', ppmt_nombre='".$_POST["nombre"]."' WHERE ppmt_id='".$_POST["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="productos-materiales.php?msg=2&pdto='.$_POST["pdto"].'";</script>';
	exit();
}
//ENVIAR BOLETIN  DE MENSAJES
if($_POST["idSql"]==32){
	$numero =(count($_POST["zonas"]));
	if($numero>0){
		$contador=0;
		while($contador<$numero){
			$clientes = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes
			INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_departamento='".$_POST["zonas"][$contador]."' AND ciu_id=cli_ciudad");
			
			while($ctes = mysqli_fetch_array($clientes)){
				if($ctes['cli_email']!="" and !is_null($ctes['cli_email']))$emails .=$ctes['cli_email'].",";
			}
			$contador++;
		}
	}
	
	$numero =(count($_POST["tipos"]));
	if($numero>0){
		$contador=0;
		while($contador<$numero){
			$clientes = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_categoria='".$_POST["tipos"][$contador]."'");
			
			while($ctes = mysqli_fetch_array($clientes)){
				if($ctes['cli_email']!="" and !is_null($ctes['cli_email']))$emails .=$ctes['cli_email'].",";
			}
			$contador++;
		}
	}
	
	$numero =(count($_POST["grupos"]));
	if($numero>0){
		$contador=0;
		while($contador<$numero){
			$clientes = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes
			INNER JOIN clientes_categorias ON cpcat_categoria='".$_POST["grupos"][$contador]."' AND cpcat_cliente=cli_id
			GROUP BY cli_id");
			
			while($ctes = mysqli_fetch_array($clientes)){
				if($ctes['cli_email']!="" and !is_null($ctes['cli_email']))$emails .=$ctes['cli_email'].",";
			}
			$contador++;
		}
	}
	
	$numC = strlen($emails)-1;
	$emails = substr($emails,0,$numC);
	
	if($_FILES['boletin']['name']!=""){
		$archivo = $_FILES['boletin']['name'];
		$destino = "files/adjuntos";
		move_uploaded_file($_FILES['boletin']['tmp_name'], $destino ."/".$archivo);
	}
	$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
	$fin .= '
				<center>
					<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
					<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
						
						<p style="color:'.$configuracion["conf_color_letra"].';">'.$_POST["mensaje"].'</p>
						
						<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/adjuntos/'.$archivo.'" width="780"></p>
						
						<p align="center"><a href="'.$configuracion["conf_url_boton"].'" target="_blank" style="height:50px; width:100%; background:'.$configuracion["conf_color_letra"].'; color:'.$configuracion["conf_fondo_mensaje"].'; padding:10px; text-decoration:none; margin:10px; border-radius:0px 5px 0px 5px;">'.$configuracion["conf_nombre_boton"].'</a></p>
						
						<p align="center" style="color:'.$configuracion["conf_color_letra"].';">
							<img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="80"><br>
							'.$configuracion["conf_mensaje_pie"].'<br>
							<a href="'.$configuracion["conf_web"].'" style="color:'.$configuracion["conf_color_link"].';">'.$configuracion["conf_web"].'</a>
						</p>
						
					</div>
				</center>
				<p>&nbsp;</p>
			';	
	$fin .='';						
	$fin .=  '<html><body>';							
	$sfrom=$configuracion["conf_email"]; //LA CUETA DEL QUE ENVIA EL MENSAJE			
	$sdestinatario=$emails; //CUENTA DEL QUE RECIBE EL MENSAJE			
	$ssubject=$_POST["asunto"]; //ASUNTO DEL MENSAJE 				
	$shtml=$fin; //MENSAJE EN SI			
	$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
	$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
	$sheader=$sheader."Mime-Version: 1.0\n"; 		
	$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
	@mail($sdestinatario,$ssubject,$shtml,$sheader);
	echo '<script type="text/javascript">window.location.href="enviar-mensaje.php?envd=1";</script>';
	exit();
}
//AGREGAR ORDENES DE SERVICIO
if($_POST["idSql"]==33){
	if($_POST["fechaFin"]=="") $_POST["fechaFin"] = '0000-00-00'; if($_POST["ord_fecha_entrega"]=="") $_POST["ord_fecha_entrega"] = '0000-00-00';
	mysqli_query($conexionBdPrincipal,"INSERT INTO ordenes_servicio(ord_fecha_registro, ord_fecha_solicitud, ord_fecha_fin, ord_contacto_cliente, ord_descripcion, ord_canal, ord_estado, ord_observaciones, ord_prioridad, ord_fecha_entrega)VALUES(now(),'".$_POST["fechaSolicitud"]."','".$_POST["fechaFin"]."','".$_POST["contacto"]."','".$_POST["descripcion"]."','".$_POST["canal"]."','".$_POST["estado"]."','".$_POST["observaciones"]."','".$_POST["prioridad"]."','".$_POST["fechaIdeal"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="ordenes-servicio-editar.php?id='.$idInsertU.'&msg=1";</script>';
	exit();
}
//EDITAR ORDENES DE SERVICIO
if($_POST["idSql"]==34){
	if($_POST["fechaFin"]=="") $_POST["fechaFin"] = '0000-00-00'; if($_POST["ord_fecha_entrega"]=="") $_POST["ord_fecha_entrega"] = '0000-00-00';
	mysqli_query($conexionBdPrincipal,"UPDATE ordenes_servicio SET ord_fecha_solicitud='".$_POST["fechaSolicitud"]."', ord_fecha_fin='".$_POST["fechaFin"]."', ord_contacto_cliente='".$_POST["contacto"]."', ord_descripcion='".$_POST["descripcion"]."', ord_canal='".$_POST["canal"]."', ord_estado='".$_POST["estado"]."', ord_observaciones='".$_POST["observaciones"]."', ord_prioridad='".$_POST["prioridad"]."', ord_fecha_entrega='".$_POST["fechaIdeal"]."' WHERE ord_id='".$_POST["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="ordenes-servicio-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}
//AGREGAR COTIZACIONES
if($_POST["idSql"]==35){
	if($_POST["valor"]=="") $_POST["valor"] = '0'; if($_POST["impuestos"]=="") $_POST["impuestos"] = '0';
	mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion(cotiz_fecha_propuesta, cotiz_descripcion, cotiz_valor, cotiz_observaciones, cotiz_cliente, cotiz_impuestos)VALUES('".$_POST["fechaPropuesta"]."','".$_POST["descripcion"]."','".$_POST["valor"]."','".$_POST["observaciones"]."','".$_POST["cliente"]."','".$_POST["impuestos"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="cotizaciones-editar.php?id='.$idInsertU.'&msg=1";</script>';
	exit();
}
//EDITAR COTIZACIONES
if($_POST["idSql"]==36){
	if($_POST["valor"]=="") $_POST["valor"] = '0'; if($_POST["impuestos"]=="") $_POST["impuestos"] = '0';
	mysqli_query($conexionBdPrincipal,"UPDATE cotizacion SET cotiz_fecha_propuesta='".$_POST["fechaPropuesta"]."', cotiz_descripcion='".$_POST["descripcion"]."', cotiz_valor='".$_POST["valor"]."', cotiz_observaciones='".$_POST["observaciones"]."', cotiz_cliente='".$_POST["cliente"]."', cotiz_impuestos='".$_POST["impuestos"]."' WHERE cotiz_id='".$_POST["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="cotizaciones-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}
//AGREGAR SUCURSALES
if($_POST["idSql"]==37){
	mysqli_query($conexionBdPrincipal,"INSERT INTO sucursales(sucu_cliente_principal, sucu_ciudad, sucu_direccion, sucu_telefono, sucu_celular, sucu_telefonos, sucu_nombre)VALUES('".$_POST["cte"]."','".$_POST["ciudad"]."','".$_POST["direccion"]."','".$_POST["telefono"]."','".$_POST["celular"]."','".$_POST["telefonos"]."','".$_POST["nombre"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="clientes-sucursales-editar.php?id='.$idInsertU.'&msg=1&cte='.$_POST["cte"].'";</script>';
	exit();
}
//EDITAR SUCURSALES
if($_POST["idSql"]==38){
	mysqli_query($conexionBdPrincipal,"UPDATE sucursales SET sucu_ciudad='".$_POST["ciudad"]."', sucu_direccion='".$_POST["direccion"]."', sucu_telefono='".$_POST["telefono"]."', sucu_celular='".$_POST["celular"]."', sucu_telefonos='".$_POST["telefonos"]."', sucu_nombre='".$_POST["nombre"]."' WHERE sucu_id='".$_POST["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="clientes-sucursales-editar.php?id='.$_POST["id"].'&msg=1&cte='.$_POST["cte"].'";</script>';
	exit();
}
//AGREGAR TIKETS CLIENTES
if($_POST["idSql"]==39){
	mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_tikets(tik_asunto_principal, tik_tipo_tiket, tik_fecha_creacion, tik_usuario_responsable, tik_estado, tik_cliente, tik_prioridad, tik_sucursal)VALUES('".$_POST["asuntoP"]."','".$_POST["tipoTicket"]."',now(),'".$_SESSION["id"]."',1,'".$_POST["cliente"]."','".$_POST["prioridad"]."','".$_POST["sucursal"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="tickets-detalles.php?idTK='.$idInsertU.'&msg=10";</script>';
	exit();
}
//EDITAR TIKETS CLIENTES
if($_POST["idSql"]==40){
	mysqli_query($conexionBdPrincipal,"UPDATE clientes_tikets SET tik_asunto_principal='".$_POST["asuntoP"]."', tik_tipo_tiket='".$_POST["tipoS"]."', tik_fecha_creacion='".$_POST["fechaInicio"]."', tik_estado='".$_POST["estado"]."', tik_prioridad='".$_POST["prioridad"]."', tik_observaciones='".$_POST["observaciones"]."', tik_referencia='".$_POST["referencia"]."', tik_canal='".$_POST["canal"]."', tik_equipo='".$_POST["equipo"]."' WHERE tik_id='".$_POST["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="clientes-tikets-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}
//AGREGAR ABONO A FACTURAS
if($_POST["idSql"]==41){
	if($_FILES['archivo']['name']!=""){
		$archivo = $_FILES['archivo']['name'];
		$destino = "files/comprobantes";
		move_uploaded_file($_FILES['archivo']['tmp_name'], $destino ."/".$archivo);
	}
	mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion_abonos(fpab_factura, fpab_fecha_abono, fpab_valor, fpab_fecha_registro, fpab_observaciones, fpab_medio_pago, fpab_responsable_registro, fpab_comprobante)VALUES('".$_POST["fact"]."','".$_POST["fecha"]."','".$_POST["valor"]."',now(),'".$_POST["observaciones"]."','".$_POST["medio"]."','".$_SESSION["id"]."','".$archivo."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	//Calculamos el saldo
	$abonos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT sum(fpab_valor), fact_valor, fact_id FROM facturacion_abonos, facturacion
	WHERE fpab_factura='".$_POST["fact"]."' AND fact_id='".$_POST["fact"]."'"));
	$saldoFinal = $abonos[1] - $abonos[0];
	if($saldoFinal<=0){
		mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_estado=1 WHERE fact_id='".$_POST["fact"]."' AND fact_estado!=3");
		
	}else{
		mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_estado=2 WHERE fact_id='".$_POST["fact"]."' AND fact_estado!=3");
		
	}
	
	echo '<script type="text/javascript">window.location.href="facturacion-abonos-editar.php?id='.$idInsertU.'&msg=1&fact='.$_POST["fact"].'";</script>';
	exit();
}
//EDITAR ABONO A FACTURAS
if($_POST["idSql"]==42){
	if($_FILES['archivo']['name']!=""){
		$archivo = $_FILES['archivo']['name'];
		$destino = "files/comprobantes";
		move_uploaded_file($_FILES['archivo']['tmp_name'], $destino ."/".$archivo);
		mysqli_query($conexionBdPrincipal,"UPDATE facturacion_abonos SET fpab_comprobante='".$archivo."' WHERE fpab_id='".$_POST["id"]."'");
	}
	mysqli_query($conexionBdPrincipal,"UPDATE facturacion_abonos SET fpab_fecha_abono='".$_POST["fecha"]."', fpab_valor='".$_POST["valor"]."', fpab_observaciones='".$_POST["observaciones"]."', fpab_medio_pago='".$_POST["medio"]."', fpab_responsable_modificacion='".$_SESSION["id"]."', fpab_fecha_ultima_modificacion=now() WHERE fpab_id='".$_POST["id"]."'");
	
	//Calculamos el saldo
	$abonos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT sum(fpab_valor), fact_valor, fact_id FROM facturacion_abonos, facturacion
	WHERE fpab_factura='".$_POST["fact"]."' AND fact_id='".$_POST["fact"]."'"));
	$saldoFinal = $abonos[1] - $abonos[0];
	if($saldoFinal<=0){
		mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_estado=1 WHERE fact_id='".$_POST["fact"]."' AND fact_estado!=3");
		
	}else{
		mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_estado=2 WHERE fact_id='".$_POST["fact"]."' AND fact_estado!=3");
		
	}
	
	echo '<script type="text/javascript">window.location.href="facturacion-abonos-editar.php?id='.$_POST["id"].'&msg=2&fact='.$_POST["fact"].'";</script>';
	exit();
}
//AGREGAR SOPORTE PRODUCTOS
if($_POST["idSql"]==43){
	if($_FILES['imagen']['name']!=""){
		$imagen = $_FILES['imagen']['name'];
		$destino = "files/soporte";
		move_uploaded_file($_FILES['imagen']['tmp_name'], $destino ."/".$imagen);
	}
	mysqli_query($conexionBdPrincipal,"INSERT INTO soporte_productos(sop_nombre, sop_descripcion, sop_imagen, sop_video, sop_nivel, sop_padre)VALUES('".$_POST["nombre"]."','".$_POST["descripcion"]."','".$imagen."','".$_POST["video"]."','".$_POST["nivel"]."','".$_POST["padre"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="soporte-productos-editar.php?id='.$idInsertU.'&msg=1";</script>';
	exit();
}
//EDITAR SOPORTE PRODUCTOS
if($_POST["idSql"]==44){
	if($_FILES['imagen']['name']!=""){
		$imagen = $_FILES['imagen']['name'];
		$destino = "files/soporte";
		move_uploaded_file($_FILES['imagen']['tmp_name'], $destino ."/".$imagen);
		mysqli_query($conexionBdPrincipal,"UPDATE soporte_productos SET sop_imagen='".$imagen."' WHERE sop_id='".$_POST["id"]."'");
	}
	mysqli_query($conexionBdPrincipal,"UPDATE soporte_productos SET sop_nombre='".$_POST["nombre"]."', sop_descripcion='".$_POST["descripcion"]."', sop_nivel='".$_POST["nivel"]."', sop_video='".$_POST["video"]."', sop_padre='".$_POST["padre"]."' WHERE sop_id='".$_POST["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="soporte-productos-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}
//AGREGAR ASUNTOS DE TIKETS
if($_POST["idSql"]==45){
	mysqli_query($conexionBdPrincipal,"INSERT INTO tikets_asuntos(tkpas_nombre)VALUES('".$_POST["nombre"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="tikets-asuntos-editar.php?id='.$idInsertU.'&msg=1";</script>';
	exit();
}
//EDITAR ASUNTOS DE TIKETS
if($_POST["idSql"]==46){
	mysqli_query($conexionBdPrincipal,"UPDATE tikets_asuntos SET tkpas_nombre='".$_POST["nombre"]."' WHERE tkpas_id='".$_POST["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="tikets-asuntos-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}
//AGREGAR REMISIONES
if($_POST["idSql"]==47){
	
	if(trim($_POST["nombreCliente"])!="" and trim($_POST["usuarioCliente"])!="" and trim($_POST["ciudadCliente"])!=""){
		
		$clienteV = mysqli_num_rows(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_usuario='".trim($_POST["usuarioCliente"])."' AND cli_id_empresa='".$idEmpresa."'"));
		if($clienteV>0){
			echo "<div style='font-family:arial; text-align:center'>Ya existe un cliente con este n&uacute;mero de NIT. Verifique para que no lo registre nuevamente.<br><br>
			<a href='javascript:history.go(-1);'>[P&aacute;gina anterior]</a></span> | <a href='clientes.php'>[Ir a clientes]</a></div>";
			exit();
		}
		
		$zona = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM ".BDADMIN.".localidad_ciudades WHERE ciu_id='".$_POST["ciudadCliente"]."'"));
		
		mysqli_query($conexionBdPrincipal,"INSERT INTO clientes(cli_nombre, cli_categoria, cli_email, cli_ciudad, cli_usuario, cli_clave, cli_zona, cli_fecha_registro, cli_fecha_ingreso, cli_celular, cli_responsable, cli_id_empresa)VALUES('".$_POST["nombreCliente"]."',2,'".$_POST["emailCliente"]."','".$_POST["ciudadCliente"]."','".trim($_POST["usuarioCliente"])."','".$_POST["usuarioCliente"]."','".$zona[2]."',now(),now(),'".$_POST["celularCliente"]."','".$_SESSION["id"]."', '".$idEmpresa."')");
		
		$idInsertU = mysqli_insert_id($conexionBdPrincipal);
		
		$_POST["cliente"] = $idInsertU;

		mysqli_query($conexionBdPrincipal,"INSERT INTO sucursales(sucu_cliente_principal, sucu_ciudad, sucu_celular, sucu_nombre)
		VALUES('".$idInsertU."', '".$_POST["ciudadCliente"]."', '".$_POST["celularCliente"]."','Sede principal')");
		
		$idSucursal = mysqli_insert_id($conexionBdPrincipal);
		
		
		mysqli_query($conexionBdPrincipal,"INSERT INTO contactos(cont_nombre, cont_email, cont_cliente_principal, cont_celular, cont_sucursal)
		VALUES('".$_POST["nombreContacto"]."', '".$_POST["emailContacto"]."', '".$_POST["cliente"]."', '".$_POST["celularContacto"]."', '".$idSucursal."')");
		
		$idContacto = mysqli_insert_id($conexionBdPrincipal);
		$_POST["contacto"] = $idContacto;
	}
	
	switch($_POST["tipoEquipo"]){
		case 1: $_POST["equipo"] = 'Estación total'; break;
		case 2: $_POST["equipo"] = 'Teodolito'; break;
		case 3: $_POST["equipo"] = 'Nivel'; break;
		case 4: $_POST["equipo"] = 'GPS'; break;
		case 5: $_POST["equipo"] = 'Nivel digital'; break;
		case 6: $_POST["equipo"] = 'Distanciómetro'; break;
		case 7: $_POST["equipo"] = 'Nivel laser'; break;
		case 8: $_POST["equipo"] = 'Semi-estación'; break;
		case 9: $_POST["equipo"] = 'Colector'; break;
		case 10: $_POST["equipo"] = 'Brújula'; break;
		case 11: $_POST["equipo"] = 'Bastón'; break;
		case 12: $_POST["equipo"] = 'Trípode'; break;
		case 13: $_POST["equipo"] = 'Prisma'; break;
		case 14: $_POST["equipo"] = 'Batería'; break;
		case 15: $_POST["equipo"] = 'Radio'; break;
		case 16: $_POST["equipo"] = 'Estuche'; break;
		case 17: $_POST["equipo"] = 'Drone'; break;
		case 18: $_POST["equipo"] = 'MATENIMIENTO GENERAL DRON'; break;
	}
	
	if(trim($_POST["nombreContacto"])!="" and trim($_POST["emailContacto"])!=""){	
		mysqli_query($conexionBdPrincipal,"INSERT INTO contactos(cont_nombre, cont_email, cont_cliente_principal, cont_celular)
		VALUES('".$_POST["nombreContacto"]."', '".$_POST["emailContacto"]."', '".$_POST["cliente"]."', '".$_POST["celularContacto"]."')");
		
		$idContacto = mysqli_insert_id($conexionBdPrincipal);
		$_POST["contacto"] = $idContacto;
	}
	
	if($_FILES['imagen']['name']!=""){
		$archivo = $_FILES['imagen']['name'];
		$destino = "../../../usuarios/files/adjuntos";
		move_uploaded_file($_FILES['imagen']['tmp_name'], $destino ."/".$archivo);
	}
	
	mysqli_query($conexionBdPrincipal,"INSERT INTO remisiones(rem_fecha, rem_cliente, rem_equipo, rem_referencia, rem_serial, rem_descripcion, rem_estado, rem_asesor, rem_detalles, rem_dias_entrega, rem_dias_reclamar, rem_marca, rem_tipo_equipo, rem_precision_angular, rem_precision_distancia, rem_observacion_salida, rem_contacto, rem_fecha_registro, rem_tiempo_certificado, rem_archivo, rem_tipos_equipos, rem_id_empresa)VALUES(now(), '".$_POST["cliente"]."', '".$_POST["equipo"]."', '".$_POST["referencia"]."', '".$_POST["serial"]."', '".$_POST["descripcion"]."', 1, '".$_SESSION["id"]."', '".$_POST["detalles"]."', '".$_POST["tiempoEntrega"]."', '".$_POST["tiempoReclamar"]."', '".$_POST["marca"]."', '".$_POST["tipoEquipo"]."', '".$_POST["pAngular"]."', '".$_POST["pDistancia"]."', '".$_POST["obsSalida"]."', '".$_POST["contacto"]."', now(), '".$_POST["vigenciaCerificado"]."', '".$archivo."', '".$_POST["tiposEquipos"]."', '".$idEmpresa."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	$numero =(count($_POST["servicios"]));
	$contador=0;
	mysqli_query($conexionBdPrincipal,"DELETE FROM remisiones_servicios WHERE remxs_id_remision='".$idInsertU."'");
	
	while($contador<$numero){
		mysqli_query($conexionBdPrincipal,"INSERT INTO remisiones_servicios(remxs_id_remision, remxs_id_servicio)VALUES('".$idInsertU."',".$_POST["servicios"][$contador].")");
		
		$contador++;
	}
	
	echo '<script type="text/javascript">window.location.href="lab-remisiones-agregar.php?id='.$idInsertU.'&msg=1&cte='.$_POST["cliente"].'";</script>';
	exit();

}
//EDITAR REMISIONES
if($_POST["idSql"]==48){
	
	switch($_POST["tipoEquipo"]){
		case 1: $_POST["equipo"] = 'Estación total'; break;
		case 2: $_POST["equipo"] = 'Teodolito'; break;
		case 3: $_POST["equipo"] = 'Nivel'; break;
		case 4: $_POST["equipo"] = 'GPS'; break;
		case 5: $_POST["equipo"] = 'Nivel digital'; break;
		case 6: $_POST["equipo"] = 'Distanciómetro'; break;
		case 7: $_POST["equipo"] = 'Nivel laser'; break;
		case 8: $_POST["equipo"] = 'Semi-estación'; break;
		case 9: $_POST["equipo"] = 'Colector'; break;
		case 10: $_POST["equipo"] = 'Brújula'; break;
		case 11: $_POST["equipo"] = 'Bastón'; break;
		case 12: $_POST["equipo"] = 'Trípode'; break;
		case 13: $_POST["equipo"] = 'Prisma'; break;
		case 14: $_POST["equipo"] = 'Batería'; break;
		case 15: $_POST["equipo"] = 'Radio'; break;
		case 16: $_POST["equipo"] = 'Estuche'; break;	
	}
	
	mysqli_query($conexionBdPrincipal,"UPDATE remisiones SET rem_tipo_equipo='".$_POST["tipoEquipo"]."', rem_equipo='".$_POST["equipo"]."', rem_referencia='".$_POST["referencia"]."', rem_serial='".$_POST["serial"]."', rem_descripcion='".$_POST["descripcion"]."', rem_detalles='".$_POST["detalles"]."', rem_dias_entrega='".$_POST["tiempoEntrega"]."', rem_dias_reclamar='".$_POST["tiempoReclamar"]."', rem_precision_angular='".$_POST["pAngular"]."', rem_precision_distancia='".$_POST["pDistancia"]."', rem_observacion_salida='".$_POST["obsSalida"]."', rem_marca='".$_POST["marca"]."', rem_fecha='".$_POST["fecha"]."', rem_tiempo_certificado='".$_POST["vigenciaCerificado"]."', rem_tipos_equipos='".$_POST["tiposEquipos"]."', rem_supervisor='".$_POST["supervisor"]."',
	
	rem_p1vd_grados='".$_POST["p1vd_grados"]."',
	rem_p1vd_minutos='".$_POST["p1vd_minutos"]."',
	rem_p1vd_segundos='".$_POST["p1vd_segundos"]."',
	rem_p1hd_grados='".$_POST["p1hd_grados"]."',
	rem_p1hd_minutos='".$_POST["p1hd_minutos"]."',
	rem_p1hd_segundos='".$_POST["p1hd_segundos"]."',
	rem_p1vi_grados='".$_POST["p1vi_grados"]."',
	rem_p1vi_minutos='".$_POST["p1vi_minutos"]."',
	rem_p1vi_segundos='".$_POST["p1vi_segundos"]."',
	rem_p1hi_grados='".$_POST["p1hi_grados"]."',
	rem_p1hi_minutos='".$_POST["p1hi_minutos"]."',
	rem_p1hi_segundos='".$_POST["p1hi_segundos"]."',
	
	rem_p2vd_grados='".$_POST["p2vd_grados"]."',
	rem_p2vd_minutos='".$_POST["p2vd_minutos"]."',
	rem_p2vd_segundos='".$_POST["p2vd_segundos"]."',
	rem_p2hd_grados='".$_POST["p2hd_grados"]."',
	rem_p2hd_minutos='".$_POST["p2hd_minutos"]."',
	rem_p2hd_segundos='".$_POST["p2hd_segundos"]."',
	rem_p2vi_grados='".$_POST["p2vi_grados"]."',
	rem_p2vi_minutos='".$_POST["p2vi_minutos"]."',
	rem_p2vi_segundos='".$_POST["p2vi_segundos"]."',
	rem_p2hi_grados='".$_POST["p2hi_grados"]."',
	rem_p2hi_minutos='".$_POST["p2hi_minutos"]."',
	rem_p2hi_segundos='".$_POST["p2hi_segundos"]."',
	
	rem_n1_patron='".$_POST["n1_patron"]."',
	rem_n1_equipo='".$_POST["n1_equipo"]."',
	rem_n1_diferencia='".$_POST["n1_diferencia"]."',
	rem_n2_patron='".$_POST["n2_patron"]."',
	rem_n2_equipo='".$_POST["n2_equipo"]."',
	rem_n2_diferencia='".$_POST["n2_diferencia"]."',
	rem_n3_patron='".$_POST["n3_patron"]."',
	rem_n3_equipo='".$_POST["n3_equipo"]."',
	rem_n3_diferencia='".$_POST["n3_diferencia"]."',
	
	rem_l1a='".$_POST["l1a"]."',
	rem_l1b='".$_POST["l1b"]."',
	rem_l1c='".$_POST["l1c"]."',
	rem_l2a='".$_POST["l2a"]."',
	rem_l2b='".$_POST["l2b"]."',
	rem_l2c='".$_POST["l2c"]."',
	rem_error_detectado='".$_POST["errorDetectado"]."'
	
	WHERE rem_id='".$_POST["id"]."'");
	
	
	$numero =(count($_POST["servicios"]));
	$contador=0;
	mysqli_query($conexionBdPrincipal,"DELETE FROM remisiones_servicios WHERE remxs_id_remision='".$_POST["id"]."'");
	
	while($contador<$numero){
		mysqli_query($conexionBdPrincipal,"INSERT INTO remisiones_servicios(remxs_id_remision, remxs_id_servicio)VALUES('".$_POST["id"]."',".$_POST["servicios"][$contador].")");
		
		$contador++;
	}
	
	
	if($_FILES['imgCertificado']['name']!=""){
		$archivo = $_FILES['imgCertificado']['name'];
		$destino = "../../../usuarios/files/adjuntos";
		move_uploaded_file($_FILES['imgCertificado']['tmp_name'], $destino ."/".$archivo);
		mysqli_query($conexionBdPrincipal,"UPDATE remisiones SET rem_foto_certificado='".$archivo."' WHERE rem_id='".$_POST["id"]."'");
	}
	
	echo '<script type="text/javascript">window.location.href="lab-remisiones-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}
//AGREGAR SEGUIMIENTO EQUIPOS
if($_POST["idSql"]==49){
	if($_POST["notfCliente"]==""){$_POST["notfCliente"]=0;}
	
	if($_FILES['archivo']['name']!=""){
		$archivo = $_FILES['archivo']['name'];
		$destino = "../../../usuarios/files/adjuntos";
		move_uploaded_file($_FILES['archivo']['tmp_name'], $destino ."/".$archivo);
	}
	
	mysqli_query($conexionBdPrincipal,"INSERT INTO remisiones_seguimiento(remseg_id_remisiones, remseg_fecha, remseg_usuario, remseg_comentario, remseg_notificar_cliente, remseg_archivo)VALUES('".$_POST["id"]."',now(),'".$_SESSION["id"]."','".$_POST["obsLista"]."<br> ".$_POST["observaciones"]."','".$_POST["notfCliente"]."','".$archivo."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	
	if($_POST["notfCliente"]==1){
		$cliente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_POST["cliente"]."'"));
		$contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_id='".$_POST["contacto"]."'"));
		$remision = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones WHERE rem_id='".$_POST["id"]."'"));
		
		$meses = array("","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fechaHoy = date("d")." de ".$meses[date("m")]." del ".date("Y");
			
		$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
		$fin .= '
					<center>
						<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
						<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
							
							<h3 align="center" style="background:darkblue; color:white;">Notificación servicio técnico - JMEQUIPOS</h3>
							
							<p style="color:'.$configuracion["conf_color_letra"].';">
							'.$fechaHoy.'<br><br>
							Señores:<br>
							'.strtoupper($cliente['cli_nombre']).'.<br>
							'.strtoupper($contacto['cont_nombre']).'.<br><br>
							Cordial saludo,<br><br>
							El departamento técnico de JMEQUIPOS SAS le informa que su equipo se encuentra en el siguiente estado:<br>
							<b>Fecha de entrada:</b> '.$remision["rem_fecha"].'<br>
							<b>Equipo:</b> '.$remision["rem_equipo"].'<br>
							<b>Referencia:</b> '.$remision["rem_referencia"].'<br>
							<b>Serial:</b> '.$remision['rem_serial'].'<br>
							<b>NOTA:</b><br>
							Para revisar este pendiente ingresa a nuestro sistema ORIÓN con tus datos de acceso, mediante el siguiente link.</p>
							
							<p align="center"><a href="https://softjm.com/clientes/index.php?idseg='.$idInsertU.'" target="_blank" style="color:'.$configuracion["conf_color_link"].';">VER EL SEGUIMIENTO</a></p>
							
							<p align="center" style="color:'.$configuracion["conf_color_letra"].';">
								<img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="80"><br>
								'.$configuracion["conf_mensaje_pie"].'<br>
								<a href="'.$configuracion["conf_web"].'" style="color:'.$configuracion["conf_color_link"].';">'.$configuracion["conf_web"].'</a>
							</p>
							
						</div>
					</center>
					<p>&nbsp;</p>
				';	
		$fin .='';						
		$fin .=  '<html><body>';							
		$sfrom="auxlaboratorio@jmequipos.com"; //LA CUETA DEL QUE ENVIA EL MENSAJE			
		$sdestinatario=$contacto['cont_email'].", ".$cliente['cli_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
		$ssubject="Notificación servicio técnico - JMEQUIPOS"; //ASUNTO DEL MENSAJE 				
		$shtml=$fin; //MENSAJE EN SI			
		$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
		$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
		$sheader=$sheader."Mime-Version: 1.0\n"; 		
		$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
		@mail($sdestinatario,$ssubject,$shtml,$sheader);
	}
	/*
	echo $fin."<br>";
	echo $contacto['cont_email'];
	exit();
	*/
	echo '<script type="text/javascript">window.location.href="lab-remisiones-seguimiento.php?id='.$_POST["id"].'";</script>';
	exit();
}

//GET GET GET GET GET GETGET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET
//ELIMINAR USUARIO
if($_GET["get"]==1){
	$idPagina = 53; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM usuarios WHERE usr_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="usuarios.php?msg=3";</script>';
	exit();
}
//ELIMINAR ROLES
if($_GET["get"]==2){
	$idPagina = 54; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM paginas_perfiles WHERE pper_tipo_usuario='".$_GET["id"]."'");
	mysqli_query($conexionBdPrincipal,"DELETE FROM usuarios WHERE usr_tipo='".$_GET["id"]."'");
	mysqli_query($conexionBdPrincipal,"DELETE FROM usuarios_tipos WHERE utipo_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="roles.php?msg=3";</script>';
	exit();
}
//ELIMINAR CLIENTES
if($_GET["get"]==3){
	$idPagina = 55; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM facturacion WHERE fact_cliente='".$_GET["id"]."'");
	mysqli_query($conexionBdPrincipal,"DELETE FROM cliente_seguimiento WHERE cseg_cliente='".$_GET["id"]."'");
	mysqli_query($conexionBdPrincipal,"DELETE FROM clientes_categorias WHERE cpcat_cliente='".$_GET["id"]."'");
	mysqli_query($conexionBdPrincipal,"DELETE FROM clientes_tikets WHERE tik_cliente='".$_GET["id"]."'");
	mysqli_query($conexionBdPrincipal,"DELETE FROM contactos WHERE cont_cliente_principal='".$_GET["id"]."'");
	mysqli_query($conexionBdPrincipal,"DELETE FROM cotizacion WHERE cotiz_cliente='".$_GET["id"]."'");
	
	mysqli_query($conexionBdPrincipal,"DELETE FROM clientes WHERE cli_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="clientes.php?msg=3";</script>';
	exit();
}
//ELIMINAR SEGUIMIENTO CLIENTES
if($_GET["get"]==4){
	$idPagina = 56; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM cliente_seguimiento WHERE cseg_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
//ELIMINAR AUDITORES
if($_GET["get"]==5){
	$idPagina = 57; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM auditores WHERE aud_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="auditores.php?msg=3";</script>';
	exit();
}
//ELIMINAR FACTURAS
if($_GET["get"]==6){
	$idPagina = 58; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM facturacion_abonos WHERE fpab_factura='".$_GET["id"]."'");
	mysqli_query($conexionBdPrincipal,"DELETE FROM facturacion_productos WHERE fpp_factura='".$_GET["id"]."'");
	mysqli_query($conexionBdPrincipal,"DELETE FROM facturacion WHERE fact_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
//ELIMINAR DOCUMENTOS
if($_GET["get"]==7){
	$idPagina = 59; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM documentos WHERE doc_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="documentos.php?msg=3";</script>';
	exit();
}
//OCULTAR CLIENTES
if($_GET["get"]==8){
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_ocultar=1 WHERE cli_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="clientes.php?msg=4";</script>';
	exit();
}
//MOSTRAR TODOS CLIENTES
if($_GET["get"]==9){
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_ocultar=0");
	
	echo '<script type="text/javascript">window.location.href="clientes.php?msg=5";</script>';
	exit();
}
//OCULTAR CLIENTES
if($_GET["get"]==10){
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_ocultar=0 WHERE cli_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="co.php?msg=4";</script>';
	exit();
}
//ELIMINAR DEALER
if($_GET["get"]==11){
	$idPagina = 60; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM dealer WHERE deal_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
//ELIMINAR PRODUCTOS
if($_GET["get"]==12){
	$idPagina = 61; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM productos_materiales WHERE ppmt_producto='".$_GET["id"]."'");
	mysqli_query($conexionBdPrincipal,"DELETE FROM productos WHERE prod_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
//ELIMINAR CATEGORÍA DE PRODUCTOS
if($_GET["get"]==13){
	$idPagina = 62; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM productos WHERE prod_categoria='".$_GET["id"]."'");
	mysqli_query($conexionBdPrincipal,"DELETE FROM productos_categorias WHERE catp_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
//ELIMINAR ZONAS
if($_GET["get"]==14){
	$idPagina = 63; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM zonas WHERE zon_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
//ELIMINAR ENCUESTAS
if($_GET["get"]==15){
	$idPagina = 64; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM encuesta_satisfaccion WHERE encs_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
//ELIMINAR NOTIFICACIONES
if($_GET["get"]==16){
	$idPagina = 65; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM notificaciones WHERE not_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
//ELIMINAR MATERIALES DE PRODUCTOS
if($_GET["get"]==17){
	$idPagina = 71; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM productos_materiales WHERE ppmt_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
//ENVIAR ENCUESTA AL CORREO
if($_GET["get"]==18){
	$contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_id='".$_GET["cont"]."'"));
	$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
	$fin .= '
				<center>
					<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
					<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
						
						<p style="color:'.$configuracion["conf_color_letra"].';">'.strtoupper($contacto['cont_nombre']).',<br>
						Agradecemos se tome 3 minutos para responder una escuesta sobre la atención brindada por nuestra empresa.<br>
						Haga click en el siguiente enlace para responder la encuesta.</p>
						
						<p align="center"><a href="'.$configuracion["conf_url_encuestas"].'/formato-encuesta.php?id='.$_GET["id"].'" target="_blank" style="color:'.$configuracion["conf_color_link"].';">RESPONDER ENCUESTA</a></p>
						
						<p align="center" style="color:'.$configuracion["conf_color_letra"].';">
							<img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="80"><br>
							'.$configuracion["conf_mensaje_pie"].'<br>
							<a href="'.$configuracion["conf_web"].'" style="color:'.$configuracion["conf_color_link"].';">'.$configuracion["conf_web"].'</a>
						</p>
						
					</div>
				</center>
				<p>&nbsp;</p>
			';	
	$fin .='';						
	$fin .=  '<html><body>';							
	$sfrom=$configuracion['conf_email']; //LA CUETA DEL QUE ENVIA EL MENSAJE			
	$sdestinatario=$contacto['cont_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
	$ssubject="Encuesta de satisfaccion"; //ASUNTO DEL MENSAJE 				
	$shtml=$fin; //MENSAJE EN SI			
	$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
	$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
	$sheader=$sheader."Mime-Version: 1.0\n"; 		
	$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
	@mail($sdestinatario,$ssubject,$shtml,$sheader);
	
	echo '<script type="text/javascript">window.location.href="encuesta.php?msg=4";</script>';
	exit();
}
//REPLICAR FACTURA
if($_GET["get"]==19){
	$idPagina = 72; include("verificar-paginas.php");
	//$factura = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM facturacion"));
	mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion (fact_cliente, fact_fecha, fact_valor, fact_estado, fact_usuario_responsable, fact_descripcion, fact_observacion, fact_descuento, fact_producto, fact_numero_fisica, fact_usuario_influyente, fact_fecha_real, fact_fecha_vencimiento) SELECT fact_cliente, now(), fact_valor, fact_estado, fact_usuario_responsable, fact_descripcion, fact_observacion, fact_descuento, fact_producto, fact_numero_fisica, fact_usuario_influyente, now(), fact_fecha_vencimiento FROM facturacion WHERE fact_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
//CAMBIAR DE ESTADO LAS NOTIFICACIONES
if($_GET["get"]==20){
	$not = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM notificaciones WHERE not_id='".$_GET["id"]."'"));
	if($not[5]==1) $estadoN = 2; else $estadoN = 1;
	mysqli_query($conexionBdPrincipal,"UPDATE notificaciones SET not_estado='".$estadoN."' WHERE not_id='".$_GET["id"]."'");
	
	mysqli_query($conexionBdPrincipal,"UPDATE cliente_seguimiento SET cseg_realizado='".$estadoN."' WHERE cseg_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
//ELIMINAR ORDENES DE SERVICIO
if($_GET["get"]==21){
	$idPagina = 76; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM ordenes_servicio WHERE ord_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
//ELIMINAR COTIZACIONES
if($_GET["get"]==22){
	$idPagina = 80; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM cotizacion WHERE cotiz_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
//ELIMINAR SUCURSALES
if($_GET["get"]==23){
	$idPagina = 86; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM sucursales WHERE sucu_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
//ENVIAR COTIZACIÓN AL CORREO
if($_GET["get"]==23){
	$contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_GET["cont"]."'"));
	$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
	$fin .= '
				<center>
					<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
					<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
						
						<p style="color:'.$configuracion["conf_color_letra"].';">'.strtoupper($contacto['cli_nombre']).',<br>
						Estamos enviando la cotización por este medio para que la revise y la pueda imprimir según su necesidad.<br>
						Haga click en el siguiente enlace para revisar la cotización.</p>
						
						<p align="center"><a href="'.$configuracion["conf_url_encuestas"].'/usuarios/reportes/formato-cotizacion-1.php?id='.$_GET["id"].'" target="_blank" style="color:'.$configuracion["conf_color_link"].';">REVISAR COTIZACIÓN</a></p>
						
						<p align="center" style="color:'.$configuracion["conf_color_letra"].';">
							<img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="80"><br>
							'.$configuracion["conf_mensaje_pie"].'<br>
							<a href="'.$configuracion["conf_web"].'" style="color:'.$configuracion["conf_color_link"].';">'.$configuracion["conf_web"].'</a>
						</p>
						
					</div>
				</center>
				<p>&nbsp;</p>
			';	
	$fin .='';						
	$fin .=  '<html><body>';							
	$sfrom=$configuracion['conf_email']; //LA CUETA DEL QUE ENVIA EL MENSAJE			
	$sdestinatario=$contacto['cli_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
	$ssubject="Cotizacion"; //ASUNTO DEL MENSAJE 				
	$shtml=$fin; //MENSAJE EN SI			
	$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
	$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
	$sheader=$sheader."Mime-Version: 1.0\n"; 		
	$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
	@mail($sdestinatario,$ssubject,$shtml,$sheader);
	
	echo '<script type="text/javascript">window.location.href="cotizaciones.php?msg=6";</script>';
	exit();
}
if($_GET["get"]==24){
	$idPagina = 91; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM cliente_seguimiento WHERE cseg_tiket='".$_GET["id"]."'");
	mysqli_query($conexionBdPrincipal,"DELETE FROM clientes_tikets WHERE tik_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
if($_GET["get"]==25){
	$idPagina = 95; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM facturacion_abonos WHERE fpab_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
//AGREGAR ABONO AUTOMÁTICO POR EL VALOR PENDIENTE
if($_GET["get"]==26){
	$nmsg = 8;
	$abonos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT sum(fpab_valor), fact_valor, fact_id, fact_fecha_real, fact_impuestos, fact_retencion, fact_descuento FROM facturacion_abonos, facturacion
	WHERE fpab_factura='".$_GET["id"]."' AND fact_id='".$_GET["id"]."'"));
	$impuestos = $abonos['fact_valor'] * $abonos['fact_impuestos']/100;
	$retencion = $abonos['fact_valor'] * $abonos['fact_retencion']/100;
	$descuento = $res['fact_valor'] * $abonos['fact_descuento']/100;						
	$valorReal = ($abonos['fact_valor'] + $impuestos) - ($retencion + $descuento);					
	$saldoFinal = $valorReal - $abonos[0];
	if($saldoFinal>0){
		mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion_abonos(fpab_factura, fpab_fecha_abono, fpab_valor, fpab_fecha_registro, fpab_observaciones, fpab_medio_pago, fpab_responsable_registro)VALUES('".$_GET["id"]."','".$abonos[3]."','".$saldoFinal."',now(),'Abono automático por el saldo pendiente',8,'".$_SESSION["id"]."')");
		
		$idInsertU = mysqli_insert_id($conexionBdPrincipal);
		
		mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_estado=1, fact_observacion=CONCAT(fact_observacion, ' <br>-- ', now(), ' Abono automático por el saldo pendiente y cambió a estado pagada') WHERE fact_id='".$_GET["id"]."' AND fact_estado!=3");
		
		$nmsg = 7;
	}
	
	echo '<script type="text/javascript">window.location.href="facturacion.php?msg='.$nmsg.'";</script>';
	exit();
}
if($_GET["get"]==27){
	$idPagina = 100; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM soporte_productos WHERE sop_padre='".$_GET["id"]."'");
	mysqli_query($conexionBdPrincipal,"DELETE FROM soporte_productos WHERE sop_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
if($_GET["get"]==28){
	//$idPagina = 100; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE cliente_seguimiento SET cseg_realizado=1 WHERE cseg_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
if($_GET["get"]==29){
	//$idPagina = 100; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE clientes_tikets SET tik_estado=2 WHERE tik_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
//Generar salida a remisión
if($_GET["get"]==30){
	//$idPagina = 100; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE remisiones SET rem_estado=2, rem_fecha_salida=now() WHERE rem_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="lab-remisiones-imprimir.php?id='.$_GET["id"].'&estado=2";</script>';
	exit();
}
//Generar certificado
if($_GET["get"]==31){
	//$idPagina = 100; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE remisiones SET rem_generar_certificado=1, rem_fecha_certificado=now(), rem_estado_certificado=1, rem_fecha=now() WHERE rem_id='".$_GET["id"]."'");
	
		/*
		$cliente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_GET["cte"]."'"));
		$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
		$fin .= '
					<center>
						<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
						<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
							
							<p style="color:'.$configuracion["conf_color_letra"].';">'.strtoupper($cliente['cli_nombre']).',<br>
							Le informamos que su equipo está listo para ser entregado. El certificado <b>No. C'.$_GET["id"].'</b> ya fue generado.<br>
							Agradecemos acercarse a las oficinas de <strong>JMENDOZA EQUIPOS</strong> a reclamar su equipo.
							</p>
							
							<p align="center" style="color:'.$configuracion["conf_color_letra"].';">
								<img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="80"><br>
								'.$configuracion["conf_mensaje_pie"].'<br>
								<a href="'.$configuracion["conf_web"].'" style="color:'.$configuracion["conf_color_link"].';">'.$configuracion["conf_web"].'</a>
							</p>
							
						</div>
					</center>
					<p>&nbsp;</p>
				';	
		$fin .='';						
		$fin .=  '<html><body>';							
		$sfrom=$configuracion['conf_email']; //LA CUETA DEL QUE ENVIA EL MENSAJE			
		$sdestinatario=$cliente['cli_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
		$ssubject="Su equipo está listo para reclamar"; //ASUNTO DEL MENSAJE 				
		$shtml=$fin; //MENSAJE EN SI			
		$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
		$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
		$sheader=$sheader."Mime-Version: 1.0\n"; 		
		$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
		@mail($sdestinatario,$ssubject,$shtml,$sheader);
		*/
?>
		
<?php

	echo '<script type="text/javascript">window.location.href="lab-remisiones.php?idRem='.$_GET["id"].'";</script>';
	exit();
}
//ENVIAR REMISIÓN ACTUAL AL CLIENTE
if($_GET["get"]==32){

		$cliente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_GET["cte"]."'"));
		$contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_id='".$_GET["contacto"]."'"));
		$remision = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones WHERE rem_id='".$_GET["id"]."'"));
		
		$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
		$fin .= '
					<center>
						<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
						<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
							
							<p style="color:'.$configuracion["conf_color_letra"].';">
							'.date("d-m-Y").'<br>
							Señores:<br>
							'.strtoupper($cliente['cli_nombre']).'.<br>
							'.strtoupper($contacto['cont_nombre']).'.<br>
							Cordial saludo,<br>
							Adjuntamos link de descarga de la remisión actual de su equipo con los siguientes datos:<br>
							<b>Fecha de entrada:</b> '.$remision["rem_fecha"].'<br>
							<b>Equipo:</b> '.$remision["rem_equipo"].'<br>
							<b>Referencia:</b> '.$remision["rem_referencia"].'<br>
							<b>Serial:</b> '.$remision['rem_serial'].'<br>
							<b>LINK DE DESCARGA:</b><br> https://softjm.com/v2.0/usuarios/empresa/lab-remisiones-imprimir.php?id='.$_GET["id"].'&estado='.$remision["rem_estado"].'<br>
							</p>
							
							<p align="center" style="color:'.$configuracion["conf_color_letra"].';">
								<img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="80"><br>
								'.$configuracion["conf_mensaje_pie"].'<br>
								<a href="'.$configuracion["conf_web"].'" style="color:'.$configuracion["conf_color_link"].';">'.$configuracion["conf_web"].'</a>
							</p>
							
						</div>
					</center>
					<p>&nbsp;</p>
				';	
		$fin .='';						
		$fin .=  '<html><body>';							
		$sfrom=$configuracion['conf_email']; //LA CUETA DEL QUE ENVIA EL MENSAJE			
		$sdestinatario=$contacto['cont_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
		$ssubject="Remisión de su equipo - JMEQUIPOS"; //ASUNTO DEL MENSAJE 				
		$shtml=$fin; //MENSAJE EN SI			
		$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
		$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
		$sheader=$sheader."Mime-Version: 1.0\n"; 		
		$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
		@mail($sdestinatario,$ssubject,$shtml,$sheader);
	/*
	echo $fin."<br>";
	echo $contacto['cont_email'];
	exit();
	*/
	echo '<script type="text/javascript">window.location.href="lab-remisiones.php?msg=1";</script>';
	exit();
}
//Quitar imagen de la remisión
if($_GET["get"]==33){
	//$idPagina = 100; include("verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE remisiones SET rem_archivo='' WHERE rem_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="lab-remisiones-editar.php?id='.$_GET["id"].'";</script>';
	exit();
}
?>