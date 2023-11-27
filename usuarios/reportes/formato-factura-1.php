<?php include("../sesion.php");?>
<?php
$configuracion = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id=1"), MYSQLI_BOTH);

$factura = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "SELECT * FROM facturacion
                        INNER JOIN clientes ON cli_id=fact_cliente
                        INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
                        WHERE fact_id='" . $_GET["id"] . "'"), MYSQLI_BOTH);
						switch($factura['fact_estado']){
							case 1: $estadoF = 'Pagada'; break;
							case 2: $estadoF = 'Por pagar'; break;
							case 3: $estadoF = 'Anulada'; break;
						}
$consulta=$conexionBdAdmin->query("SELECT * FROM documentos_configuracion 
                                    WHERE dconf_id_empresa= '".$idEmpresa."' 
                                    AND dconf_id_documento= '".ID_DOC_FACTURA."';");
$configuracionDoc = mysqli_fetch_array($consulta, MYSQLI_BOTH);
$fontLink = "https://fonts.googleapis.com/css2?family=" . str_replace(' ', '+', $configuracionDoc["dconf_estilo_letra"]) . "&display=swap";
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Factura de venta <?=$factura['fact_id'];?> (<?=$factura['fact_fecha_real'];?>) - <?=$factura['cli_nombre'];?></title>
<link rel="stylesheet" href="<?php echo $fontLink;?>">
</head>
<body style="font-family:<?php echo $configuracionDoc['dconf_estilo_letra'] ?? 'Verdana, sans-serif'; ?>; font-size:<?php echo $configuracionDoc['dconf_tamano_letra'] ?? '11'; ?>px;">
<?php
$colorFondo = $configuracionDoc['dconf_estilo'] ?? '#ffca05';
$colorLetra = '#000';
?>

                            <div style="padding:10px; background:#FFFFFF; border:thin; border-style:double; border-radius:5px;">
                            <table width="100%" border="0" align="center">
								<tr>
                                	<td rowspan="2" style="width:20%;"><img src="../files/<?=$configuracion['conf_logo'];?>" width="150"></td>
                                    <td rowspan="2" align="center" style="width:50%">
										<span style="font-size:16px;"><?=strtoupper($configuracion['conf_empresa']);?></span><br>
                                        NIT <?=$configuracion['conf_nit'];?><br>
                                        <?=$configuracion['conf_telefono'];?><br>
										<?=$configuracion['conf_email'];?><br>
                                        <?=$configuracion['conf_web'];?><br>
                                    </td>
                                    <td colspan="2" style="height:20px; background:<?=$colorFondo;?>; color:<?=$colorLetra;?>; padding:5px; width:30%;"><b>FACTURA NO.</b> &nbsp;<span style="font-size:16px;"><?=$factura['fact_id'];?></span></td>
                                </tr>
                                
                                <tr>
                                	<td colspan="4" style="height:50px; padding:5px;">
                                    R&eacute;gimen simplificado<br>
                                	Factura Original
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                    <td style="height:20px; background:<?=$colorFondo;?>; color:<?=$colorLetra;?>; padding:5px;"><b>FECHA:</b> <?=$factura['fact_fecha_real'];?></td>
                                </tr>
                                
							</table>
                            </div>
                            
                            <p>&nbsp;</p>
                            
                            <div style="padding:10px; background:#FFF; color:#000; border:thin; border-style:double; border-radius:5px;">
                            <table width="100%" border="0" align="center" rules="none">
								<tr style="height:30px;">
                                	<td align="right" width="15%"><b>CLIENTE:</b></td>
                                    <td align="left"><?=$factura['cli_nombre'];?></td>
                                    <td align="right"><b>EMAIL:</b></td>
                                    <td><?=$factura['cli_email'];?></td>
                                </tr>
                                
                                <tr style="height:30px;">
                                	<td align="right"><b>NIT:</b></td>
                                    <td><?=$factura['cli_usuario'];?></td>
                                    <td align="right"><b>TELÉFONO:</b></td>
                                    <td><?=$factura['cli_telefono'];?></td>
                                </tr>
                                
                                <tr style="height:30px;">
                                	<td align="right"><b>CIUDAD:</b></td>
                                    <td><?=$factura['ciu_nombre'];?></td>
                                    <td align="right"><b>DIRECCIÓN:</b></td>
                                    <td><?=$factura['cli_direccion'];?></td>
                                </tr>
							</table>
                            </div>
                            
                            <p>&nbsp;</p>
                            
                            <div style="padding:10px; background:#FFF; color:#000; border:thin; border-style:double; border-radius:5px;">
                            <p><b>DESCRIPCIÓN GENERAL</b></p>
							<?=$factura['fact_descripcion'];?>
                            </div>
                            
                            <p>&nbsp;</p>
                            
                            <div style="padding:10px; background:#FFF; color:#000; border:thin; border-style:double; border-radius:5px;">
                            <table width="100%" border="0" align="center" rules="none">
								<tr style="height:30px; font-weight:bold;">
                                	<td>#</td>
                                    <td>Productos</td>
                                </tr>
                                <?php
								$n=1;
								$items = mysqli_query($conexionBdPrincipal,"SELECT * FROM facturacion_productos
								INNER JOIN productos ON prod_id=fpp_producto
								WHERE fpp_factura='".$_GET["id"]."'");
								while($it = mysqli_fetch_array($items)){
								?>
                                <tr style="height:30px;">
                                	<td><?=$n;?></td>
                                    <td><?=$it['prod_nombre'];?></td>
                                </tr>
                                <?php $n++; }?>
							</table>
                            </div>
                            
                            <p align="right" style="height:20px; padding:5px; font-size:22px;"><b>TOTAL NETO:</b> $<?=number_format($factura['fact_valor'],0,",",".");?> (<?=$estadoF;?>)</p>
                            
                            <div style="padding:10px; background:#FFFFFF; border:thin; border-style:double; border-radius:5px;">
                            
                            <table width="100%" border="0" align="center">
								<tr>
                                	<td>
                                    	Esta factura se asimila en todos sus efectos a una letra de cambio de conformidad con el Art. 774 del
código del comercio. Autorizo que en caso de incumplimiento de esta obligación sea reportado a las
centrales de riesgo, se cobraran intereses por mora.
                                    </td>
                                </tr>
                                
							</table>
                            </div>
                            
                            <p>&nbsp;</p>
                            
                            <div style="padding:10px; background:<?=$colorFondo;?>; color:#030303; border:thin; border-style:double; border-radius:5px;">
                            
                            <table width="100%" border="0" align="center">
								<tr>
                                	<td>
                                    	<p><b>FORMAS DE PAGO DISPONIBLES</b></p>

<p><b>Bancolombia:</b> Cuenta de Ahorros Bancolombia <b># 431 565 88 254</b> a nombre de <b>JHON ODERMAN MEJIA</b> Identificado con C&eacute;dula 1.051.820.890.<br>
Puede hacer el pago mediante una consignaci&oacute;n o transferencia bancaria.</p>

<p> <b>Efecty y Gana:</b> Puedes hacer el giro a nombre de Jhon Oderman Mejía, con número de cédula 1.051.820.890.</p>

<p> <b>Sitio Web:</b> Puede hacer su pago de manera virtual en nuestro sitio web www.oderman.com.co, opción pago de facturas, ingresa el número de la factura (<b><?=$factura['fact_id'];?></b>) y continúa con el proceso.</p>

<p align="center" style="font-size:8px;"> <b>SI AÚN TIENE DUDAS CON RESPECTO A ESTA FACTURA O AL PROCESO DE PAGO AGRADECEMOS COMUNICARSE CON NOSOTROS.<br>
RECUERDE QUE EL NO PAGO A TIEMPO DE LAS FACTURAS PUEDE OCASIONAR SUSPENSIÓN EN LOS SERVICIOS PRESTADOS O COBRO ADICIONAL POR MORA.</b></p>
                                    </td>
                                </tr>
                                
							</table>
                            </div>
                            
                            <div style="margin-top:30px;" align="center">_____________________________________<br>ACEPTADA, FIRMA Y/O SELLO Y FECHA</div>

</body>
<script type="application/javascript">
	print();
</script>
</html>