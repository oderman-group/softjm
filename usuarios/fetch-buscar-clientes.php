<?php
include("sesion.php");
include("includes/head.php");
$filtro = "";
$dpto="";

if (isset($_GET["buscar"]) and $_GET["buscar"] != "") {
	$filtro .= " AND (cli_usuario LIKE '%" . $_GET["buscar"] . "%' OR cli_nombre LIKE '%" . $_GET["buscar"] . "%')"; 
	$inicio=$_GET["inicio"];
	$limite=$_GET["limite"];
	
}
$tipoDoc="";
if (isset($_GET["tipoDoc"]) and is_numeric($_GET["tipoDoc"])) {
	$filtro .= " AND cli_tipo_documento='" . $_GET["tipoDoc"] . "'";
}

$filtroGrupos = '';
if (isset($_GET["grupo"]) and is_numeric($_GET["grupo"])) {
	$filtroGrupos .= "LEFT JOIN clientes_categorias ON cpcat_cliente=cli_id AND cpcat_categoria='" . $_GET["grupo"] . "'";
}
$dpto="";
if (isset($_GET["dpto"]) and $_GET["dpto"] != "") {
	$consulta = $conexionBdPrincipal->query("SELECT * FROM " . MAINBD . ".clientes
								LEFT JOIN " . BDADMIN . ".localidad_ciudades ON ciu_id=cli_ciudad
								INNER JOIN " . BDADMIN . ".localidad_departamentos ON dep_id=ciu_departamento AND dep_id='" . $_GET["dpto"] . "'
								$filtroGrupos
								WHERE cli_id=cli_id " . $filtro . " AND cli_id_empresa='" . $idEmpresa . "'
								LIMIT $inicio, $limite
								");
								
								
} else {
	$sql="SELECT * FROM " . MAINBD . ".clientes
	LEFT JOIN " . BDADMIN . ".localidad_ciudades ON ciu_id=cli_ciudad
	INNER JOIN " . BDADMIN . ".localidad_departamentos ON dep_id=ciu_departamento
	$filtroGrupos
	WHERE cli_id=cli_id " . $filtro . " AND cli_id_empresa='" . $idEmpresa . "'	LIMIT $inicio, $limite";

	$consulta = $conexionBdPrincipal->query($sql);
	
}
$no = 1;
while ($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {

	$estadoSesion = 'gris.jpg';
	if ($res['cli_sesion'] == 1) {
		$estadoSesion = 'verde.jpg';
	}

	if (isset($_GET["pap"]) and $res['cli_papelera'] == 1 and $_GET["pap"] != 1) {
		continue;
	}

	$fondoPapelera = 'none';
	$titleEstado = '';
	if ($res['cli_estado_mercadeo'] == 6 or $res['cli_papelera'] == 1) {
		$fondoPapelera = 'tomato';
		$titleEstado = 'Papelera - ' . $res['cli_estado_mercadeo_fecha'];
	}

	if ($res['cli_estado_mercadeo'] == 2) {
		$fondoPapelera = 'goldenrod';
		$titleEstado = 'Número equivocado - ' . $res['cli_estado_mercadeo_fecha'];
	}

	if ($res['cli_estado_mercadeo'] == 5) {
		$fondoPapelera = 'aqua';
		$titleEstado = 'Actualizado - ' . $res['cli_estado_mercadeo_fecha'];
	}

	if (!Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
		$consultaNumZ = $conexionBdPrincipal->query("SELECT * FROM zonas_usuarios 
									WHERE zpu_usuario='" . $_SESSION["id"] . "' AND zpu_zona='" . $res['cli_zona'] . "'");
		$numZ = $consultaNumZ->num_rows;

		$consultaNumCliente = $conexionBdPrincipal->query("SELECT * FROM clientes_usuarios 
									WHERE cliu_usuario='" . $_SESSION["id"] . "' AND cliu_cliente='" . $res['cli_id'] . "'");
		$numCliente = $consultaNumCliente->num_rows;

		if ($numZ == 0 and $numCliente == 0) continue;
	}

	switch ($res['cli_categoria']) {
		case 1:
			$categ = 'Prospecto';
			$etiquetaC = 'warning';
			$fondoColorCat = '';
			break;
		case 2:
			$categ = 'Cliente';
			$etiquetaC = 'info';
			$fondoColorCat = '';
			break;
		case 3:
			$categ = 'Dealer';
			$etiquetaC = 'info';
			$fondoColorCat = 'aquamarine';
			break;
	}

	$consultaNumeros = $conexionBdPrincipal->query("
								SELECT
								(SELECT count(tik_id) FROM clientes_tikets WHERE tik_cliente='" . $res['cli_id'] . "'),
								(SELECT count(cseg_id) FROM cliente_seguimiento 
								INNER JOIN clientes_tikets ON tik_id=cseg_tiket 
								WHERE cseg_cliente='" . $res['cli_id'] . "'),
								(SELECT count(sucu_id) FROM sucursales WHERE sucu_cliente_principal='" . $res['cli_id'] . "'),
								(SELECT count(cont_id) FROM contactos WHERE cont_cliente_principal='" . $res['cli_id'] . "'),
								(SELECT count(fact_id) FROM facturacion WHERE fact_cliente='" . $res['cli_id'] . "'),
								(SELECT count(rem_id) FROM remisiones WHERE rem_cliente='" . $res['cli_id'] . "')
								");

	$numeros = mysqli_fetch_array($consultaNumeros, MYSQLI_BOTH);

	$color1 = '#FFF';
	$color2 = '#FFF';
	$color3 = '#FFF';
	$color4 = '#FFF';
	$color5 = '#FFF';
	$color6 = '#FFF';
	if ($numeros[0] == 0) {
		$color1 = '#FFF090';
	}
	if ($numeros[1] == 0) {
		$color2 = '#FFF090';
	}
	if ($numeros[2] == 0) {
		$color3 = '#FFF090';
	}
	if ($numeros[3] == 0) {
		$color4 = '#FFF090';
	}
	if ($numeros[4] == 0) {
		$color5 = '#FFF090';
	}
	if ($numeros[5] == 0) {
		$color6 = '#FFF090';
	}

?>
	<tr title="<?= $titleEstado; ?>">
		<td style="background-color: <?= $fondoPapelera; ?>;"><?php if ($res['cli_retirado'] == 1) {
																	echo '<span style="color:red;"><strike>(R) ' . $no . '</strike></span>';
																} else {
																	echo $no;
																} ?></td>
		<td style="background-color: <?= $fondoPapelera; ?>;"><?= $res['ciu_nombre'] . ", " . $res['dep_nombre'] . " (03" . $res['dep_indicativo'] . ")"; ?></td>



		<td style="background-color: <?= $fondoColorCat; ?>;">
			<?php echo "<b>Tipo</b>:" . $tipoDocumento[$res['cli_tipo_documento']] . " | "; ?>
			<?php echo "<b>Documento</b>:" . $res['cli_usuario']; ?> | <?php echo "<b>Categoría:</b> " . $categ; ?><br>
			<?php echo '<span style="font-size:16px;">' . $res['cli_nombre']; ?></span>
			<?php if ($res['cli_telefono'] != "") echo "<br><b>Tel:</b> " . $res['cli_telefono']; ?>
			<?php if ($res['cli_celular'] != "") echo "<br><b>Cel:</b> " . $res['cli_celular']; ?>
			<?php if ($res['cli_email'] != "") echo " | <b>Email:</b> " . $res['cli_email']; ?>

			<h4 style="margin-top:5px;">
				<?php if (Modulos::validarRol([11], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
					<a href="clientes-editar.php?id=<?= $res[0]; ?>" data-toggle="tooltip" title="Editar" target="_blank"><i class="icon-edit"></i></a>&nbsp;
				<?php } ?>
				<?php if (Modulos::validarRol([55], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
					<a href="bd_delete/clientes-eliminar.php?id=<?= $res[0]; ?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>&nbsp;
				<?php } ?>
				<?php if (Modulos::validarRol([83], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
					<a href="clientes-sucursales.php?cte=<?= $res[0]; ?>&emg=1" data-toggle="tooltip" title="Sucursales" target="new"><i class="icon-home"></i></a>&nbsp;
				<?php } ?>
				<?php if (Modulos::validarRol([44], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
					<a href="clientes-contactos.php?cte=<?= $res[0]; ?>&emg=1" data-toggle="tooltip" title="Contactos" target="new"><i class="icon-group"></i></a>&nbsp;
				<?php } ?>
				<?php if (Modulos::validarRol([88], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
					<a href="clientes-tikets.php?cte=<?= $res[0]; ?>&emg=1" data-toggle="tooltip" title="Tikets de seguimiento" target="new"><i class="icon-list-ol"></i></a>&nbsp;
				<?php } ?>
				<?php if (Modulos::validarRol([12], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
					<a href="clientes-seguimiento.php?cte=<?= $res[0]; ?>&emg=1" data-toggle="tooltip" title="Seguimiento de clientes" target="new"><i class="icon-list-alt"></i></a>&nbsp;
				<?php } ?>
				<?php if (Modulos::validarRol([259], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
					<a href="facturacion.php?cte=<?= $res[0]; ?>&emg=1" data-toggle="tooltip" title="Facturación" target="new"><i class="icon-money"></i></a>&nbsp;
				<?php } ?>
				<?php if (Modulos::validarRol([110], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
					<a href="enviar-portafolios.php?cte=<?= $res[0]; ?>" data-toggle="tooltip" title="Enviar portafolios" target="_blank"><i class="icon-list-ul"></i></a>&nbsp;
				<?php } ?>
			</h4>
		</td>
		<?php
		$valoresClientes = [
			["url" => "clientes-tikets.php?cte=" . $res['cli_id'], "id" => 88, "color" => $color1, "numero" => $numeros[0]],
			["url" => "clientes-seguimiento.php?cte=" . $res['cli_id'], "id" => 88, "color" => $color2, "numero" => $numeros[1]],
			["url" => "clientes-sucursales.php?cte=" . $res['cli_id'], "id" => 88, "color" => $color3, "numero" => $numeros[2]],
			["url" => "clientes-contactos.php?cte=" . $res['cli_id'], "id" => 88, "color" => $color4, "numero" => $numeros[3]],
			["url" => "facturacion.php?cte=" . $res['cli_id'], "id" => 88, "color" => $color5, "numero" => $numeros[4]],
			["url" => "../v2.0/usuarios/empresa/lab-remisiones.php?cte=" . $res['cli_id'], "id" => 88, "color" => $color6, "numero" => $numeros[5]]
		];
		?>
		<?php foreach ($valoresClientes as $pagina) { ?>
			<?php if (Modulos::validarRol([$pagina['id']], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
				<td align="center" style="background:<?= $pagina['color'] ?>;"><a href="<?= $pagina['url'] ?>" target="_blank"><?= $pagina['numero'] ?></a></td>
			<?php } ?>
		<?php } ?>
		<td><img src="files/<?= $estadoSesion; ?>"><br><?= $res['cli_ultimo_ingreso']; ?></td>
	</tr>
<?php $no++;
} ?>

