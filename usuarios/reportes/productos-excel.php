<?php 
include("../sesion.php");

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=InformeDeProductos_".date("d/m/Y").".xls");

include("../../conexion.php");
require("../funciones-para-el-sistema.php");
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>INFORMES - LISTADO DE PRODUCTOS</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

                            <h2 style="text-align:center;">LISTADO DE PRODUCTOS</h2>
<?php	
$campos = array(
    "prod_id" => "ID",
	"prod_referencia" => "COD.",
    "prod_nombre" => "Nombre",
	"prod_grupo1" => "Grupo 1",
	"prod_categoria" => "Grupo 2",
	"prod_costo" => "Costo COP",
	"prod_costo_dolar" => "Costo USD",
	"prod_utilidad" => "Utilidad",
	"prod_descuento2" => "Utilidad Dealer",
	"prod_descuento_web" => "Dcto. Web",
	"prod_precio" => "Precio lista COP",
	"precioDealer" => "Precio Dealer",
	"precioWeb" => "Precio Web",
	"prod_existencias" => "Existencias",
	"prod_descuento1" => "Dcto. Máximo",
	"precio_usd" => "Precio lista USD",
);
?>
                            <table width="100%" border="1" rules="all" align="center">
							<thead>
							<tr style="height: 40px; background-color: darkblue; color:white;">
								<th>No.</th>
								
								<?php
									$numero =(count($_POST["campos"]));
									if($numero>0){
										$contador=0;
										while($contador<$numero){
								?>
										<th><?=$campos[$_POST["campos"][$contador]];?></th>
								<?php
											$contador++;
										}
									}
								?>
								
							</tr>
							</thead>
							<tbody>
                            <?php
							$filtro = '';	
							$numero =(count($_POST["grupos1"]));
							if($numero>0){
								$filtro .= " AND (prod_grupo1='".$_POST["grupos1"][0]."'";
								$contador=1;
								while($contador<$numero){
									$filtro .= " OR prod_grupo1='".$_POST["grupos1"][$contador]."'";
									$contador++;
								}
								$filtro .= ")";
							}
								
							$filtro2 = '';	
							$numero =(count($_POST["grupos2"]));
							if($numero>0){
								$filtro2 .= " AND (prod_categoria='".$_POST["grupos2"][0]."'";
								$contador=1;
								while($contador<$numero){
									$filtro2 .= " OR prod_categoria='".$_POST["grupos2"][$contador]."'";
									$contador++;
								}
								$filtro2 .= ")";
							}	
								
							$consulta = mysqli_query($conexionBdPrincipal, "SELECT * FROM productos
							INNER JOIN productos_categorias ON catp_id=prod_categoria 
							WHERE prod_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'
							$filtro
							$filtro2
							ORDER BY ".$_POST['orden']." ".$_POST['formaOrden']."
							");
							$no = 1;
							while($res = mysqli_fetch_array($consulta)){
								$grupo1 = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "SELECT * FROM productos_categorias WHERE catp_id='".$res['prod_grupo1']."'
								"));
							?>
							<tr style="height: 20px;">
								<td align="center"><?=$no;?></td>
								
								<?php
									$numero =(count($_POST["campos"]));
									if($numero>0){
										$contador=0;
										while($contador<$numero){
											
											if($_POST["campos"][$contador]=='prod_precio'){
												echo '<td>$'.number_format($res[$_POST["campos"][$contador]],0,",",".").'</td>';
											}
											elseif($_POST["campos"][$contador]=='prod_costo'){
												echo '<td>$'.number_format($res[$_POST["campos"][$contador]],0,",",".").'</td>';
											}
											elseif($_POST["campos"][$contador]=='prod_id'){
												echo '<td align="center">'.$res[$_POST["campos"][$contador]].'</td>';
											}
											elseif($_POST["campos"][$contador]=='prod_grupo1'){
												echo '<td>'.$grupo1['catp_nombre'].'</td>';
											}
											elseif($_POST["campos"][$contador]=='prod_categoria'){
												echo '<td>'.$res['catp_nombre'].'</td>';
											}
											elseif($_POST["campos"][$contador]=='prod_existencias'){
												echo '<td align="center">'.$res[$_POST["campos"][$contador]].'</td>';
											}
											elseif($_POST["campos"][$contador]=='prod_descuento1'){
												echo '<td align="center">'.$res[$_POST["campos"][$contador]].'%</td>';
											}
											elseif($_POST["campos"][$contador]=='prod_utilidad'){
												echo '<td align="center">'.$res[$_POST["campos"][$contador]].'%</td>';
											}
											elseif($_POST["campos"][$contador]=='prod_descuento2'){
												echo '<td align="center">'.$res[$_POST["campos"][$contador]].'%</td>';
											}
											elseif($_POST["campos"][$contador]=='prod_descuento_web'){
												echo '<td align="center">'.$res[$_POST["campos"][$contador]].'%</td>';
											}
											elseif($_POST["campos"][$contador]=='precioDealer'){
												$dcto2 = $res['prod_descuento2']/100;
												$precioDealer = $res['prod_costo'] + ($res['prod_costo']*$dcto2);
												
												echo '<td>$'.number_format($precioDealer,0,",",".").'</td>';
											}
											elseif($_POST["campos"][$contador]=='precioWeb'){
												$dctoWeb = $res['prod_descuento_web']/100;
												$precioWeb = $res['prod_costo'] + ($res['prod_costo']*$dctoWeb);
												
												echo '<td>$'.number_format($precioWeb,0,",",".").'</td>';
											}
											elseif($_POST["campos"][$contador]=='precio_usd'){
												$precioListaUSD = productosPrecioListaUSD($res['prod_utilidad'], $res['prod_costo_dolar']);
												
												echo '<td>$'.number_format($precioListaUSD,0,",",".").'</td>';
											}
											else{
												echo '<td>'.$res[$_POST["campos"][$contador]].'</td>';	
											}	

											$contador++;
										}
									}
								?>
								
							</tr>
								

								
                            <?php $no++;}?>
							</tbody>
							</table>

</body>
</html>