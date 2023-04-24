<?php
require_once("../sesion.php");

if ($_FILES['archivo']['name'] != "") {
    $archivo = $_FILES['archivo']['name'];
    $destino = "files/adjuntos";
    move_uploaded_file($_FILES['archivo']['tmp_name'], $destino . "/" . $archivo);
    mysqli_query($conexionBdPrincipal,"UPDATE cliente_seguimiento SET cseg_archivo='" . $archivo . "' WHERE cseg_id='" . $_POST["id"] . "'");
}

$datos = 0;
if ($_POST["datos"] == 1) {
    $datos = 1;
}

$cotizo = 0;
if ($_POST["cotizo"] == 1) {
    $cotizo = 1;
}

$vendio = 0;
if ($_POST["vendio"] == 1) {
    $vendio = 1;
}

mysqli_query($conexionBdPrincipal,"UPDATE cliente_seguimiento SET cseg_cliente='" . $_POST["cliente"] . "', cseg_observacion='" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["observaciones"]) . "', cseg_fecha_proximo_contacto='" . $_POST["fechaPC"] . "', cseg_asunto='" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["asunto"]) . "', cseg_usuario_encargado='" . $_POST["encargado"] . "', cseg_cotizacion='" . $_POST["cotizacion"] . "', cseg_fecha_contacto='" . $_POST["fechaContacto"] . "', cseg_tipo='" . $_POST["tipoS"] . "', cseg_contacto='" . $_POST["contacto"] . "', cseg_canal='" . $_POST["canal"] . "', cseg_cotizo='" . $cotizo . "', cseg_vendio='" . $vendio . "', cseg_consiguio_datos='" . $datos . "', cseg_forma_contacto='" . $_POST["formaContacto"] . "' WHERE cseg_id='" . $_POST["id"] . "'");

/*
if ($_POST["notf"] == 1) {
    mysqli_query($conexionBdPrincipal,"INSERT INTO notificaciones(not_asunto, not_cliente, not_usuario, not_visto, not_estado, not_seguimiento, not_fecha)VALUES('" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["asunto"]) . "', '" . $_POST["cliente"] . "', '" . $_POST["encargado"] . "', 0, 1, '" . $_POST["id"] . "', now())");
    
    $cliente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='" . $_POST["cliente"] . "'"));
    $contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='" . $_POST["encargado"] . "'"));
    $fin =  '<html><body style="background-color:' . $configuracion["conf_fondo_boletin"] . ';">';
    $fin .= '
                <center>
                    <p align="center"><img src="' . $configuracion["conf_url_encuestas"] . '/usuarios/files/' . $configuracion["conf_logo"] . '" width="350"></p>
                    <div style="font-family:arial; background:' . $configuracion["conf_fondo_mensaje"] . '; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
                        
                        <p style="color:' . $configuracion["conf_color_letra"] . ';">' . strtoupper($contacto['usr_nombre']) . ',<br>
                        Te han encargado un nuevo seguimiento para uno de los clientes.<br>
                        <b>ALGUNOS DETALLES</b><br>
                        Asunto: ' . $_POST["asunto"] . '<br>
                        Cliente: ' . $cliente['cli_nombre'] . '<br>
                        Para revisar este pendiente ingresa al CRM con tus datos de acceso, mediante el siguiente link.</p>
                        
                        <p align="center"><a href="http://softjm.com/index.php?idseg=' . $_POST["id"] . '" target="_blank" style="color:' . $configuracion["conf_color_link"] . ';">IR AL SEGUIMIENTO</a></p>
                        
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
    $sdestinatario = $cliente['cli_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
    $ssubject = "CRM - Seguimiento a clientes"; //ASUNTO DEL MENSAJE 				
    $shtml = $fin; //MENSAJE EN SI			
    $sheader = "From:" . $sfrom . "\nReply-To:" . $sfrom . "\n";
    $sheader = $sheader . "X-Mailer:PHP/" . phpversion() . "\n";
    $sheader = $sheader . "Mime-Version: 1.0\n";
    $sheader = $sheader . "Content-Type: text/html; charset=UTF-8\r\n";
    @mail($sdestinatario, $ssubject, $shtml, $sheader);
}*/

/*
if ($_POST["notfCliente"] == 1) {
    $cliente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='" . $_POST["cliente"] . "'"));
    $contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='" . $_POST["encargado"] . "'"));
    $fin =  '<html><body style="background-color:' . $configuracion["conf_fondo_boletin"] . ';">';
    $fin .= '
                <center>
                    <p align="center"><img src="' . $configuracion["conf_url_encuestas"] . '/usuarios/files/' . $configuracion["conf_logo"] . '" width="350"></p>
                    <div style="font-family:arial; background:' . $configuracion["conf_fondo_mensaje"] . '; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
                        
                        <p style="color:' . $configuracion["conf_color_letra"] . ';">' . strtoupper($cliente['cli_nombre']) . ',<br>
                        Le informamos que se está haciendo un seguimiento.<br>
                        <b>ALGUNOS DETALLES</b><br>
                        Asunto: ' . $_POST["asunto"] . '<br>
                        Fecha próximo contacto: ' . $_POST["fechaPC"] . '<br>
                        Encargado próximo contacto: ' . $contacto['usr_nombre'] . '<br>
                        </p>
                        
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
    $sdestinatario = $contacto['usr_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
    $ssubject = "CRM - Seguimiento a clientes"; //ASUNTO DEL MENSAJE 				
    $shtml = $fin; //MENSAJE EN SI			
    $sheader = "From:" . $sfrom . "\nReply-To:" . $sfrom . "\n";
    $sheader = $sheader . "X-Mailer:PHP/" . phpversion() . "\n";
    $sheader = $sheader . "Mime-Version: 1.0\n";
    $sheader = $sheader . "Content-Type: text/html; charset=UTF-8\r\n";
    @mail($sdestinatario, $ssubject, $shtml, $sheader);
}*/
echo '<script type="text/javascript">window.location.href="../clientes-seguimiento-editar.php?id=' . $_POST["id"] . '&msg=2&idTK=' . $_POST["idTK"] . '";</script>';
exit();