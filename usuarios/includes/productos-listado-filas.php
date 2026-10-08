<?php
if (empty($consulta) || !($consulta instanceof mysqli_result)) {
    echo '<tr class="productos-empty-row"><td colspan="20" class="clientes-empty">No hay productos que coincidan con los filtros aplicados.</td></tr>';
    return;
}
if ($consulta->num_rows === 0) {
    echo '<tr class="productos-empty-row"><td colspan="20" class="clientes-empty">No hay productos que coincidan con los filtros aplicados.</td></tr>';
    return;
}
										while ($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {

											/*$dcto1 = $res['prod_descuento1']/100;
											$precioMinimo = $res['prod_precio'] - ($res['prod_precio']*$dcto1);*/

											$descuentoDealer = !empty($res['prod_descuento2']) ? $res['prod_descuento2'] / 100 : 0;

											if(!empty($res['prod_precio'])){
												$precioDealer = $res['prod_precio'] - ($res['prod_precio'] * $descuentoDealer);
											}

											$descuentoWeb = 0;
											if(!empty($res['prod_descuento_web'])){
												$descuentoWeb = $res['prod_descuento_web'] / 100;
											}

											if(!empty($res['prod_precio'])){
												$precioWeb = $res['prod_precio'] - ($res['prod_precio'] * $descuentoWeb);
											}

											if(!empty($res['prod_utilidad']) AND !empty($res['prod_costo_dolar'])){
												$precioListaUSD = productosPrecioListaUSD($res['prod_utilidad'], $res['prod_costo_dolar']);
											}

											if(!empty($res['prod_comision'])){
												$comision = $res['prod_comision'] / 100;
											}

											$valorComision = 0;
											$precioConIva = 0;

											if (!empty($res['prod_precio'])) {

												$valorComision = ($res['prod_precio'] * $comision);

												$precioConIva = $res['prod_precio'] + ($res['prod_precio'] * 0.19);
											}

											$precioListaDolarHoy = ($precioListaUSD * $configuracion['conf_trm_venta']);
										?>
											<tr>
												<td><?= $no; ?></td>

												<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
													<td>
														<a 
															href="javascript:void(0);" 
															onClick="pred(this)" 
															name="<?= $res[$pk]; ?>" 
															title="<?= $res['prod_precio_predeterminado']; ?>" 
															id="p<?= $res[$pk]; ?>"
															translate="no"
														>
															<?=$opcionSINO[$res['prod_precio_predeterminado']];?>
														</a>
													</td>

													<td>
														<a 
															href="javascript:void(0);" 
															onClick="visweb(this)" 
															name="<?= $res[$pk]; ?>" 
															title="<?= $res['prod_visible_web']; ?>" 
															id="vw<?= $res[$pk];?>"
															translate="no"
														>
															<?= $opcionSINO[$res['prod_visible_web']]; ?>
														</a>
													</td>
												<?php } ?>

												<td align="center" style="font-weight: bold;">
													<input 
														type="text" 
														title="prod_referencia" 
														name="<?= $res[$pk]; ?>" 
														value="<?= $res['prod_referencia']; ?>" 
														style="width: 60px; text-align: center" 
														onChange="productos(this)" 
														<?php if (!Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {echo "disabled";} ?>
														translate="no"
													>
													<span style="visibility: hidden;" translate="no"><?= $res['prod_referencia']; ?></span>
												</td>

												<td class="col-nombre">
													<div class="<?= $columna; ?>">
														<?= htmlspecialchars($res['prod_nombre'] ?? ''); ?>
														<h4>
														<?php if (Modulos::validarRol([38], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
															<a href="#" class="js-editar-producto" data-id="<?= (int) $res[0]; ?>" title="Editar"><i class="icon-edit"></i></a>
														<?php } ?>
														<?php if (Modulos::validarRol([61], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && Producto::numeroProcesosComerciales($res[0], $conexionBdPrincipal) == 0 && Producto::numeroCombos($res[0], $conexionBdPrincipal) == 0) {?>
															<a href="bd_delete/productos-eliminar.php?id=<?= $res[0]; ?>" class="js-eliminar-producto" data-nombre="<?= htmlspecialchars($res['prod_nombre']); ?>" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
														<?php } ?>
															<!--<a href="productos-materiales.php?pdto=<?= $res[0]; ?>" data-toggle="tooltip" title="Materiales"><i class="icon-folder-open"></i></a>-->
														<?php if (Modulos::validarRol([209], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
															<a href="#" class="js-galeria-producto" data-id="<?= (int) $res[0]; ?>" data-toggle="tooltip" title="Galería"><i class="icon-picture"></i></a>
														<?php } ?>
														<?php if (Modulos::validarRol([145], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
															<a href="#" class="js-bodegas-producto" data-id="<?= (int) $res[0]; ?>" data-toggle="tooltip" title="Bodegas por productos"><i class="icon-pushpin"></i></a>
														<?php } ?>
														<?php if (Modulos::validarRol([214], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
															<a href="#" class="js-historial-producto" data-id="<?= (int) $res[0]; ?>" data-toggle="tooltip" title="Historial de precios"><i class="icon-time"></i></a>
														<?php } ?>
														<?php if (Modulos::validarRol([215], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
															<a href="bd_create/productos-replicar-guardar.php?prod=<?= $res[0]; ?>" class="js-replicar-producto" data-nombre="<?= htmlspecialchars($res['prod_nombre']); ?>" data-toggle="tooltip" title="Replicar a productos de soporte"><i class="icon-repeat"></i></a>
														<?php } ?>
														<?php if (!empty($ofimaProductosActiva)) {
															$integradoOfimaProducto = (int) ($res['prod_integrado_ofima'] ?? 0) === 1;
															if ($integradoOfimaProducto) { ?>
															<span class="productos-icono-ofima is-ok" data-toggle="tooltip" title="Integrado con Ofima"><i class="icon-ok-sign"></i></span>
															<?php } else { ?>
															<span class="productos-icono-ofima is-off" data-toggle="tooltip" title="No integrado con Ofima"><i class="icon-remove-circle"></i></span>
															<?php }
														} ?>
														</h4>
													</div>
												</td>

												<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
													<td>
														<div class="precio-par" translate="no">
															<label class="precio-par-fila">
																<span>COP:</span>
																<input
																	id="costo<?= $res['prod_id']; ?>"
																	type="text"
																	alt="<?= $res['prod_utilidad']; ?>"
																	title="prod_costo"
																	name="<?= $res[$pk]; ?>"
																	value="<?= $res['prod_costo']; ?>"
																	onChange="productos(this)"
																>
															</label>
															<label class="precio-par-fila">
																<span>USD:</span>
																<input
																	id="costoUSD<?= $res['prod_id']; ?>"
																	type="text"
																	title="prod_costo_dolar"
																	name="<?= $res[$pk]; ?>"
																	value="<?= $res['prod_costo_dolar']; ?>"
																	onChange="productos(this)"
																>
															</label>
														</div>
													</td>
													<?php } ?>	

													<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>

													<td>
														<input 
															id="utilidad<?= $res['prod_id']; ?>" 
															type="text" 
															alt="<?= $res['prod_costo']; ?>" 
															title="prod_utilidad" 
															name="<?= $res[$pk]; ?>" 
															value="<?= $res['prod_utilidad']; ?>" 
															style="width: 40px; text-align: center" 
															onChange="productos(this)"
															translate="no"
														>
														<span style="visibility: hidden;" translate="no"><?= $res['prod_utilidad']; ?></span>
													</td>
												<?php }

												$precioLista = 0;
												if (!empty($res['prod_precio'])) {
													$precioLista = $res['prod_precio'];
												}
												?>

												<td id="precioLista<?= $res['prod_id']; ?>">$<?= number_format($precioLista, 0, ",", "."); ?></td>

												<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
													<td id="precioListaIva<?= $res['prod_id']; ?>">$<?= number_format($precioConIva, 0, ",", "."); ?></td>
												<?php } ?>

												<td id="precioListaUSD<?= $res['prod_id']; ?>">USD <?= number_format($precioListaUSD, 2, ",", "."); ?></td>

												<td id="precioListaDolarHoy<?= $res['prod_id']; ?>">$<?= number_format($precioListaDolarHoy, 0, ",", "."); ?></td>

												<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
													<td>
														<input
															id="descuentoDealer<?= $res['prod_id']; ?>"
															type="text" 
															title="prod_descuento2" 
															name="<?= $res[$pk]; ?>" 
															value="<?= $res['prod_descuento2']; ?>" 
															style="width: 40px; text-align: center" 
															onChange="productos(this)"
															translate="no"
														>
														<span style="visibility: hidden;" translate="no"><?= $res['prod_descuento2']; ?></span>
													</td>

													<td id="precioDealer<?= $res['prod_id']; ?>">$<?= number_format($precioDealer, 0, ",", "."); ?></td>

													<td>
														<input
															id="dctoWeb<?= $res['prod_id']; ?>"
															type="text" 
															title="prod_descuento_web" 
															name="<?= $res[$pk]; ?>" 
															value="<?= $res['prod_descuento_web']; ?>" 
															style="width: 40px; text-align: center" 
															onChange="productos(this)"
															translate="no"
														>
														<span style="visibility: hidden;" translate="no"><?= $res['prod_descuento_web']; ?></span>
													</td>

													<td id="precioWeb<?= $res['prod_id']; ?>">$<?= number_format($precioWeb, 0, ",", "."); ?></td>

													
												<?php } ?>

												<td>
													<input type="text" title="prod_descuento1" name="<?= $res[$pk]; ?>" value="<?= $res['prod_descuento1']; ?>" style="width: 40px; text-align: center" onChange="productos(this)" <?php if ($_SESSION["id"] != 7 and $_SESSION["id"] != 15) {echo "disabled";} ?>>
													<span style="visibility: hidden;"><?= $res['prod_descuento1']; ?></span>
												</td>

												<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
														<td>
															<input type="text" title="prod_comision" name="<?= $res[$pk]; ?>" value="<?= $res['prod_comision']; ?>" style="width: 40px; text-align: center" onChange="productos(this)">
															<span style="visibility: hidden;"><?= $res['prod_comision']; ?></span>
														</td>

														<td>
															<input type="text" title="prod_comision_externo" name="<?= $res[$pk]; ?>" value="<?= $res['prod_comision_externo']; ?>" style="width: 40px; text-align: center" onChange="productos(this)">
															<span style="visibility: hidden;"><?= $res['prod_comision_externo']; ?></span>
														</td>


												<?php
													} 
												?>
												

												

												<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
													<td><?= $res['G2NAME']; ?></td>
												<?php } ?>

												<td><?= $res['catp_nombre']; ?></td>
												<td><?= $res['mar_nombre']; ?></td>



												<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
													<td>
														<input type="text" title="prod_precio_fabrica" name="<?= $res[$pk]; ?>" value="<?= $res['prod_precio_fabrica']; ?>" style="width: 80px; text-align: center" onChange="productos(this)">
														<span style="visibility: hidden;"><?= $res['prod_precio_fabrica']; ?></span>
													</td>

													<td>
														<input type="text" title="prod_flete" name="<?= $res[$pk]; ?>" value="<?= $res['prod_flete']; ?>" style="width: 80px; text-align: center" onChange="productos(this)">
														<span style="visibility: hidden;"><?= $res['prod_flete']; ?></span>
													</td>

													<td>
														<input type="text" title="prod_aduana" name="<?= $res[$pk]; ?>" value="<?= $res['prod_aduana']; ?>" style="width: 80px; text-align: center" onChange="productos(this)">
														<span style="visibility: hidden;"><?= $res['prod_aduana']; ?></span>
													</td>
												<?php } ?>
												<td align="center" style="font-weight: bold;">
													<input type="text" title="prod_existencias" name="<?= $res[$pk]; ?>" value="<?= $res['prod_existencias']; ?>" style="width: 60px; text-align: center" onChange="productos(this)" disabled>
													<span style="visibility: hidden;"><?= $res['prod_existencias']; ?></span>
												</td>
												<!--<td>$<?= number_format($precioMinimo, 0, ",", "."); ?></td>
												<td>$<?= number_format($valorComision, 0, ",", "."); ?></td>-->
											</tr>
										<?php $no++;
										} ?>
