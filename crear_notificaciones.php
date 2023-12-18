<?php
$conexion=mysql_connect("localhost","odermancom_jm_crm",")S{q9V7hBJv;");
mysql_select_db("odermancom_jm_crm",$conexion);

$configuracion = mysql_fetch_array(mysql_query("SELECT * FROM configuracion WHERE conf_id=1",$conexion));

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'librerias/phpmailer/Exception.php';
require 'librerias/phpmailer/PHPMailer.php';
require 'librerias/phpmailer/SMTP.php';
/*
//RECORDAR AL USUARIO SEGUIMIENTOS PENDIENTES
$notif = mysql_query("SELECT DATEDIFF(cseg_fecha_proximo_contacto, now()), cseg_usuario_encargado, cseg_asunto, cseg_cliente, cseg_id FROM cliente_seguimiento 
WHERE cseg_fecha_proximo_contacto!='0000-00-00' AND (DATEDIFF(cseg_fecha_proximo_contacto, now())=0 or DATEDIFF(cseg_fecha_proximo_contacto, now())=1)",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}

while($not=mysql_fetch_array($notif)){

		mysql_query("INSERT INTO notificaciones(not_asunto, not_cliente, not_usuario, not_visto, not_estado, not_fecha, not_seguimiento)VALUES('".$not[2]."', '".$not[3]."', '".$not[1]."', 0, 1, now(), '".$not[4]."')",$conexion);
		if(mysql_errno()!=0){echo mysql_error(); exit();}
		
		$cliente = mysql_fetch_array(mysql_query("SELECT * FROM clientes WHERE cli_id='".$not[3]."'",$conexion));
		$usuario = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usr_id='".$not[1]."'",$conexion));
		
		$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
		$fin .= '
					<center>
						<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
						<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
							
							<p style="color:'.$configuracion["conf_color_letra"].';">'.strtoupper($contacto['usr_nombre']).',<br>
							Te han encargado un nuevo seguimiento para uno de los clientes.<br>
							<b>ALGUNOS DETALLES</b><br>
							Asunto: '.$not[2].'<br>
							Cliente: '.$cliente['cli_nombre'].'<br>
							Para revisar este pendiente ingresa al CRM con tus datos de acceso, mediante el siguiente link.</p>
							
							<p align="center"><a href="http://softjm.com/index.php?idseg='.$not[4].'" target="_blank" style="color:'.$configuracion["conf_color_link"].';">INGRESAR AL CRM</a></p>
							
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
		
		// Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
			//Server settings
			$mail->SMTPDebug = 2;                                       // Enable verbose debug output
			$mail->isSMTP();                                            // Set mailer to use SMTP
			$mail->Host       = 'mail.orioncrm.com.co';  // Specify main and backup SMTP servers
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = $configuracion['conf_email'];                     // SMTP username
			$mail->Password   = $configuracion['conf_clave_correo'];                          // SMTP password
			$mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
			$mail->Port       = 465;                                   // TCP port to connect to

			//Recipients
			$mail->setFrom($configuracion['conf_email'], '');
			$mail->addAddress($usuario['usr_email'], $usuario['usr_nombre']);     // Add a recipient
			
			
			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = 'ORIÓN - Seguimiento a clientes';
			$mail->Body = $fin;
			$mail->CharSet = 'UTF-8';

			$mail->send();
			echo 'Enviada notificación de seguimiento de clientes';
		} catch (Exception $e) {echo "Error: {$mail->ErrorInfo}";}
		
	
}
*/

//RECORDAR RENOVAR CERTIFICADOS
$consultaC = mysql_query("SELECT * FROM remisiones
INNER JOIN clientes ON cli_id=rem_cliente
",$conexion);
$no = 1;
while($resC = mysql_fetch_array($consultaC)){	
	$vencimiento = mysql_fetch_array(mysql_query("SELECT DATEDIFF(DATE_ADD(rem_fecha, INTERVAL 6 MONTH), now()) 
	FROM remisiones 
	WHERE rem_id='".$resC['rem_id']."'",$conexion));
	
	//Actualizar estado del certificado a vencido con 1 o más días
	if($vencimiento[0]<0){
		mysql_query("UPDATE remisiones SET rem_estado_certificado='".REM_ESTADO_CERTIFICADO_VENCIDO."' WHERE rem_id='".$resC['rem_id']."'",$conexion);
		if(mysql_errno()!=0){echo mysql_error(); exit();}
	}
	
	//Enviar un correo de que vence ese día el certificado
	if($vencimiento[0]=='0'){
		$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
		$fin .= '
					<center>
						<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
						<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
							
							<p style="color:'.$configuracion["conf_color_letra"].';">'.strtoupper($resC['cli_nombre']).',<br>
							Le informamos que su certificado <b>C'.$resC['rem_id'].'</b> vence HOY.<br>
							Le sugerimos ponerse en contacto con nuestra área de servicio técnico al <strong>PBX: (4) 322 0619 EXT. 102, WhatsApp: 310 798 3526</strong>  o al correo <strong>laboratorio@jmequipos.com</strong> para la renovación de su certificado.<br><br>
							<b>DETALLES</b><br>
							<b>Equipo:</b> '.$resC['rem_equipo'].'<br>
							<b>Referencia:</b> '.$resC['rem_referencia'].'<br>
							<b>Marca:</b> '.$resC['rem_marca'].'<br>
							<b>Serial:</b> '.$resC['rem_serial'].'<br>
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

		// Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
			//Server settings
			$mail->SMTPDebug = 2;                                       // Enable verbose debug output
			$mail->isSMTP();                                            // Set mailer to use SMTP
			$mail->Host       = 'mail.orioncrm.com.co';  // Specify main and backup SMTP servers
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = $configuracion['conf_email'];                     // SMTP username
			$mail->Password   = $configuracion['conf_clave_correo'];                              // SMTP password
			$mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
			$mail->Port       = 465;                                    // TCP port to connect to

			//Recipients
			$mail->setFrom($configuracion['conf_email'], 'JMEQUIPOS');
			$mail->addAddress($resC['cli_email'], $resC['cli_nombre']);     // Add a recipient
			$mail->addAddress('auxlaboratorio@jmequipos.com', 'Auxiliar laboratorio');     // Add a recipient
			$mail->addAddress('laboratorio@jmequipos.com', 'Laboratorio');     // Add a recipient
			$mail->addAddress('recepcion@jmequipos.com', 'Laboratorio');     // Add a recipient
			//$mail->addAddress('jhonoderman@gmail.com', 'Jhon Mejia');     // Add a recipient
			
			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = 'Recordamos que hoy vence el certificado ('.$resC['rem_id'].') - JMEQUIPOS';
			$mail->Body = $fin;
			$mail->CharSet = 'UTF-8';

			$mail->send();
			echo 'Enviada notificación de vencimiento HOY';
		} catch (Exception $e) {echo "Error: {$mail->ErrorInfo}";}
	}
	
	/*
	//Enviar recordatorio un mes antes
	if($vencimiento[0]==30){
		$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
		$fin .= '
					<center>
						<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
						<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
							
							<p style="color:'.$configuracion["conf_color_letra"].';">'.strtoupper($resC['cli_nombre']).',<br>
							Le informamos que su certificado <b>C'.$resC['rem_id'].'</b> vence en los próximos 30 días.<br>
							Le sugerimos ponerse en contacto con nuestra área de servicio técnico al <strong>PBX: (4) 322 0619 EXT. 102, WhatsApp: 310 798 3526</strong>  o al correo <strong>laboratorio@jmequipos.com</strong> para la renovación de su certificado.<br><br>
							<b>DETALLES</b><br>
							<b>Equipo:</b> '.$resC['rem_equipo'].'<br>
							<b>Referencia:</b> '.$resC['rem_referencia'].'<br>
							<b>Marca:</b> '.$resC['rem_marca'].'<br>
							<b>Serial:</b> '.$resC['rem_serial'].'<br>
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

		// Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
			//Server settings
			$mail->SMTPDebug = 2;                                       // Enable verbose debug output
			$mail->isSMTP();                                            // Set mailer to use SMTP
			$mail->Host       = 'mail.orioncrm.com.co';  // Specify main and backup SMTP servers
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = $configuracion['conf_email'];                     // SMTP username
			$mail->Password   = $configuracion['conf_clave_correo'];                               // SMTP password
			$mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
			$mail->Port       = 465;                                    // TCP port to connect to

			//Recipients
			$mail->setFrom($configuracion['conf_email'], 'JMEQUIPOS');
			$mail->addAddress($resC['cli_email'], $resC['cli_nombre']);     // Add a recipient
			$mail->addAddress('auxlaboratorio@jmequipos.com', 'Daniel Tangarife');     // Add a recipient
			
			
			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = 'Vencimiento de certificado (30 días) - JMEQUIPOS';
			$mail->Body = $fin;
			$mail->CharSet = 'UTF-8';

			$mail->send();
			echo 'Enviada notificación de vencimiento a los 30 días';
		} catch (Exception $e) {echo "Error: {$mail->ErrorInfo}";}

	}*/
}

/*
//NOTIFICACIÓN SOBRE REMISIONES
$notif = mysql_query("SELECT DATEDIFF(now(), rem_fecha_registro), rem_cliente, rem_asesor, rem_equipo, rem_id FROM remisiones WHERE rem_estado=1",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}

while($not=mysql_fetch_array($notif)){
	if($not[0]==3){
		
		$cliente = mysql_fetch_array(mysql_query("SELECT * FROM clientes WHERE cli_id='".$not[1]."'",$conexion));
		$contacto = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usr_id='".$not[2]."'",$conexion));
		$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
		$fin .= '
					<center>
						<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
						<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
							
							<p style="color:'.$configuracion["conf_color_letra"].';">'.strtoupper($contacto['usr_nombre']).',<br>
							Hace 3 días registraste la remisión número <b>'.$not[4].'</b> y aún no se le ha dado salida. Verifica por favor.
							</p>
							
							<p align="center"><a href="http://softjm.com/index.php" target="_blank" style="color:'.$configuracion["conf_color_link"].';">INGRESAR AL CRM</a></p>
							
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
		
		// Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
			//Server settings
			$mail->SMTPDebug = 2;                                       // Enable verbose debug output
			$mail->isSMTP();                                            // Set mailer to use SMTP
			$mail->Host       = 'mail.orioncrm.com.co';  // Specify main and backup SMTP servers
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = $configuracion['conf_email'];                     // SMTP username
			$mail->Password   = $configuracion['conf_clave_correo'];                               // SMTP password
			$mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
			$mail->Port       = 465;                                    // TCP port to connect to

			//Recipients
			$mail->setFrom($configuracion['conf_email'], 'JMEQUIPOS');
			$mail->addAddress($contacto['usr_email'], $contacto['usr_nombre']);     // Add a recipient
			$mail->addAddress('auxlaboratorio@jmequipos.com', 'Daniel Tangarife');     // Add a recipient
			
			
			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = 'ORIÓN - remisión de entrada No.'.$not[4];
			$mail->Body = $fin;
			$mail->CharSet = 'UTF-8';

			$mail->send();
			echo 'Enviada notificacion de remisiones con más de 3 días';
		} catch (Exception $e) {echo "Error: {$mail->ErrorInfo}";}

	}
}
*/
?>