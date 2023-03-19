<?php include("../sesion.php");?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>INFORMES - HISTORIAL DE ACCIONES</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

							<h1 style="text-align:center;">INFORMES</h1>
                            <h2 style="text-align:center;">HISTORIAL DE ACCIONES</h2>
							<div align="center">
								<img src="../files/<?= $configuracion['conf_logo']; ?>" alt="<?= $configuracion['conf_empresa']; ?>" width="120">
							</div>

                            <table width="100%" border="1" rules="all" align="center">
							<thead>
							<tr style="height: 30px; background-color:darkblue; color:white;">
								<th>No</th>
                                <th>Fecha</th>
                                <th>Usuario</th>
								<th>Pagina</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$filtro="";
							if(isset($_POST["usuario"]) and $_POST["usuario"]!=""){
								$filtro .= " AND (hil_usuario='".$_POST["usuario"]."')";
							}
							if(isset($_POST["desde"]) and $_POST["desde"]!=""){
								$filtro .= " AND (hil_fecha>='".$_POST["desde"]."')";
							}
							if(isset($_POST["hasta"]) and $_POST["hasta"]!=""){
								$filtro .= " AND (hil_fecha<='".$_POST["hasta"]."')";
							}
							$consulta = $conexionBdPrincipal->query("SELECT * FROM ".BDADMIN.".historial_acciones
							INNER JOIN ".MAINBD.".usuarios ON usr_id=hil_usuario
							INNER JOIN ".BDADMIN.".paginas ON pag_id=hil_id_pagina
							WHERE hil_id=hil_id ".$filtro."
							ORDER BY ".$_POST["orden"]." ".$_POST["formaOrden"]."");
							$no = 1;
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
							?>
							<tr>
								<td align="center"><?=$no;?></td>
                                <td><?=$res['hil_fecha'];?></td>
                                <td><?=$res['usr_nombre'];?></td>
                                <td><?=$res['pag_nombre'];?></td>
							</tr>
                            <?php $no++;}?>
							</tbody>
							</table>

</body>
</html>