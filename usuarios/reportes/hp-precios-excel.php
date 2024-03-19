<?php include("../sesion.php"); ?>
<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=HISTORAL_PRECIOS_ORION_CRM_" . date("d/m/Y") . ".xls");
include("../../conexion.php");
?>

<?php include("../head.php"); ?>
</head>

<body>
	

<table width="100%" border="1" rules="all" align="center">
		<thead>
			<tr style="height:30px; background-color: blue; color: white;">
				<th>No</th>
				<th>ID</th>
				<th>CODIGO</th>
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
			if($_GET["producto"]!=""){$filtro .= " AND (php_producto='".$_GET["producto"]."')";}
			if($_GET["responsable"]!=""){$filtro .= " AND (php_usuario='".$_GET["responsable"]."')";}	

			if(isset($_GET["desde"]) and $_GET["desde"]!=""){$filtro .= " AND (DATE(php_fecha_cambio) BETWEEN '".$_GET["desde"]."' AND '".$_GET["hasta"]."')";}
			//if(isset($_GET["hasta"]) and $_GET["hasta"]!=""){$filtro .= " AND (php_fecha_cambio<='".$_GET["hasta"]."')";}

			$no = 1;
			$consulta = mysqli_query($conexionBdPrincipal, "SELECT * FROM productos_historial_precios 
							INNER JOIN productos ON prod_id=php_producto AND prod_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'
							INNER JOIN usuarios ON usr_id=php_usuario AND usr_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'
							WHERE php_id=php_id $filtro
							GROUP BY php_producto
							ORDER BY php_id DESC
							");

			while ($res = mysqli_fetch_array($consulta)) {

				$cambios = mysqli_num_rows(mysqli_query($conexionBdPrincipal, "SELECT * FROM productos_historial_precios WHERE php_producto='".$res['prod_id']."'"));

				$precios = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "SELECT * FROM productos_historial_precios 
					WHERE php_producto='".$res['prod_id']."'
					ORDER BY php_id DESC
					LIMIT 0,1
					"));
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
					<td align="center"><?=$cambios;?></td>
					
				</tr>
			<?php $no++;
			} ?>
		</tbody>
	</table>


</body>