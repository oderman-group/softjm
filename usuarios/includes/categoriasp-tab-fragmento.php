<?php if ($tabFragmento === 'g2') { ?>
			<div class="categorias-tab-contenido">
			<span id="resp"></span>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3>GRUPO 2</h3>
						</div>
						<div class="widget-container">
							<p></p>
							<table class="table table-striped table-bordered clientes-table">
							<thead>
							<tr>
								<th>No</th>
								<th><?= $ofimaActiva ? 'Código' : 'Cod'; ?></th>
                                <th>Nombre</th>
								<!--<th>Grupo</th>-->
								<th>#Productos</th>
								<?php if (Modulos::validarRol([403], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
								<th>Actualización</th>
								<th>Utilidad Min (%)</th>
								<th>Utilidad Lista (%)</th>
								<th title="Sobre el precio de lista.">Dcto. Max. (%)</th>
								<th title="Sobre el precio de lista.">Utilidad Dealer. (%)</th>
								<th title="Sobre el precio de lista.">Utilidad Web. (%)</th>
								<th>Comisión (%)</th>
								<?php }?>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$conteoGrupo2 = [];
							$consultaConteoG2 = $conexionBdPrincipal->query("SELECT prod_categoria AS id, COUNT(*) AS total FROM productos WHERE prod_id_empresa='".$idEmpresa."' GROUP BY prod_categoria");
							while ($consultaConteoG2 && ($filaConteo = mysqli_fetch_assoc($consultaConteoG2))) {
								$conteoGrupo2[(int) $filaConteo['id']] = (int) $filaConteo['total'];
							}
							$usuariosGrupo2 = [];
							$consultaUsuariosG2 = $conexionBdPrincipal->query("SELECT usr_id, usr_nombre FROM usuarios WHERE usr_id_empresa='".$idEmpresa."'");
							while ($consultaUsuariosG2 && ($filaUsuario = mysqli_fetch_assoc($consultaUsuariosG2))) {
								$usuariosGrupo2[(int) $filaUsuario['usr_id']] = $filaUsuario['usr_nombre'];
							}
							$consulta = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=2 AND catp_id_empresa='".$idEmpresa."'");
							$no = 1;
							$totalP=0;
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
								$numProductos = $conteoGrupo2[(int) $res[0]] ?? 0;
								$totalP += $numProductos;
								$usuario = ['usr_nombre' => $usuariosGrupo2[(int) ($res['catp_usuario'] ?? 0)] ?? ''];
							?>
							<?php
								$habilitadaGrupo2 = !isset($res['catp_habilitada']) || (int) $res['catp_habilitada'] === 1;
							?>
							<tr class="<?= $habilitadaGrupo2 ? 'categoria-habilitada' : 'categoria-deshabilitada'; ?>" data-habilitada="<?= $habilitadaGrupo2 ? '1' : '0'; ?>">
								<td><?=$no;?></td>
								<td><?= $ofimaActiva ? htmlspecialchars((string) ($res['catp_cod_grupo'] ?? ''), ENT_QUOTES, 'UTF-8') : $res[0]; ?></td>
                                <td><?=$res[1];?></td>
								<!--<td><?=$res[2];?></td>-->
								<td style="text-align: center;">
									<a href="productos.php?grupo2=<?=$res[0];?>" data-toggle="tooltip" title="Productos"><?=$numProductos;?></a>
								</td>
								<?php if (Modulos::validarRol([403], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
								<td>
										<span style="font-size: 9px;"><?= strtoupper((string) (is_array($usuario) ? ($usuario['usr_nombre'] ?? '') : '')); ?></span>
										<br><span style="font-size: 9px;"><?=$res['catp_fecha'];?></span>
								</td>
								
								<td>
										<input type="text" title="prod_utilidad_minima" alt="catp_utilidad_minima" name="<?=$res[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res['catp_utilidad_minima'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_utilidad" alt="catp_utilidad_lista" name="<?=$res[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res['catp_utilidad_lista'];?>">
								</td>
								
								
								<td>
										<input type="text" title="prod_descuento1" alt="catp_dcto_max" name="<?=$res[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res['catp_dcto_max'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_descuento2" alt="catp_utilidad_dealer" name="<?=$res[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res['catp_utilidad_dealer'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_descuento_web" alt="catp_utilidad_web" name="<?=$res[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res['catp_utilidad_web'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_comision" alt="catp_comision" name="<?=$res[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res['catp_comision'];?>">
								</td>
								<?php }?>
                                <td><h4>
																	<?php if (Modulos::validarRol([41], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
                                    <a href="#" class="js-editar-grupo" data-tab="g2" data-id="<?= (int) $res[0]; ?>" data-nombre="<?= htmlspecialchars((string) $res['catp_nombre'], ENT_QUOTES, 'UTF-8'); ?>" data-codigo="<?= htmlspecialchars((string) ($res['catp_cod_grupo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" data-habilitada="<?= $habilitadaGrupo2 ? '1' : '0'; ?>" title="Editar"><i class="icon-edit"></i></a>
																	<?php } ?>
																	<?php if (Modulos::validarRol([62], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
                                    <a href="bd_delete/categoriasp-eliminar.php?id=<?=$res[0];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
																	<?php } ?>
                                </h4></td>
							</tr>
                            <?php $no++;}?>
							</tbody>
							<tfoot>
								<tr style="font-weight: bold;">
									<td colspan="2">TOTAL</td>
									<td style="text-align: center;"><?=$totalP;?></td>
									<td colspan="6">&nbsp;</td>
								</tr>	
							</tfoot>	
							</table>
						</div>
					</div>
				</div>
			</div>
			</div>
<?php } ?>
<?php if ($tabFragmento === 'marcas') { ?>
			<div class="categorias-tab-contenido">
			<span id="respG3"></span>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head bondi-blue">
							<h3>MARCAS</h3>
						</div>
						<div class="widget-container">
							<p></p>
							<table class="table table-striped table-bordered clientes-table">
							<thead>
							<tr>
								<th>No</th>
								<th><?= $ofimaActiva ? 'Código' : 'Cod.'; ?></th>
                                <th>Nombre</th>
								<!--<th>Grupo</th>-->
								<th>#Productos</th>
								<?php if (Modulos::validarRol([403], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
								<th>Actualización</th>
								<th>Utilidad Min (%)</th>
								<th>Utilidad Lista (%)</th>
								<th title="Sobre el precio de lista.">Dcto. Max. (%)</th>
								<th title="Sobre el precio de lista.">Utilidad Dealer. (%)</th>
								<th title="Sobre el precio de lista.">Utilidad Web. (%)</th>
								<th>Comisión (%)</th>
								<?php }?>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$conteoMarcas = [];
							$consultaConteoMar = $conexionBdPrincipal->query("SELECT prod_marca AS id, COUNT(*) AS total FROM productos WHERE prod_id_empresa='".$idEmpresa."' GROUP BY prod_marca");
							while ($consultaConteoMar && ($filaConteo = mysqli_fetch_assoc($consultaConteoMar))) {
								$conteoMarcas[(int) $filaConteo['id']] = (int) $filaConteo['total'];
							}
							$consulta3 = $conexionBdPrincipal->query("SELECT * FROM marcas WHERE mar_id_empresa='".$idEmpresa."'");
							$no = 1;
							$totalP3=0;
							while($res3 = mysqli_fetch_array($consulta3, MYSQLI_BOTH)){
								$numProductos3 = $conteoMarcas[(int) $res3[0]] ?? 0;
								$totalP3 += $numProductos3;
								/*$consultaUsuarios3=$conexionBdPrincipal->query("SELECT * FROM usuarios WHERE usr_id='".$res3['catp_usuario']."'");
								$usuario3 = mysqli_fetch_array($consultaUsuarios3, MYSQLI_BOTH);*/
							?>
							<?php $habilitadaMarca = !isset($res3['mar_habilitada']) || (int) $res3['mar_habilitada'] === 1; ?>
							<tr class="<?= $habilitadaMarca ? 'categoria-habilitada' : 'categoria-deshabilitada'; ?>">
								<td><?=$no;?></td>
								<td><?= $ofimaActiva ? htmlspecialchars((string) ($res3['mar_cod_ofima'] ?? ''), ENT_QUOTES, 'UTF-8') : $res3[0]; ?></td>
                                <td><?=$res3[1];?></td>
								<!--<td><?=$res[2];?></td>-->
								<td style="text-align: center;">
									<a href="productos.php?marca=<?=$res3[0];?>" data-toggle="tooltip" title="Productos"><?=$numProductos3;?></a>
								</td>
								<?php if (Modulos::validarRol([403], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
								<td>
										<span style="font-size: 9px;"></span>
										<br><span style="font-size: 9px;"></span>
								</td>
								
								<td>
										<input type="text" title="prod_utilidad_minima" alt="catp_utilidad_minima" name="<?=$res3[0];?>" style="width: 40px; text-align: center" onChange="grupoTres(this)" value="<?=$res3['catp_utilidad_minima'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_utilidad" alt="catp_utilidad_lista" name="<?=$res3[0];?>" style="width: 40px; text-align: center" onChange="grupoTres(this)" value="<?=$res3['catp_utilidad_lista'];?>">
										
								</td>
								
								
								<td>
										<input type="text" title="prod_descuento1" alt="catp_dcto_max" name="<?=$res3[0];?>" style="width: 40px; text-align: center" onChange="grupoTres(this)" value="<?=$res3['catp_dcto_max'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_descuento2" alt="catp_utilidad_dealer" name="<?=$res3[0];?>" style="width: 40px; text-align: center" onChange="grupoTres(this)" value="<?=$res3['catp_utilidad_dealer'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_descuento_web" alt="catp_utilidad_web" name="<?=$res3[0];?>" style="width: 40px; text-align: center" onChange="grupoTres(this)" value="<?=$res3['catp_utilidad_web'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_comision" alt="catp_comision" name="<?=$res3[0];?>" style="width: 40px; text-align: center" onChange="grupoTres(this)" value="<?=$res3['catp_comision'];?>">
								</td>
								<?php } ?>
                                <td>
									<h4>
									<a href="#" class="js-editar-grupo" data-tab="marcas" data-id="<?= (int) $res3[0]; ?>" data-nombre="<?= htmlspecialchars((string) $res3['mar_nombre'], ENT_QUOTES, 'UTF-8'); ?>" data-codigo="<?= htmlspecialchars((string) ($res3['mar_cod_ofima'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" data-habilitada="<?= $habilitadaMarca ? '1' : '0'; ?>" title="Editar"><i class="icon-edit"></i></a>
									</h4>
							</td>
							</tr>
                            <?php $no++;}?>
							</tbody>
							<tfoot>
								<tr style="font-weight: bold;">
									<td colspan="2">TOTAL</td>
									<td style="text-align: center;"><?=$totalP3;?></td>
									<td colspan="6">&nbsp;</td>
								</tr>	
							</tfoot>	
							</table>
						</div>
					</div>
				</div>
			</div>
			</div>
<?php } ?>
<?php if ($tabFragmento === 'g3') { ?>
			<div class="categorias-tab-contenido">
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head cabeza-clasificacion">
							<h3>GRUPO 3</h3>
						</div>
						<div class="widget-container">
							<table class="table table-striped table-bordered clientes-table">
							<thead>
							<tr>
								<th>No</th>
								<th><?= $ofimaActiva ? 'Código' : 'Cod.'; ?></th>
								<th>Nombre</th>
								<th>#Productos</th>
								<th></th>
							</tr>
							</thead>
							<tbody>
							<?php
							$consultaG3 = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=3 AND catp_id_empresa='".$idEmpresa."'");
							$noG3 = 1;
							$hayGrupo3 = false;
							if ($consultaG3) {
								while ($resG3 = mysqli_fetch_array($consultaG3, MYSQLI_BOTH)) {
									$hayGrupo3 = true;
									$habilitadaG3 = !isset($resG3['catp_habilitada']) || (int) $resG3['catp_habilitada'] === 1;
							?>
							<tr class="<?= $habilitadaG3 ? 'categoria-habilitada' : 'categoria-deshabilitada'; ?>">
								<td><?= $noG3; ?></td>
								<td><?= $ofimaActiva ? htmlspecialchars((string) ($resG3['catp_cod_grupo'] ?? ''), ENT_QUOTES, 'UTF-8') : $resG3[0]; ?></td>
								<td><?= htmlspecialchars((string) $resG3['catp_nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td style="text-align:center;">—</td>
								<td>
									<?php if (Modulos::validarRol([41], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
									<a href="#" class="js-editar-grupo" data-tab="g3" data-id="<?= (int) $resG3[0]; ?>" data-nombre="<?= htmlspecialchars((string) $resG3['catp_nombre'], ENT_QUOTES, 'UTF-8'); ?>" data-codigo="<?= htmlspecialchars((string) ($resG3['catp_cod_grupo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" data-habilitada="<?= $habilitadaG3 ? '1' : '0'; ?>" title="Editar"><i class="icon-edit"></i></a>
									<?php } ?>
								</td>
							</tr>
							<?php $noG3++; } } ?>
							</tbody>
							</table>
							<?php if (!$hayGrupo3) { ?>
							<p class="categorias-vacio">Todavía no hay registros en el grupo 3. Usa Agregar nuevo y elige Grupo 3.</p>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			</div>
<?php } ?>
