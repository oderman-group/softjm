<?php include("../sesion.php"); ?>
<?php include("../../conexion.php"); ?>
<?php $configuracion = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id=1")); ?>
<!DOCTYPE HTML>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>INFORMES - HISTORIAL DE PRECIOS</title>
</head>
<?php
$arrayMeses = array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
?>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

	<h1 style="text-align:center;">INFORMES</h1>
	<h2 style="text-align:center;">HISTORIAL DE PRECIOS</h2>
	<h3 style="text-align:center;"><?=$arrayMeses[ $_POST["mes"] ]." de ".$_POST["agno"];?></h3>
	<div align="center" style="margin-bottom:5px;"><img src="../files/<?= $configuracion['conf_logo']; ?>" height="100" alt="<?= $configuracion['conf_empresa']; ?>"></div>



	<?php if($_POST["mostrar"] == 1){?>
	<p><a href="hp-precios-excel.php?responsable=<?=$_POST["responsable"];?>&producto=<?=$_POST["producto"];?>&desde=<?=$_POST["producto"];?>&hasta=<?=$_POST["hasta"];?>">Exportar a excel</a></p>

	<table width="100%" border="1" rules="all" align="center">
		<thead>
			<tr style="height:30px; background-color: blue; color: white;">
				<th>No</th>
				<th>ID</th>
				<th>CÓDIGO</th>
				<th>Nombre</th>
				<th>Precio anterior</th>
				<th>Precio actual</th>
				<th>Origen</th>
				<th>Fecha cambio</th>
				<th>Responsable</th>
				<th>Cambios</th>
				
			</tr>
		</thead>
		<tbody>
			<?php
			$filtro="";
			if($_POST["producto"]!=""){$filtro .= " AND (php_producto='".$_POST["producto"]."')";}
			if($_POST["responsable"]!=""){$filtro .= " AND (php_usuario='".$_POST["responsable"]."')";}	

			if(isset($_POST["desde"]) and $_POST["desde"]!=""){$filtro .= " AND (DATE(php_fecha_cambio) BETWEEN '".$_POST["desde"]."' AND '".$_POST["hasta"]."')";}
			//if(isset($_POST["hasta"]) and $_POST["hasta"]!=""){$filtro .= " AND (php_fecha_cambio<='".$_POST["hasta"]."')";}

			$no = 1;
			$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_historial_precios 
							INNER JOIN productos ON prod_id=php_producto
							INNER JOIN usuarios ON usr_id=php_usuario
							WHERE php_id=php_id $filtro
							GROUP BY php_producto
							ORDER BY php_id DESC");

			while ($res = mysqli_fetch_array($consulta)) {

				$cambios = mysqli_num_rows(mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_historial_precios WHERE php_producto='".$res['prod_id']."'"));

				$precios = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_historial_precios 
					WHERE php_producto='".$res['prod_id']."'
					ORDER BY php_id DESC
					LIMIT 0,1"));
			?>
				<tr>
					<td align="center"><?= $no; ?></td>
					<td align="center"><?= $res['prod_id']; ?></td>
					<td align="center"><?= $res['prod_referencia']; ?></td>
					<td><?= $res['prod_nombre']; ?></td>
					<td>$<?= number_format($precios['php_precio_anterior'], 0, ",", "."); ?></td>
					<td>$<?= number_format($precios['php_precio_nuevo'], 0, ",", "."); ?></td>
					<td><?= $origenPrecioProducto[$res['php_causa']]; ?></td>
					<td><?= $res['php_fecha_cambio']; ?></td>
					<td><?= $res['usr_nombre']; ?></td>
					<td align="center"><a href="../productos-historial-precios.php?prod=<?= $res['prod_id']; ?>" target="_blank"><?=$cambios;?></a></td>
					
				</tr>
			<?php $no++;
			} ?>
		</tbody>
	</table>

<?php }?>






<?php 
//Mostra combos
if($_POST["mostrar"] == 2){?>

	<?php
	$combos = mysqli_query($conexionBdPrincipal,"SELECT * FROM combos");
	while($combo = mysqli_fetch_array($combos)){
	?>

	<h2><?=$combo['combo_nombre'];?></h2>


		<table width="100%" border="1" rules="all" align="center">
			<thead>
				<tr style="height:30px; background-color: blue; color: white;">
					<th width="2%">No</th>
					<th width="2%">ID</th>
					<th width="6%">CÓDIGO</th>
					<th width="70%">Nombre</th>
					<th width="10%">Precio original</th>
					<th width="10%">Precio actual</th>
					
				</tr>
			</thead>
			<tbody>
				<?php
				$no = 1;
				$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM combos_productos 
								INNER JOIN productos ON prod_id=copp_producto
								WHERE copp_combo='".$combo['combo_id']."'
								GROUP BY copp_producto
								ORDER BY copp_id DESC");

				$totalOrginial = 0;
				$totalActual = 0;

				while ($res = mysqli_fetch_array($consulta)) {

					$colorPrecio = '';
					if($res['copp_precio'] != $res['prod_precio']){
						$colorPrecio = 'gold';
					}

					$totalOrginial += $res['copp_precio'];
					$totalActual += $res['prod_precio'];

				?>
					<tr>
						<td align="center"><?= $no; ?></td>
						<td align="center"><?= $res['prod_id']; ?></td>
						<td align="center"><?= $res['prod_referencia']; ?></td>
						<td><?= $res['prod_nombre']; ?></td>
						<td>$<?= number_format($res['copp_precio'], 0, ",", "."); ?></td>
						<td style="background-color: <?=$colorPrecio;?>;"><a href="../productos-historial-precios.php?prod=<?= $res['prod_id']; ?>" target="_blank">$<?= number_format($res['prod_precio'], 0, ",", "."); ?></a></td>
						
					</tr>
				<?php 
				$no++;
				} 
				?>

				<tfoot>
					<tr style="font-weight: bold; height: 30px; font-size:15px;">
						<td colspan="4" style="text-align:right;">TOTAL:</td>
						<td>$<?= number_format($totalOrginial, 0, ",", "."); ?></td>
						<td>$<?= number_format($totalActual, 0, ",", "."); ?></td>
					</tr>
				</tfoot>
			</tbody>
		</table>

	<?php }?>

<?php }?>	



</body>

</html>