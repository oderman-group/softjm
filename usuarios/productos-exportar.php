<?php include("sesion.php");?>
<?php
//header("Content-Type: application/vnd.ms-excel");
//header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");

header("content-disposition: attachment;filename=productos_".date("d/m/Y h:m:i").".xls");
header("Content-Transfer-Encoding: binary ");

        
include("../conexion.php");

?>
<?php include("head.php");?>
</head>

<body>
<?php
$filtro = "";
if($_GET["grupo1"]){$filtro .=" AND prod_grupo1='".$_GET["grupo1"]."'";}
if($_GET["grupo2"]){$filtro .=" AND prod_categoria='".$_GET["grupo2"]."'";}
if($_GET["marca"]){$filtro .=" AND prod_marca='".$_GET["marca"]."'";}
if($_GET["tipoProductos"]==2){$filtro .=" AND prod_descuento_web>0";}
if($_GET["tipoProductos"]==3){$filtro .=" AND prod_precio_predeterminado=1";}
	
$consulta = mysql_query("SELECT * FROM productos 
INNER JOIN productos_categorias ON catp_id=prod_categoria
WHERE prod_id=prod_id $filtro
",$conexion);
?>
<div align="center">  
<table  width="100%" border="1" rules="all">
    <thead>
    	<tr style="height: 40px; background-color: darkblue; color: floralwhite;">
            <th>No.</th>
			<th>ID</th>
			<th>Código</th>
            <th>Nombre</th>
			
			<th>Cod. G1</th>
			<th>Grupo 1</th>
			<th>Cod. G2</th>
			<th>Grupo 2</th>
			<th>Cod. Marca</th>
			<th>Marca</th>
			
			<th>Existencias</th>
			<th>Costo (COP)</th>
			
			<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15){?>
			<th>Dcto. Max. (%)</th>
			<th>Utilidad Dealer (%)</th>
			<th>Utilidad (%)</th>
			
			<th>P. Fábrica (USD)</th>
			<th>Fletes (USD)</th>
			<th>Aduana (USD)</th>
			<th>Costo (USD)</th>
			<?php }?>
			
			<th>Precio Lista COP</th>
			<th>Precio Lista USD</th>
			
			<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15){?>
			<th>Precio Web</th>
			<?php }?>
			
			<th>Materiales</th>
			<th>Facturas</th>
			
			<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15){?>
			<th>Precio predetermindo</th>
			<?php }?>
			
			
        </tr>
    </thead>
    <tbody>
<?php
$no = 1;
$pdt = array("NO","SI");		
while($res=mysql_fetch_array($consulta))
{
	$grupo1 = mysql_fetch_array(mysql_query("SELECT * FROM productos_categorias WHERE catp_id='".$res['prod_grupo1']."'
	",$conexion));
	
	$marca = mysql_fetch_array(mysql_query("SELECT * FROM marcas WHERE mar_id='".$res['prod_marca']."'
	",$conexion));
	
	$dctoWeb = $res['prod_descuento_web']/100;
	$precioWeb = $res['prod_costo'] + ($res['prod_costo']*$dctoWeb);

	$precioListaUSD = productosPrecioListaUSD($res['prod_utilidad'], $res['prod_costo_dolar']);
	
	$datosReg = mysql_fetch_array(mysql_query("
	SELECT
	(SELECT count(ppmt_id) FROM productos_materiales WHERE ppmt_producto='".$res['prod_id']."'),
	(SELECT count(fpp_id) FROM facturacion_productos WHERE fpp_producto='".$res['prod_id']."')
	",$conexion));
?>    
    	<tr>	
            <td align="center"><?=$no;?></td>
			<td align="center" style="font-weight: bold;"><?=$res['prod_id'];?></td>
            <td align="center"><?=$res['prod_referencia'];?></td>
			<td><?=$res['prod_nombre'];?></td>
			
			<td align="center"><?=$grupo1['catp_id'];?></td>
            <td><?=$grupo1['catp_nombre'];?></td>
			<td align="center"><?=$res['catp_id'];?></td>
            <td><?=$res['catp_nombre'];?></td>
			<td align="center"><?=$marca['mar_id'];?></td>
            <td><?=$marca['mar_nombre'];?></td>
			
			<td align="center"><?=$res['prod_existencias'];?></td>
			<td><?=$res['prod_costo'];?></td>
			
			<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15){?>
			<td align="center"><?=$res['prod_descuento1'];?></td>
			<td align="center"><?=$res['prod_descuento2'];?></td>
			<td><?=$res['prod_utilidad'];?></td>
			
			<td><?=$res['prod_precio_fabrica'];?></td>
			<td><?=$res['prod_flete'];?></td>
			<td><?=$res['prod_aduana'];?></td>
			<td><?=$res['prod_costo_dolar'];?></td>
			<?php }?>
			
			<td>$<?=$res['prod_precio'];?></td>
			<td><?=number_format($precioListaUSD,0,",",".");?></td>
			
			<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15){?>
			<td>$<?=number_format($precioWeb,0,",",".");?></td>
			<?php }?>
			
			<td align="center"><?=$datosReg[0];?></td>
			<td align="center"><?=$datosReg[1];?></td>
			
			<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15){?>
			<td><?=$pdt[$res['prod_precio_predeterminado']];?></td>
			<?php }?>

        </tr>   

<?php
 $no ++;
}
?>        
    </tbody>
</table>

	
</body>