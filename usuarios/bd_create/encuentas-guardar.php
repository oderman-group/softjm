<?php
require_once("../sesion.php");

$idPagina = 283;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

mysqli_query($conexionBdPrincipal,"INSERT INTO encuesta_satisfaccion(encs_fecha, encs_cliente, encs_atendido, encs_producto, encs_contacto)VALUES(now(),'" . $_POST["cliente"] . "','" . $_POST["usuario"] . "','" . $_POST["producto"] . "','" . $_POST["contacto"] . "')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);

	$contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_id='" . $_POST["contacto"] . "'"));
	$fin =  '<html><body style="background-color:' . $configuracion["conf_fondo_boletin"] . ';">';
	$fin .= '
				<center>
					<p align="center"><img src="' . $configuracion["conf_url_encuestas"] . '/usuarios/files/' . $configuracion["conf_logo"] . '" width="350"></p>
					<div style="font-family:arial; background:' . $configuracion["conf_fondo_mensaje"] . '; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
						
						<p style="color:' . $configuracion["conf_color_letra"] . ';">' . strtoupper($contacto['cont_nombre']) . ',<br>
						Agradecemos se tome 3 minutos para responder una escuesta sobre la atención brindada por nuestra empresa.<br>
						Haga click en el siguiente enlace para responder la encuesta.</p>
						
						<p align="center"><a href="' . $configuracion["conf_url_encuestas"] . '/formato-encuesta.php?id=' . $idInsertU . '" target="_blank" style="color:' . $configuracion["conf_color_link"] . ';">RESPONDER ENCUESTA</a></p>
						
						<p align="center" style="color:' . $configuracion["conf_color_letra"] . ';">
							<img src="' . $configuracion["conf_url_encuestas"] . '/usuarios/files/' . $configuracion["conf_logo"] . '" width="80"><br>
							' . $configuracion["conf_mensaje_pie"] . '<br>
							<a href="' . $configuracion["conf_web"] . '" style="color:' . $configuracion["conf_color_link"] . ';">' . $configuracion["conf_web"] . '</a>
						</p>
						
					</div>
				</center>
				<p>&nbsp;</p>
			';
	$fin .= '';
	$fin .=  '<html><body>';
	$sfrom = $configuracion['conf_email']; //LA CUETA DEL QUE ENVIA EL MENSAJE			
	$sdestinatario = $contacto['cont_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
	$ssubject = "Encuesta de satisfaccion"; //ASUNTO DEL MENSAJE 				
	$shtml = $fin; //MENSAJE EN SI			
	$sheader = "From:" . $sfrom . "\nReply-To:" . $sfrom . "\n";
	$sheader = $sheader . "X-Mailer:PHP/" . phpversion() . "\n";
	$sheader = $sheader . "Mime-Version: 1.0\n";
	$sheader = $sheader . "Content-Type: text/html; charset=UTF-8\r\n";
	@mail($sdestinatario, $ssubject, $shtml, $sheader);
	
	

echo '<script type="text/javascript">window.open("../../formato-encuesta.php?id=' . $idInsertU . '");</script>';
echo '<script type="text/javascript">window.location.href="../encuesta-editar.php?id=' . $idInsertU . '&msg=1";</script>';
exit();

