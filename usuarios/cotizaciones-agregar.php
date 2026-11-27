<?php
include("sesion.php");

$idPagina = 78;
include("includes/verificar-paginas.php");
require_once RUTA_PROYECTO.'/usuarios/class/Cliente.php';
require_once RUTA_PROYECTO.'/usuarios/includes/api-ofima-conexion.php';
$ofimaBloquearClientesNoIntegrados = ofimaBloquearClientesNoIntegrados($conexionBdPrincipal, (int) $idEmpresa);
include("includes/head.php");
?>
<link href="css/chosen.css" rel="stylesheet">
<link href="css/clientes-listado.css" rel="stylesheet">
<link href="../assets-login/plugins/select2/css/select2.css" rel="stylesheet" />
<style>
	.cotiz-agregar .combo-ayuda { display: block; margin-top: 0.35rem; font-size: 0.75rem; color: #64748b; }
	.cotiz-agregar .clientes-panel { overflow: visible; }
	.cotiz-agregar .clientes-panel-header { border-radius: 13px 13px 0 0; }
	.cotiz-agregar fieldset.default {
		box-sizing: border-box;
		width: 100%;
		max-width: 100%;
		min-width: 0;
		margin: 0 0 1rem;
		padding: 0.75rem 1rem 0.25rem;
		border: 1px solid #e2e8f0;
		border-radius: 12px;
		background: #f8fafc;
	}
	.cotiz-agregar fieldset.default legend {
		width: auto;
		margin: 0 0 0.5rem;
		padding: 0 0.35rem;
		border: 0;
		font-size: 0.75rem;
		font-weight: 700;
		letter-spacing: 0.04em;
		text-transform: uppercase;
		color: #047857;
		line-height: 1.2;
	}
	.cotiz-agregar fieldset.default .control-group { margin-bottom: 0.65rem; }
	.cotiz-agregar .cotiz-campo-accion { display: flex; align-items: center; gap: 0.5rem; min-width: 0; max-width: 100%; }
	.cotiz-agregar .cotiz-campo-accion .cotiz-select {
		flex: 1 1 0;
		width: 0;
		min-width: 0;
		max-width: 100%;
		height: 38px;
		margin: 0;
		padding: 0 0.7rem;
		border: 1px solid #cbd5e1;
		border-radius: 8px;
		background: #fff;
		color: #0f172a;
		font-size: 0.875rem;
		box-shadow: none;
	}
	.cotiz-agregar .cotiz-campo-accion .btn {
		flex: 0 0 auto;
		height: 38px;
		margin: 0;
		padding: 0 0.85rem;
		line-height: 36px;
		white-space: nowrap;
		border-radius: 8px;
	}
	.cotiz-agregar .cotiz-campo-accion .select2-container { flex: 1 1 0; width: 0 !important; min-width: 0; max-width: 100%; }
	.cotiz-agregar .cotiz-campo-accion .select2-container .select2-selection--single {
		height: 38px;
		border: 1px solid #cbd5e1;
		border-radius: 8px;
		background: #fff;
	}
	.cotiz-agregar .cotiz-campo-accion .select2-container .select2-selection__rendered {
		line-height: 36px;
		padding-left: 0.7rem;
		color: #0f172a;
		font-size: 0.875rem;
	}
	.cotiz-agregar .cotiz-campo-accion .select2-container .select2-selection__placeholder { color: #94a3b8; }
	.cotiz-agregar .cotiz-campo-accion .select2-container--focus .select2-selection--single,
	.cotiz-agregar .cotiz-campo-accion .select2-container--open .select2-selection--single { background: #fff; color: #0f172a; }
	.cotiz-agregar .cotiz-campo-accion .select2-container .select2-selection__arrow { height: 36px; }
	.select2-container--open { z-index: 10060; }
	.select2-container--open .select2-dropdown {
		border: 1px solid #cbd5e1;
		border-radius: 8px;
		overflow: hidden;
		box-shadow: 0 8px 18px rgba(15, 23, 42, 0.12);
	}
	.select2-container--default .select2-search--dropdown { padding: 0.35rem 0.45rem; background: #f8fafc; }
	.select2-container--default .select2-search--dropdown .select2-search__field {
		height: 28px;
		margin: 0;
		padding: 0 0.55rem;
		border: 1px solid #cbd5e1;
		border-radius: 6px;
		box-shadow: none;
		font-size: 0.8125rem;
		line-height: 26px;
	}
	.select2-container--default .select2-results__option { padding: 0.28rem 0.6rem; font-size: 0.8125rem; }
	#product-select + .select2-container .select2-selection__choice:has(.combo-prod-off) {
		background-color: #fee2e2 !important;
		border-color: #fca5a5 !important;
		color: #991b1b !important;
		font-weight: 700;
	}
	#product-select + .select2-container .select2-selection__choice:has(.combo-prod-ok) {
		background-color: #d1fae5 !important;
		border-color: #6ee7b7 !important;
		color: #065f46 !important;
		font-weight: 700;
	}
	.select2-container--default .select2-results__option:has(.combo-prod-ok),
	.select2-container--default .select2-results__option--highlighted:has(.combo-prod-ok),
	.select2-container--default .select2-results__option--highlighted[aria-selected]:has(.combo-prod-ok) {
		background: #fff !important;
		color: #065f46 !important;
	}
	.select2-results__option .combo-prod-ok,
	.select2-container--default .select2-results__option--highlighted .combo-prod-ok {
		color: #065f46 !important;
		font-weight: 700;
		background: transparent !important;
	}
	.select2-container--default .select2-results__option:has(.combo-prod-off),
	.select2-container--default .select2-results__option--highlighted:has(.combo-prod-off),
	.select2-container--default .select2-results__option--highlighted[aria-selected]:has(.combo-prod-off) {
		background: #fff !important;
		color: #991b1b !important;
	}
	.select2-results__option .combo-prod-off,
	.select2-container--default .select2-results__option--highlighted .combo-prod-off {
		color: #991b1b !important;
		font-weight: 700;
		background: transparent !important;
	}
	.select2-container--default .select2-results__option:has(.cliente-ofima-ok),
	.select2-container--default .select2-results__option--highlighted:has(.cliente-ofima-ok),
	.select2-container--default .select2-results__option--highlighted[aria-selected]:has(.cliente-ofima-ok) {
		background: #fff !important;
		color: #065f46 !important;
	}
	.select2-results__option .cliente-ofima-ok,
	.select2-container--default .select2-results__option--highlighted .cliente-ofima-ok,
	.select2-selection__rendered .cliente-ofima-ok {
		color: #065f46 !important;
		font-weight: 700;
		background: transparent !important;
	}
	.select2-container--default .select2-results__option:has(.cliente-ofima-off),
	.select2-container--default .select2-results__option--highlighted:has(.cliente-ofima-off),
	.select2-container--default .select2-results__option--highlighted[aria-selected]:has(.cliente-ofima-off) {
		background: #fff !important;
		color: #991b1b !important;
	}
	.select2-results__option .cliente-ofima-off,
	.select2-container--default .select2-results__option--highlighted .cliente-ofima-off,
	.select2-selection__rendered .cliente-ofima-off {
		color: #991b1b !important;
		font-weight: 700;
		background: transparent !important;
	}
	.select2-container--default .select2-results__group {
		padding: 0.3rem 0.6rem;
		font-size: 0.6875rem;
		font-weight: 700;
		letter-spacing: 0.03em;
		text-transform: uppercase;
		color: #64748b;
	}
	@media (max-width: 768px) {
		.cotiz-agregar .cotiz-campo-accion { flex-wrap: wrap; align-items: stretch; }
		.cotiz-agregar .cotiz-campo-accion .cotiz-select,
		.cotiz-agregar .cotiz-campo-accion .select2-container { flex: 1 1 100%; width: 100% !important; }
		.cotiz-agregar .cotiz-campo-accion .btn { flex: 1 1 auto; }
	}
</style>

<!--============ javascript ===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-fileupload.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.tagsinput.js"></script>
<script src="js/chosen.jquery.js"></script>
<script src="js/bootstrap-colorpicker.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script src="js/date.js"></script>
<script src="js/daterangepicker.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script src="../assets-login/plugins/select2/js/select2.js"></script>
<script>
	$(function () {
		if (!$.fn.select2) {
			return;
		}
		function filtrarOpcion(params, data) {
			var termino = $.trim(params.term || '').toUpperCase();
			if (data.children) {
				var hijos = [];
				for (var i = 0; i < data.children.length; i++) {
					var hijo = filtrarOpcion(params, data.children[i]);
					if (hijo) {
						hijos.push(hijo);
					}
				}
				if (!hijos.length) {
					return null;
				}
				var grupo = $.extend(true, {}, data);
				grupo.children = hijos;
				return grupo;
			}
			if (!data.id) {
				return null;
			}
			if (termino === '' || (data.text || '').toUpperCase().indexOf(termino) > -1) {
				return data;
			}
			return null;
		}
		function plantillaClienteOfima(data) {
			if (!data.id) {
				return data.text;
			}
			var integrado = 0;
			if (typeof data.integrado_ofima !== 'undefined') {
				integrado = parseInt(data.integrado_ofima, 10) || 0;
			} else if (data.element) {
				integrado = parseInt($(data.element).attr('data-integrado-ofima') || '0', 10);
			}
			var clase = integrado === 1 ? 'cliente-ofima-ok' : 'cliente-ofima-off';
			return $('<span></span>').addClass(clase).text(data.text || '');
		}
		var bloquearClientesNoOfima = <?= !empty($ofimaBloquearClientesNoIntegrados) ? 'true' : 'false'; ?>;
		[
			{ nombre: 'cliente', placeholder: 'Escoja un cliente', buscar: 'Buscar cliente' },
			{ nombre: 'sucursal', placeholder: 'Escoja una sucursal', buscar: 'Buscar sucursal' },
			{ nombre: 'contacto', placeholder: 'Escoja un contacto', buscar: 'Buscar contacto' }
		].forEach(function (campo) {
			var select = $('select.cotiz-select[name="' + campo.nombre + '"]');
			if (!select.length || select.data('select2')) {
				return;
			}
			var opciones = {
				width: '100%',
				placeholder: campo.placeholder,
				minimumResultsForSearch: 0,
				dropdownParent: $(document.body),
				matcher: filtrarOpcion,
				language: { noResults: function () { return 'Sin resultados'; } }
			};
			if (campo.nombre === 'cliente') {
				opciones.minimumInputLength = 2;
				delete opciones.matcher;
				opciones.ajax = {
					url: 'ajax/ajax-buscar-clientes-cotizacion.php',
					dataType: 'json',
					delay: 250,
					cache: true,
					data: function (params) { return { q: params.term, habilitados: 1 }; },
					processResults: function (data) {
						var lista = (data && data.clientes) || [];
						return {
							results: lista.map(function (cliente) {
								return {
									id: cliente.id,
									text: String(cliente.nombre || ''),
									integrado_ofima: cliente.integrado_ofima
								};
							})
						};
					}
				};
				opciones.templateResult = plantillaClienteOfima;
				opciones.templateSelection = plantillaClienteOfima;
				opciones.language = {
					inputTooShort: function () {
						return bloquearClientesNoOfima
							? 'Escriba al menos 2 letras. Solo clientes integrados con Ofima.'
							: 'Escriba al menos 2 letras.';
					},
					noResults: function () { return 'Sin resultados'; },
					searching: function () { return 'Buscando...'; }
				};
			}
			select.select2(opciones);
			select.on('select2:open', function () {
				var buscador = document.querySelector('.select2-container--open .select2-search__field');
				if (buscador) {
					buscador.setAttribute('placeholder', campo.buscar);
					buscador.focus();
				}
			});
		});
	});
</script>
<?php 
//Son todas las funciones javascript para que los campos del formulario funcionen bien.
include("includes/js-formularios.php");
?>




<?php include("includes/texto-editor.php");?>
</head>
<body class="clientes-page cotiz-agregar">
<div class="layout">
	<?php include("includes/encabezado.php");?>
	<div class="main-wrapper">
		<div class="container-fluid clientes-page-inner">
			<?php
			$migaTema = [];
			if (Modulos::validarRol([77], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
				$migaTema[] = ['texto' => 'Cotizaciones', 'url' => 'cotizaciones.php'];
			}
			$migaTema[] = ['texto' => $paginaActual['pag_nombre'] ?? 'Agregar cotización'];
			include 'includes/miga-tema.php';
			?>
			<section class="clientes-panel">
				<div class="clientes-panel-header">
					<div>
						<h3><?= htmlspecialchars($paginaActual['pag_nombre'] ?? 'Agregar cotización'); ?></h3>
						<p><?= !empty($ofimaBloquearClientesNoIntegrados) ? 'Solo clientes integrados con Ofima' : 'Todos los clientes disponibles'; ?></p>
					</div>
				</div>
				<div class="clientes-panel-body">
							<form class="form-horizontal" method="post" action="bd_create/cotizaciones-guardar.php">

								<script type="application/javascript">
									function clientes(datoscliente){
										var id = datoscliente.value;
										location.href = "cotizaciones-agregar.php?cte="+id;
									}
								</script>
								
                               <fieldset class="default">
									<legend>Datos del cliente</legend>
                               <div class="control-group">
									<label class="control-label">Cliente</label>
									<div class="controls">
										<div class="cotiz-campo-accion">
										<select class="cotiz-select" name="cliente" onChange="clientes(this)" required>
											<option value=""></option>
											<?php
											if (isset($_GET['cte']) && is_numeric($_GET['cte'])) {
												$idClienteSelect = (int) $_GET['cte'];
												$filaClienteSelect = mysqli_fetch_assoc($conexionBdPrincipal->query(
													"SELECT cli_id, cli_nombre, cli_integrado_ofima FROM clientes WHERE cli_id='".$idClienteSelect."' AND cli_id_empresa='".(int) $idEmpresa."' LIMIT 1"
												));
												if ($filaClienteSelect) {
											?>
											<option value="<?= (int) $filaClienteSelect['cli_id']; ?>" data-integrado-ofima="<?= (int) ($filaClienteSelect['cli_integrado_ofima'] ?? 0) === 1 ? 1 : 0; ?>" selected><?= htmlspecialchars((string) $filaClienteSelect['cli_nombre'], ENT_QUOTES, 'UTF-8'); ?></option>
											<?php
												}
											}
											?>
                                    	</select>
										<?php if(isset($_GET['cte']) && is_numeric($_GET['cte']) && Modulos::validarRol([11], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){?>
											<a href="clientes-editar.php?id=<?= (int) $_GET["cte"]; ?>" class="btn btn-info" target="_blank">Editar cliente</a>
								   <?php }?>
										<?php if (!empty($ofimaBloquearClientesNoIntegrados)) { ?>
										<button type="button" class="btn btn-info" id="btnBuscarClientes">Buscar clientes</button>
										<?php } ?>
										</div>
										<?php if (!empty($ofimaBloquearClientesNoIntegrados)) { ?>
										<span class="combo-ayuda">Solo aparecen clientes integrados con Ofima.</span>
										<?php } ?>
                                    </div>
							   </div>
							   <?php if (!isset($_GET['cte']) || !is_numeric($_GET['cte'])) { ?>
							   </fieldset>
							   <?php } ?>

							   <?php if(is_numeric($_GET['cte'])){?>

								<?php
								$sqlClienteCte = "SELECT * FROM clientes WHERE cli_id='".$_GET['cte']."' AND cli_id_empresa='".$idEmpresa."'";
								if (!empty($ofimaBloquearClientesNoIntegrados)) {
									$sqlClienteCte .= " AND cli_integrado_ofima = 1";
								}
								$consultaCli=$conexionBdPrincipal->query($sqlClienteCte);
								$clienteInfo = mysqli_fetch_array($consultaCli, MYSQLI_BOTH);
								$fueraDeZona = false;
								if ($clienteInfo && !Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
									$permisosZona = Cliente::permisosVisibilidadListado((int) ($_SESSION['id'] ?? 0), $conexionBdPrincipal);
									$fueraDeZona = !Cliente::usuarioPuedeVerClienteEnListado(
										$permisosZona,
										(int) ($clienteInfo['cli_zona'] ?? 0),
										(int) $clienteInfo['cli_id']
									);
								}
								if (!$clienteInfo || $fueraDeZona) {
									$mensajeCliente = $fueraDeZona
										? 'Este cliente no está en sus zonas.'
										: (!empty($ofimaBloquearClientesNoIntegrados)
											? 'Este cliente no está integrado con Ofima. Elija uno que sí lo esté.'
											: 'Este cliente no está disponible.');
									echo '<div class="alert alert-warning">'.htmlspecialchars($mensajeCliente).'</div>';
									echo '</fieldset>';
								} else {
								?>
								<div class="control-group">
									<label class="control-label">Sucursal</label>
									<div class="controls">
										<div class="cotiz-campo-accion">
										<select class="cotiz-select" name="sucursal" required>
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM sucursales WHERE sucu_cliente_principal='".$_GET["cte"]."'");
											$numOp = $conOp->num_rows;
											$selectedSucursal = '';
											if($numOp == 0) {
												//Crear automáticamente la sucursal
												$conexionBdPrincipal->query("INSERT INTO sucursales(sucu_cliente_principal, sucu_ciudad, sucu_direccion, sucu_telefono, sucu_celular, sucu_nombre)VALUES('".$_GET["cte"]."', '".$clienteInfo['cli_ciudad']."', '".$clienteInfo['cli_direccion']."', '".$clienteInfo['cli_telefono']."', '".$clienteInfo['cli_celular']."','Sede principal (Automática)')");
												
												echo '<script type="text/javascript">window.location.href="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'";</script>';
												exit();
											} else if ($numOp == 1) {
												$selectedSucursal = 'selected';
											}

											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>" <?=$selectedSucursal;?>><?=$resOp[7];?></option>
                                            <?php
											}
											?>
                                    	</select>
									<?php if (Modulos::validarRol([83], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="clientes-sucursales.php?cte=<?= (int) $_GET["cte"]; ?>" class="btn btn-info" target="_blank">Ver sucursales</a>
									<?php } ?>
										</div>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Contacto</label>
									<div class="controls">
										<div class="cotiz-campo-accion">
										<select class="cotiz-select" name="contacto" required>
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM contactos WHERE cont_cliente_principal='".$_GET["cte"]."'");
											$numOp = $conOp->num_rows;
											$selectedContacto = '';

											if($numOp==0){
												//Crear automáticamente el contacto
												$conexionBdPrincipal->query("INSERT INTO contactos(cont_nombre, cont_cliente_principal)VALUES('Contacto principal (Automático)', '".$_GET["cte"]."')");
												
												echo '<script type="text/javascript">window.location.href="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'";</script>';
												exit();
											} else if ($numOp == 1) {
												$selectedContacto = 'selected';
											}

											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>" <?=$selectedContacto;?>><?=strtoupper($resOp[1])." (".$resOp[3].")";?></option>
                                            <?php
											}
											?>
                                    	</select>
									<?php if (Modulos::validarRol([44], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="clientes-contactos.php?cte=<?= (int) $_GET["cte"]; ?>" class="btn btn-info" target="_blank">Ver contactos</a>
									<?php } ?>
										</div>
                                    </div>
                               </div>
							   </fieldset>

							   <?php if($configuracion['conf_proveedor_cotizacion'] == 1){?>

								<script type="application/javascript">

									function provee(datos){
										var idP = datos.value;
										var cte = <?=$_GET["cte"];?>;

										location.href = `cotizaciones-agregar.php?prov=${idP}&cte=${cte}`;
									}
									</script>
								
								<div class="control-group">
									 <label class="control-label">Escoja un proveedor</label>
									 <div class="controls">
										 <select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="proveedor" onChange="provee(this)" required>
											 <option value=""></option>
											 <?php
											 $conOp = $conexionBdPrincipal->query("SELECT * FROM proveedores WHERE prov_id_empresa='".$idEmpresa."'");
											 while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											 ?>
												 <option value="<?=$resOp[0];?>" <?php if(isset($_GET["prov"]) and $_GET["prov"]!="" and $_GET["prov"]==$resOp[0]) echo "selected";?>><?=$resOp['prov_nombre'];?></option>
											 <?php
											 }
											 ?>
										 </select>
									 </div>
									
									<?php if(is_numeric($_GET['prov']) && Modulos::validarRol([125], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){?>
											<a href="proveedores-editar.php?id=<?=$_GET["prov"];?>" class="btn btn-info" target="_blank">Editar proveedor</a>
									<?php }?>
									
								</div>
 
									<?php }else{?>
									<input type="hidden" name="proveedor" value="0">
									<?php }?>
                               
								<div class="control-group">
									<label class="control-label">Usuario Influyente</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="influyente" required>
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios 
											WHERE usr_bloqueado!=1 AND usr_id_empresa='".$idEmpresa."' 
											ORDER BY usr_nombre");

											while ($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)) {
												$selectedInfluyente = '';

												if ($resOp[0] == $_SESSION['id']) {
													$selectedInfluyente = 'selected';
												}
											?>
                                            	<option value="<?=$resOp[0];?>" <?=$selectedInfluyente;?>><?=strtoupper($resOp[4])." (".$resOp[5].")";?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Fecha de la propuesta</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaPropuesta" required value="<?=date("Y-m-d");?>">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Fecha de vencimiento</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaVencimiento" required value="<?=date("Y-m-d");?>">
									</div>
								</div>
								
								<?php
								if($clienteInfo['cli_credito']==1){
									$msjCredito = "Este cliente tiene crédito con la compañía.";
									$colorCredito = 'aquamarine';
								}else{
									$msjCredito = "Este cliente aún NO tiene crédito con la compañía.";
									$colorCredito = 'gold';
								}
								?>	
								<p style="color: black; background-color: <?=$colorCredito;?>; padding: 10px; font-weight: bold;"><?=$msjCredito;?></p>
								
								<div class="control-group">
									<label class="control-label">Forma de pago</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="formaPago">
											<option value=""></option>
                                            <option value="1" <?php if($clienteInfo['cli_credito']!=1)echo "selected";?>>Contado</option>
                                            <option value="2" <?php if($clienteInfo['cli_credito']==1)echo "selected";?>>Crédito</option>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Moneda</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="moneda">
											<option value=""></option>
                                            <option value="1" selected>COP</option>
                                            <option value="2">USD</option>
                                    	</select>
                                    </div>
							   </div>
							   
								
								<div class="control-group">
										<label class="control-label">Combos</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="span10" tabindex="2" name="combo[]" multiple id="combos-select">
											</select>
										</div>
								   </div>
								
								<div class="control-group">
										<label class="control-label">Productos</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="span10" tabindex="2" name="producto[]" multiple id="product-select">
											</select>
										</div>
									</div>
								
									<div class="control-group">
										<label class="control-label">Observaciones</label>
										<div class="controls">
											<textarea rows="5" cols="80" style="width: 80%" class="tinymce-simple" name="notas"></textarea>
										</div>
									</div>

									<?php
									$consultaTickets = $conexionBdPrincipal->query("SELECT * FROM clientes_tikets 
									WHERE tik_cliente='".$_GET["cte"]."'
									AND tik_id_cotizacion IS NULL
									AND tik_tipo_tiket = 1
									AND tik_estado = 1
									AND tik_tipo_negocio = 1
									");
									$numTickets = $consultaTickets->num_rows;
									?>

									<div class="alert alert-info">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<i class="icon-exclamation-sign"></i><strong>Asociar Ticket!</strong> Se debe asociar un ticket comercial ya existente o se creará uno nuevo automáticamente para esta cotización.<br>
										En caso que quiera dejar esta cotización sin ticket, por el momento, escoja la opción <b>No deseo asociar ningun ticket a esta cotización por el momento</b>.
									</div>

									<div class="control-group">
										<label class="control-label">Asociar a un ticket</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="ticket">
												<option value="TICKET_AUTO">Deseo que el ticket se cree automáticamente</option>
												<option value="NO_TICKET">NO deseo asociar ningun ticket a esta cotización por el momento</option>
												<?php
												if ($numTickets > 0) {
													echo '<optgroup label="Tickets disponibles">';
													while ($resOp = mysqli_fetch_array($consultaTickets, MYSQLI_BOTH)) {
														$selected = '';
														if(isset($_GET['ticket']) && $resOp[0] == $_GET['ticket']) {
															$selected = 'selected';
														}
												?>
														<option value="<?=$resOp[0];?>" <?=$selected;?>><?="Ticket # ".$resOp[0]." - ".strtoupper($resOp[1])." (".$resOp[3].")";?></option>
												<?php
												}
													echo '</optgroup>';
													}
												?>
											</select>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">¿Es PRE-cotización?
											<button class="tooltipp">No será tenida en cuenta como una cotización oficial y no es apta para generar pedido. Esta opción no se podrá cambiar después.</button>
											<i class="fa-solid fa-circle-question"></i>
										</label>
										<div class="controls">
											<input type="checkbox" value="1" name="precotizacion">
										</div>
									</div>
								
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-arrow-right"></i> Continuar</button>
								</div>
								<?php } ?>

			<?php }?>
							</form>
				</div>
			</section>	
            

		</div>
	</div>
	<?php include("includes/drawer-buscar-clientes.php");?>
	<?php include("includes/drawer-crear-cliente.php");?>
	<?php include("includes/pie.php");?>
	<script src="js/Cotizaciones.js?v=20261123a"></script>
</div>
</body>
</html>