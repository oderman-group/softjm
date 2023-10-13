<?php   
require_once("../sesion.php");

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=clientes_" . date("d/m/Y") . ".xls");

$idPagina = 264;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
?>
<!DOCTYPE html>
<html lang="en">

<?php
include(RUTA_PROYECTO."/usuarios/head.php");
?>
</head>

<body>
	<?php
    if (!empty($_GET["dpto"])) {
        $consulta = $conexionBdPrincipal->query("SELECT * FROM clientes
        INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
        INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento AND dep_id='" . $_GET["dpto"] . "'");
    } else {
        $consulta = $conexionBdPrincipal->query("SELECT * FROM clientes
        INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
        INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento");
    }
?>

<div align="center">
			<table width="100%" border="1" rules="all">
				<thead>
					<tr>
						<th colspan="8" style="background:#060; color:#FFF;">CLIENTES/EMPRESAS</th>
					</tr>
					<tr>
						<th>No</th>
						<th>Nombre</th>
						<th>Email</th>
						<th>Teléfono</th>
						<th>Ciudad</th>
						<th>Departamento</th>
						<th>Categoría</th>
						<th>Referencia</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$conta = 1;
					while ($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
						if ($res['cli_papelera'] == 1) {
							continue;
						}

						switch ($res['cli_categoria']) {
							case 1:
								$categ = 'Prospecto';
								break;
							case 2:
								$categ = 'Cliente';
								break;
						}

						if ($datosUsuarioActual[3] != 1) {
                            $consultaNumZ = $conexionBdPrincipal->query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='" . $_SESSION["id"] . "' AND zpu_zona='" . $res['cli_zona'] . "'");
							$numZ = $consultaNumZ->num_rows;
							if ($numZ == 0) continue;
						}
					?>
						<tr>
							<td align="center"><?= $conta; ?></td>
							<td><?= $res['cli_nombre']; ?></td>
							<td><?= $res['cli_email']; ?></td>
							<td><?= $res['cli_telefono']; ?></td>
							<td><?= $res['ciu_nombre']; ?></td>
							<td><?= $res['dep_nombre']; ?></td>
							<td><?= $categ; ?></td>
							<td><?= $res['cli_referencia']; ?></td>
						</tr>
							<?php
								$i = 1;
								$contacto = $conexionBdPrincipal->query("SELECT * FROM contactos
								WHERE cont_cliente_principal='".$res['cli_id']."'");
								$contNum = $contacto->num_rows;
								if($contNum>0){
							?>
								<tr style="background-color: dimgray; height: 20px; font-weight: bold; color:white;">
									<td align="center" colspan="8">CONTACTOS</td>
								</tr>
							<?php
								}
								while($cont = mysqli_fetch_array($contacto, MYSQLI_BOTH)){
							?>
							<tr style="background-color: gainsboro;">
								<td align="center"><?=$i;?></td>
                                <td colspan="1"><?=$cont['cont_nombre'];?></td>
                                <td colspan="1"><?=$cont['cont_email'];?></td>
								<td colspan="1"><?=$cont['cont_telefono']." - ".$cont['cont_celular'];?></td>
								<td colspan="1"><?= $res['ciu_nombre']; ?></td>
								<td colspan="1"><?= $res['dep_nombre']; ?></td>
								<td colspan="1"><?=$cont['cont_area'];?></td>
								<td colspan="1"></td>
							</tr>	
							<?php
									$i++;
								}
							?>

					<?php
						$conta++;
					}
					?>
				</tbody>
			</table>
    </body>

</html>

<?php
include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");