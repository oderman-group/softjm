<?php include("sesion.php"); ?>
<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=INFORMES_ORION_CRM_" . date("d/m/Y") . ".xls");
?>
</head>

<body>
		<?php
		if (isset($_GET["exp"]) and $_GET["exp"] == 2) {
			$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones
INNER JOIN clientes ON cli_id=rem_cliente WHERE rem_id_empresa='".$idEmpresa."'");
		?>
			<div align="center">
				<table width="100%" border="1" rules="all">
					<thead>
						<tr>
							<th colspan="4">&nbsp;</th>
							<th colspan="3">Vencimiento</th>
							<th colspan="7">&nbsp;</th>
						</tr>

						<tr>
							<th>No</th>
							<th>ID</th>
							<th>Estado</th>
							<th>Fecha revisión</th>
							<th>Dia</th>
							<th>Mes</th>
							<th>Año</th>
							<th>Equipo</th>
							<th>Marca</th>
							<th>Referencia</th>
							<th>Serial</th>
							<th>Cliente</th>
							<th>Teléfono</th>
							<th>Email</th>
							<th>Ingresos</th>
							<th>Último ingreso</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$conta = 1;
						
						while ($res = mysqli_fetch_array($consulta)) {
							
							$camposRemision = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT 
							DAY(rem_fecha), 
							MONTH(rem_fecha), 
							YEAR(rem_fecha),
							DAY(DATE_ADD(rem_fecha, INTERVAL '" . $res['rem_tiempo_certificado'] . "' MONTH)), 
							MONTH(DATE_ADD(rem_fecha, INTERVAL '" . $res['rem_tiempo_certificado'] . "' MONTH)), 
							YEAR(DATE_ADD(rem_fecha, INTERVAL '" . $res['rem_tiempo_certificado'] . "' MONTH))
							FROM remisiones 
							WHERE rem_id='" . $res['rem_id'] . "' AND rem_id_empresa='".$idEmpresa."'"));

							$cantidadIngresos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT COUNT(*), MAX(rem_id) FROM remisiones WHERE rem_serial='" . $res['rem_serial'] . "' AND rem_serial!=0 AND rem_id_empresa='".$idEmpresa."'"));
							$cantIngresos = $cantidadIngresos[0];
							if ($cantidadIngresos[0] == 0) $cantIngresos = 1;

							$ultimoIngreso = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT rem_fecha FROM remisiones WHERE rem_id='" . $cantidadIngresos[1] . "' AND rem_id_empresa='".$idEmpresa."'"));
						?>
							<tr>
								<td align="center"><?= $conta; ?></td>
								<td>C<?= $res['rem_id']; ?></td>
								<td><?= $estadosCertificados[$res['rem_estado_certificado']]; ?></td>
								<td><?= $res['rem_fecha']; ?></td>
								<td><?= $camposRemision[3]; ?></td>
								<td><?= $meses[$camposRemision[4]]; ?></td>
								<td><?= $camposRemision[5]; ?></td>
								<td><?= $res['rem_equipo']; ?></td>
								<td><?= $res['rem_marca']; ?></td>
								<td><?= $res['rem_referencia']; ?></td>
								<td><?= $res['rem_serial']; ?></td>
								<td><?= $res['cli_nombre']; ?></td>
								<td><?= $res['cli_telefono']; ?></td>
								<td><?= $res['cli_email']; ?></td>
								<td align="center"><?= $cantIngresos; ?></td>
								<td><?= $ultimoIngreso[0]; ?></td>
							</tr>

						<?php
							$conta++;
						}
						?>
					</tbody>
				</table>
			<?php } ?>

			<?php
			if (isset($_GET["exp"]) and $_GET["exp"] == 3) {
				$filtro = "";
				if (isset($_GET["usuarioR"]) and $_GET["usuarioR"] != "") {
					$filtro .= " AND (tik_usuario_responsable='" . $_GET["usuarioR"] . "')";
				}
				if (isset($_GET["cliente"]) and $_GET["cliente"] != "") {
					$filtro .= " AND (tik_cliente='" . $_GET["cliente"] . "')";
				}
				if (isset($_GET["tipoTK"]) and $_GET["tipoTK"] != "") {
					$filtro .= " AND (tik_tipo_tiket='" . $_GET["tipoTK"] . "')";
				}
				if (isset($_GET["canal"]) and $_GET["canal"] != "") {
					$filtro .= " AND (tik_canal='" . $_GET["canal"] . "')";
				}
				if (isset($_GET["desde"]) and $_GET["desde"] != "") {
					$filtro .= " AND (tik_fecha_creacion>='" . $_GET["desde"] . "')";
				}
				if (isset($_GET["hasta"]) and $_GET["hasta"] != "") {
					$filtro .= " AND (tik_fecha_creacion<='" . $_GET["hasta"] . "')";
				}

				$consulta = mysqli_query($conexionBdPrincipal,
					"SELECT * FROM clientes_tikets
INNER JOIN clientes ON cli_id=tik_cliente
INNER JOIN usuarios ON usr_id=tik_usuario_responsable
WHERE tik_id=tik_id " . $filtro . "
ORDER BY " . $_GET["orden"] . " " . $_GET["formaOrden"]);
			?>
				<div align="center">
					<table width="100%" border="1" rules="all">
						<thead>


							<tr style="height: 40px; background-color: darkblue; color:white;">
								<th>No</th>
								<th>Tipo</th>
								<th>Fecha de contacto</th>
								<th>Cliente</th>
								<th>Resposable</th>
								<th>Canal</th>
								<th>Asunto</th>
								<th>Completados</th>
								<th>Pendientes</th>
								<th>Estado</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$conta = 1;
							$canales = array("", "Facebook", "WhatsApp", "Fijo", "Celular", "Personal", "Skype", "Otro");
							while ($res = mysqli_fetch_array($consulta)) {
								$encargado = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='" . $res['cseg_usuario_encargado'] . "' AND usr_id_empresa='".$idEmpresa."'"));
								switch ($res['tik_tipo_tiket']) {
									case 1:
										$tipoS = 'Comercial';
										break;
									case 2:
										$tipoS = 'Soporte técnico';
										break;
									case 3:
										$tipoS = 'Soporte operativo';
										break;
								}
								switch ($res['tik_estado']) {
									case 1:
										$estado = 'Abierto';
										break;
									case 2:
										$estado = 'Cerrado';
										break;
								}

								$seguimientos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"
								SELECT
								(SELECT COUNT(cseg_id) FROM cliente_seguimiento WHERE cseg_tiket='" . $res['tik_id'] . "' AND cseg_realizado=1),
								(SELECT COUNT(cseg_id) FROM cliente_seguimiento WHERE cseg_tiket='" . $res['tik_id'] . "' AND cseg_realizado IS NULL)
								"));
							?>
								<tr>
									<td align="center"><?= $conta; ?></td>
									<td><?= $tipoS; ?></td>
									<td><?= $res['tik_fecha_creacion']; ?></td>
									<td><?= $res['cli_nombre']; ?></td>
									<td><?= strtoupper($res['usr_nombre']); ?></td>
									<td><?= $canales[$res['tik_canal']]; ?></td>
									<td><?= "<b>" . $res['tik_id'] . "</b> - " . $res['tik_asunto_principal']; ?></td>
									<td align="center"><?= $seguimientos[0]; ?></td>
									<td align="center"><?= $seguimientos[1]; ?></td>
									<td><?= $estado; ?></td>
								</tr>

							<?php
								$conta++;
							}
							?>
						</tbody>
					</table>
				<?php } ?>

				<?php
				if (isset($_GET["exp"]) and $_GET["exp"] == 4) {
					$filtro = "";
					if (isset($_GET["usuarioR"]) and $_GET["usuarioR"] != "") {
						$filtro .= " AND (tik_usuario_responsable='" . $_GET["usuarioR"] . "')";
					}
					if (isset($_GET["cliente"]) and $_GET["cliente"] != "") {
						$filtro .= " AND (tik_cliente='" . $_GET["cliente"] . "')";
					}
					if (isset($_GET["tipoTK"]) and $_GET["tipoTK"] != "") {
						$filtro .= " AND (tik_tipo_tiket='" . $_GET["tipoTK"] . "')";
					}
					if (isset($_GET["canal"]) and $_GET["canal"] != "") {
						$filtro .= " AND (tik_canal='" . $_GET["canal"] . "')";
					}
					if (isset($_GET["desde"]) and $_GET["desde"] != "") {
						$filtro .= " AND (tik_fecha_creacion>='" . $_GET["desde"] . "')";
					}
					if (isset($_GET["hasta"]) and $_GET["hasta"] != "") {
						$filtro .= " AND (tik_fecha_creacion<='" . $_GET["hasta"] . "')";
					}

					$consulta = mysqli_query($conexionBdPrincipal,
						"SELECT * FROM clientes_tikets
INNER JOIN clientes ON cli_id=tik_cliente
INNER JOIN usuarios ON usr_id=tik_usuario_responsable
WHERE tik_id=tik_id " . $filtro . "
ORDER BY " . $_GET["orden"] . " " . $_GET["formaOrden"]);
				?>
					<div align="center">
						<table width="100%" border="1" rules="all">
							<thead>


								<tr style="height: 40px; background-color: darkblue; color:white;">
									<th>No</th>
									<th>TICKET</th>
									<th>Fecha de contacto</th>
									<th>Ciudad, Departamento</th>
									<th>Cliente</th>
									<th>Resposable</th>
									<th>Cotización</th>
									<th>CZ</th>
									<th>VT</th>
									<th>DT</th>
									<th>Observación</th>
									<th>Próximo contacto</th>
									<th>Encargado</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$filtro = "";
								if (isset($_GET["usuarioR"]) and $_GET["usuarioR"] != "") {
									$filtro .= " AND (cseg_usuario_responsable='" . $_GET["usuarioR"] . "')";
								}

								if (isset($_GET["cliente"]) and $_GET["cliente"] != "") {
									$filtro .= " AND (cseg_cliente='" . $_GET["cliente"] . "')";
								}
								if (isset($_GET["tipoS"]) and $_GET["tipoS"] != "") {
									$filtro .= " AND (cseg_tipo='" . $_GET["tipoS"] . "')";
								}
								if (isset($_GET["cotizacion"]) and $_GET["cotizacion"] != "") {
									if ($_GET["cotizacion"] == 1) $filtro .= " AND (cseg_cotizacion!='')";
									elseif ($_GET["cotizacion"] == 2) $filtro .= " AND (cseg_cotizacion='')";
									else $filtro .= "";
								}
								if (isset($_GET["desde"]) and $_GET["desde"] != "") {
									$filtro .= " AND (cseg_fecha_contacto>='" . $_GET["desde"] . "')";
								}
								if (isset($_GET["hasta"]) and $_GET["hasta"] != "") {
									$filtro .= " AND (cseg_fecha_contacto<='" . $_GET["hasta"] . "')";
								}

								if (isset($_GET["venta"]) and $_GET["venta"] != "") {
									$filtro .= " AND (cseg_vendio='" . $_GET["venta"] . "')";
								}
								if (isset($_GET["datos"]) and $_GET["datos"] != "") {
									$filtro .= " AND (cseg_consiguio_datos='" . $_GET["datos"] . "')";
								}

								$filtro2 = '';
								if ($_GET["departamento"] != "") {
									$filtro2 .= " AND dep_id='" . $_POST["departamento"] . "'";
								}

								if (isset($_GET["desde"]) and $_GET["desde"] != "") {
									$filtro .= " AND (cseg_fecha_contacto>='" . $_GET["desde"] . "')";
								}
								if (isset($_GET["hasta"]) and $_GET["hasta"] != "") {
									$filtro .= " AND (cseg_fecha_contacto<='" . $_GET["hasta"] . "')";
								}

								$filtroCli = '';
								if ($_GET["ciudad"] != "") {
									$filtroCli .= " AND cli_ciudad='" . $_GET["ciudad"] . "'";
								}
								if ($_GET["tipoDocumento"] != "") {
									$filtroCli .= " AND cli_tipo_documento='" . $_GET["tipoDocumento"] . "'";
								}

								$consulta = mysqli_query($conexionBdPrincipal,
									"SELECT * FROM cliente_seguimiento
							INNER JOIN clientes_tikets ON tik_id=cseg_tiket
							INNER JOIN clientes ON cli_id=cseg_cliente $filtroCli
							INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
							INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento $filtro2
							INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
							WHERE cseg_id=cseg_id " . $filtro . "
							ORDER BY " . $_GET["orden"] . " " . $_GET["formaOrden"]);
								$conta = 1;
								while ($res = mysqli_fetch_array($consulta)) {
									$encargado = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='" . $res['cseg_usuario_encargado'] . "' AND usr_id_empresa='".$idEmpresa."'"));
									switch ($res['cseg_realizado']) {
										case 1:
											$html = 'Completado';
											break;
										default:
											$html = '<span class="label label-important">Pendiente';
											break;
									}
								?>
									<tr>
										<td align="center"><?= $conta; ?></td>
										<td><?= "<b>" . $res['tik_id'] . "</b><br><br>" . $res['tik_fecha_creacion']; ?></td>
										<td><?= $res['cseg_fecha_contacto']; ?></td>
										<td><?= $res['ciu_nombre'] . ", " . $res['dep_nombre']; ?></td>
										<td><?= $res['cli_nombre']; ?></td>
										<td><?= $res['usr_nombre']; ?></td>
										<td><?= $res['cseg_cotizacion']; ?></td>
										<td><?= $opcionesSino[$res['cseg_cotizo']]; ?></td>
										<td><?= $opcionesSino[$res['cseg_vendio']]; ?></td>
										<td><?= $opcionesSino[$res['cseg_consiguio_datos']]; ?></td>
										<td><?= $res['cseg_observacion']; ?></td>
										<td><?= $res['cseg_fecha_proximo_contacto']; ?></td>
										<td><?= $encargado['usr_nombre']; ?></td>
										<td><?= $html; ?></td>
									</tr>

								<?php
									$conta++;
								}
								?>
							</tbody>
						</table>
					<?php } ?>




					<?php
					if (isset($_GET["exp"]) and $_GET["exp"] == 5) {
						$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones
            INNER JOIN clientes ON cli_id=rem_cliente WHERE rem_id_empresa='".$idEmpresa."'");
					?>
						<div align="center">
							<table width="100%" border="1" rules="all">
								<thead>
									<tr>
										<th colspan="4">&nbsp;</th>
										<th colspan="3">Vencimiento</th>
										<th colspan="7">&nbsp;</th>
									</tr>

									<tr>
										<th>No</th>
										<th>ID</th>
										<th>Estado</th>
										<th>Fecha revisión</th>
										<th>Dia</th>
										<th>Mes</th>
										<th>Año</th>
										<th>Equipo</th>
										<th>Marca</th>
										<th>Referencia</th>
										<th>Serial</th>
										<th>Cliente</th>
										<th>Teléfono</th>
										<th>Email</th>
										<th>Ingresos</th>
										<th>Último ingreso</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$conta = 1;

									while ($res = mysqli_fetch_array($consulta)) {

										$camposRemision = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT 
										DAY(rem_fecha), MONTH(rem_fecha), YEAR(rem_fecha),
										DAY(DATE_ADD(rem_fecha, INTERVAL '" . $res['rem_tiempo_certificado'] . "' MONTH)), MONTH(DATE_ADD(rem_fecha, INTERVAL '" . $res['rem_tiempo_certificado'] . "' MONTH)), YEAR(DATE_ADD(rem_fecha, INTERVAL '" . $res['rem_tiempo_certificado'] . "' MONTH))
										FROM remisiones 
										WHERE rem_id='" . $res['rem_id'] . "' AND rem_id_empresa='".$idEmpresa."'"));

										$mesSiguiente = date("m") + 1;


										/*if (($camposRemision[4] != date("m") and $camposRemision[4] != $mesSiguiente) or $camposRemision[5] != date("Y")) {
											continue;
										}*/


										//Para septiembre y octubre
										if ($camposRemision[4] ==  $_GET["mes"] and $camposRemision[5] == $_GET["agno"]) {

										}else{
											continue;
										}

										$cantidadIngresos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT COUNT(*), MAX(rem_id) FROM remisiones 
											WHERE rem_serial='" . $res['rem_serial'] . "' AND rem_serial!=0 AND rem_id_empresa='".$idEmpresa."'"));
										$cantIngresos = $cantidadIngresos[0];
										if ($cantidadIngresos[0] == 0) $cantIngresos = 1;

										$ultimoIngreso = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT rem_fecha FROM remisiones WHERE rem_id='" . $cantidadIngresos[1] . "' AND rem_id_empresa='".$idEmpresa."'"));
									?>
										<tr>
											<td align="center"><?= $conta; ?></td>
											<td>C<?= $res['rem_id']; ?></td>
											<td><?= $estadosCertificados[$res['rem_estado_certificado']]; ?></td>
											<td><?= $res['rem_fecha']; ?></td>
											<td><?= $camposRemision[3]; ?></td>
											<td><?= $meses[$camposRemision[4]]; ?></td>
											<td><?= $camposRemision[5]; ?></td>
											<td><?= $res['rem_equipo']; ?></td>
											<td><?= $res['rem_marca']; ?></td>
											<td><?= $res['rem_referencia']; ?></td>
											<td><?= $res['rem_serial']; ?></td>
											<td><?= $res['cli_nombre']; ?></td>
											<td><?= $res['cli_telefono']; ?></td>
											<td><?= $res['cli_email']; ?></td>
											<td align="center"><?= $cantIngresos; ?></td>
											<td><?= $ultimoIngreso[0]; ?></td>
										</tr>

									<?php
										$conta++;
									}
									?>
								</tbody>
							</table>
						<?php } ?>

						<?php
						if (isset($_GET["exp"]) and $_GET["exp"] == 6) {
							$filtro="";
							if($_GET["responsable"]!=""){$filtro .= " AND (factura_creador='".$_GET["responsable"]."')";}
							if($_GET["vendedor"]!=""){$filtro .= " AND (factura_vendedor='".$_GET["vendedor"]."')";}
							if($_GET["cliente"]!=""){$filtro .= " AND (factura_cliente='".$_GET["cliente"]."')";}

							if(isset($_GET["desdeF"]) and $_GET["desdeF"]!=""){$filtro .= " AND (factura_fecha_propuesta>='".$_GET["desdeF"]."')";}
							if(isset($_GET["hastaF"]) and $_GET["hastaF"]!=""){$filtro .= " AND (factura_fecha_propuesta<='".$_GET["hastaF"]."')";}

							$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones
              INNER JOIN clientes ON cli_id=rem_cliente WHERE rem_id_empresa='".$idEmpresa."'");
						?>
							<div align="center">
								<table width="100%" border="1" rules="all">
									<thead>

										<tr>
											<th>No</th>
											<th>ID</th>
											<th>Fecha</th>
											<th>Cliente</th>
											<th>Productos</th>
											<th>Responsable</th>
											<th>Vendedor</th>
											<th>Valor</th>
											<th>Comisión</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM facturas
									INNER JOIN clientes ON cli_id=factura_cliente
									INNER JOIN usuarios ON usr_id=factura_creador
									WHERE factura_id=factura_id AND factura_id_empresa='".$idEmpresa."' $filtro
									ORDER BY factura_vendedor
									");

										$no = 1;
										while ($res = mysqli_fetch_array($consulta)) {
											$vendedor = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='" . $res['factura_vendedor'] . "' AND usr_id_empresa='".$idEmpresa."'"));

											$valorFactura = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT SUM(czpp_cantidad * czpp_valor) FROM cotizacion_productos 
                      WHERE czpp_cotizacion='" . $res['factura_id'] . "' and czpp_tipo=4"));

											$pCom = $configuracion['conf_comision_vendedores'] / 100;

											$comision = ($valorFactura[0] * $pCom);

											$totalComision += $comision;
										?>
											<tr>
												<td align="center"><?= $no; ?></td>
												<td align="center"><a href="#../cotizaciones-editar.php?id=<?= $res['cotiz_id']; ?>" target="_blank"><?= $res['factura_id']; ?></a></td>
												<td><?= $res['factura_fecha_propuesta']; ?></td>
												<td><?= strtoupper($res['cli_nombre']); ?></td>
												<td>
													<?php
													$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos
										INNER JOIN productos ON prod_id=czpp_producto
										WHERE czpp_cotizacion='" . $res['factura_id'] . "' AND czpp_tipo=4
										");
													$i = 1;
													while ($prod = mysqli_fetch_array($productos)) {
														echo "<b>" . $i . ".</b> " . $prod['prod_nombre'] . ", ";
														$i++;
													}
													?>
												</td>
												<td><?= strtoupper($res['usr_nombre']); ?></td>
												<td><?= strtoupper($vendedor['usr_nombre']); ?></td>
												<td align="center">$<?= number_format($valorFactura[0], 0, ".", "."); ?></td>
												<td align="center">$<?= number_format($comision, 0, ".", "."); ?></td>
											</tr>

										<?php
											$conta++;
										}
										?>
									</tbody>
									<tfoot>
										<tr style="height: 30px; font-weight: bold; font-size: large;">
											<td align="right" colspan="8">Total</td>
											<td align="center">$<?= number_format($totalComision, 0, ".", "."); ?></td>
										</tr>
									</tfoot>
								</table>
							<?php } ?>


</body>