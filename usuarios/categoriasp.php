<?php
include("sesion.php");

$idPagina = 39;
include("includes/verificar-paginas.php");
include_once(RUTA_PROYECTO."/usuarios/includes/api-ofima-conexion.php");
$ofimaActiva = ofimaIntegracionActiva($conexionBdPrincipal, (int) $_SESSION["dataAdicional"]["id_empresa"]);
include("includes/head.php");
?>
<link href="css/tablecloth.css" rel="stylesheet">
<link href="css/clientes-listado.css" rel="stylesheet">
<style>
	table.table tbody tr.categoria-deshabilitada td {
		background-color: #f1f5f9 !important;
		color: #94a3b8;
	}
	table.table tbody tr.categoria-deshabilitada td a {
		color: #94a3b8;
	}
	body:not(.ver-habilitados) tr.categoria-habilitada,
	body:not(.ver-no-habilitados) tr.categoria-deshabilitada {
		display: none;
	}
	.categorias-filtro-check {
		display: inline-flex;
		align-items: center;
		gap: 0.4rem;
		margin: 0;
		font-weight: 600;
		color: #334155;
		cursor: pointer;
	}
	.categorias-tabs {
		display: flex;
		flex-wrap: wrap;
		gap: 0.4rem;
		margin: 1rem 0 0.75rem;
		padding: 0.35rem;
		background: #fff;
		border: 1px solid #e2e8f0;
		border-radius: 12px;
	}
	.categorias-tab {
		border: 0;
		background: transparent;
		color: #475569;
		font-weight: 700;
		font-size: 1rem;
		letter-spacing: 0.01em;
		text-transform: uppercase;
		padding: 0.7rem 1.25rem;
		border-radius: 10px;
		cursor: pointer;
	}
	.categorias-tab[data-tab="g1"] { background: #c0392b; color: #fff; }
	.categorias-tab[data-tab="g2"] { background: #009600; color: #fff; }
	.categorias-tab[data-tab="marcas"] { background: #0093a8; color: #fff; }
	.categorias-tab[data-tab="g3"] { background: #6d28d9; color: #fff; }
	.categorias-tab.is-active {
		font-size: 1.2rem;
		padding: 0.95rem 1.6rem;
		transform: scale(1.06);
		z-index: 1;
	}
	.categorias-tab[data-tab="g1"].is-active { box-shadow: inset 0 0 0 3px #fff, 0 0 0 3px #c0392b; }
	.categorias-tab[data-tab="g2"].is-active { box-shadow: inset 0 0 0 3px #fff, 0 0 0 3px #009600; }
	.categorias-tab[data-tab="marcas"].is-active { box-shadow: inset 0 0 0 3px #fff, 0 0 0 3px #0093a8; }
	.categorias-tab[data-tab="g3"].is-active { box-shadow: inset 0 0 0 3px #fff, 0 0 0 3px #6d28d9; }
	.categorias-tabs {
		align-items: center;
		justify-content: space-between;
	}
	.categorias-tabs-botones { display: flex; flex-wrap: wrap; gap: 0.4rem; }
	.categorias-filtro {
		display: inline-flex;
		align-items: center;
		gap: 0.2rem;
		padding: 0.25rem;
		background: #f1f5f9;
		border: 1px solid #e2e8f0;
		border-radius: 12px;
	}
	.categorias-filtro label {
		position: relative;
		margin: 0;
		cursor: pointer;
		display: inline-flex;
		align-items: center;
		padding: 0.5rem 0.95rem;
		border-radius: 9px;
		font-size: 0.9rem;
		font-weight: 700;
		color: #64748b;
		line-height: 1;
		transition: background .15s ease, color .15s ease, box-shadow .15s ease;
	}
	.categorias-filtro input { position: absolute; opacity: 0; pointer-events: none; }
	.categorias-filtro label:has(input:checked) {
		background: #0f172a;
		color: #fff;
		box-shadow: 0 1px 2px rgba(15, 23, 42, 0.18);
	}
	.categorias-pane { display: none; }
	.categorias-pane.is-active,
	.categorias-pane.is-active .categorias-pane { display: block; }
	.categorias-pane .content-widgets {
		border: 1px solid #e2e8f0;
		border-radius: 14px;
		overflow: hidden;
		background: #fff;
		box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
	}
	.categorias-pane .widget-head { display: block; }
	.categorias-pane .widget-head.cabeza-roja { background: #c0392b; }
	.categorias-pane .widget-head.cabeza-clasificacion { background: #6d28d9; }
	.categorias-pane .widget-container { padding: 0.5rem 0.75rem 1rem; background: #fff; }
	#drawerCrearGrupo {
		position: fixed; top: 0; right: 0; width: 420px; max-width: 100%; height: 100vh;
		background: #fff; z-index: 99999; transform: translateX(100%);
		transition: transform .3s ease; box-shadow: -8px 0 32px rgba(15,23,42,.15);
		display: flex; flex-direction: column;
		font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
	}
	#drawerCrearGrupo.is-open { transform: translateX(0); }
	#drawerGrupoOverlay { position: fixed; inset: 0; background: rgba(15,23,42,.45); z-index: 99998; opacity: 0; visibility: hidden; transition: opacity .3s ease; }
	#drawerGrupoOverlay.is-open { opacity: 1; visibility: visible; }
	#drawerCrearGrupo .drawer-header {
		display: flex; justify-content: space-between; align-items: center;
		padding: 1.15rem 1.35rem; color: #fff; background: #c0392b; flex-shrink: 0;
	}
	#drawerCrearGrupo[data-tono="g2"] .drawer-header { background: #009600; }
	#drawerCrearGrupo[data-tono="marcas"] .drawer-header { background: #0093a8; }
	#drawerCrearGrupo[data-tono="g3"] .drawer-header { background: #6d28d9; }
	#drawerCrearGrupo .drawer-header-title { margin: 0; font-size: 1.15rem; font-weight: 700; }
	#drawerCrearGrupo .drawer-cerrar {
		background: rgba(255,255,255,.18); border: 0; color: #fff; width: 36px; height: 36px;
		border-radius: 8px; font-size: 1.25rem; cursor: pointer;
	}
	#drawerCrearGrupo .drawer-body { padding: 1.35rem; display: flex; flex-direction: column; gap: 1rem; flex: 1; }
	#drawerCrearGrupo .drawer-intro { margin: 0; color: #64748b; font-size: 0.85rem; line-height: 1.45; }
	#drawerCrearGrupo .campo label {
		display: block; margin-bottom: 0.35rem; font-size: 0.75rem; font-weight: 600; color: #334155;
	}
	#drawerCrearGrupo .campo label .req { color: #dc2626; }
	#drawerCrearGrupo input[type="text"] {
		width: 100%; height: 40px; box-sizing: border-box; margin: 0;
		border: 1px solid #cbd5e1; border-radius: 8px; padding: 0 0.75rem;
		font-size: 0.9rem; color: #0f172a; background: #fff;
	}
	#drawerCrearGrupo input[type="text"]:focus { outline: none; border-color: #0f172a; box-shadow: 0 0 0 3px rgba(15,23,42,.08); }
	#drawerCrearGrupo .campo-check {
		display: flex; align-items: center; gap: 0.55rem; margin: 0;
		padding: 0.75rem 0.85rem; border: 1px solid #e2e8f0; border-radius: 10px; background: #f8fafc;
		font-size: 0.875rem; font-weight: 600; color: #0f172a; cursor: pointer;
	}
	#drawerCrearGrupo .drawer-error { color: #c0392b; min-height: 1.2rem; margin: 0; font-size: 0.85rem; }
	#drawerCrearGrupo .drawer-actions {
		margin-top: auto; display: flex; justify-content: flex-end; gap: 0.5rem;
		padding-top: 0.5rem; border-top: 1px solid #e2e8f0;
	}
	#drawerCrearGrupo .drawer-actions .btn { border-radius: 8px; font-weight: 600; }
	body.drawer-open { overflow: hidden; }
	.categorias-vacio {
		margin: 1.5rem 0.5rem;
		color: #64748b;
		text-align: center;
	}
</style>
<!--============j avascript===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.tablecloth.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script src="js/ZeroClipboard.js"></script>
<script src="js/dataTables.bootstrap.js"></script>
<script src="js/TableTools.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script type="text/javascript">
            /*$( function () {
		  // Set the classes that TableTools uses to something suitable for Bootstrap
		  $.extend( true, $.fn.DataTable.TableTools.classes, {
			  "container": "btn-group",
			  "buttons": {
				  "normal": "btn",
				  "disabled": "btn disabled"
			  },
			  "collection": {
				  "container": "DTTT_dropdown dropdown-menu",
				  "buttons": {
					  "normal": "",
					  "disabled": "disabled"
				  }
			  }
		  } );
		  // Have the collection use a bootstrap compatible dropdown
		  $.extend( true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
			  "collection": {
				  "container": "ul",
				  "button": "li",
				  "liner": "a"
			  }
		  } );
		  });
		  */
            $(function () {
                $('#data-table').dataTable({
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
                    /*"oTableTools": {
			"aButtons": [
				"copy",
				"print",
				{
					"sExtends":    "collection",
					"sButtonText": 'Save <span class="caret" />',
					"aButtons":    [ "csv", "xls", "pdf" ]
				}
			]
		}*/
                });
            });
            $(function () {
                $('.tbl-simple').dataTable({
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
                });
            });
			
			$(function () {
			$(".tbl-paper-theme").tablecloth({
          theme: "paper"
		   });
			});
			
		$(function () {
			$(".tbl-dark-theme").tablecloth({
          theme: "dark"
		   });
		});
			$(function () {
                $('.tbl-paper-theme,.tbl-dark-theme').dataTable({
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
                });
	

            });
        </script>

<script type="text/javascript">
  function productos(enviada){
  	  var campo = enviada.title;
	  var nombreCat = enviada.alt;
	  var producto = enviada.name;
	  var proceso = 3;
	  var valor = enviada.value;
	  $('#resp').empty().hide().html("Esperando...").show(1);
		datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo)+"&nombreCat="+(nombreCat);
			   $.ajax({
				   type: "POST",
				   url: "ajax/ajax-productos.php",
				   data: datos,
				   success: function(data){
				   $('#resp').empty().hide().html(data).show(1);
				   }
			   });
	}
	
	function grupoUno(enviada){
  	  var campo = enviada.title;
	  var nombreCat = enviada.alt;		
	  var producto = enviada.name;
	  var proceso = 4;
	  var valor = enviada.value;
	  $('#respG1').empty().hide().html("Esperando...").show(1);
		datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo)+"&nombreCat="+(nombreCat)
			   $.ajax({
				   type: "POST",
				   url: "ajax/ajax-productos.php",
				   data: datos,
				   success: function(data){
				   $('#respG1').empty().hide().html(data).show(1);
				   }
			   });
	}
	
	function grupoTres(enviada){
  	  var campo = enviada.title;
	  var nombreCat = enviada.alt;		
	  var producto = enviada.name;
	  var proceso = 5;
	  var valor = enviada.value;
	  $('#respG3').empty().hide().html("Esperando...").show(1);
		datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo)+"&nombreCat="+(nombreCat)
			   $.ajax({
				   type: "POST",
				   url: "ajax/ajax-productos.php",
				   data: datos,
				   success: function(data){
				   $('#respG3').empty().hide().html(data).show(1);
				   }
			   });
	}
</script>
</head>
<body class="clientes-page ver-habilitados">
<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
	<div class="main-wrapper">
			<div class="container-fluid clientes-page-inner">
				<div class="clientes-hero">
					<div>
						<h1 class="clientes-hero-title"><?= htmlspecialchars($paginaActual['pag_nombre'] ?? 'Categorías'); ?></h1>
						<p class="clientes-hero-subtitle">Grupos de productos y marcas</p>
					</div>
					<div class="clientes-hero-actions">
						<?php if (Modulos::validarRol([40], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
						<button type="button" class="btn btn-success" id="btnAbrirDrawerGrupo"><i class="icon-plus"></i> Agregar nuevo</button>
						<?php } ?>
					</div>
				</div>
				<?php include("includes/notificaciones.php");?>
			
			<div class="categorias-tabs" role="tablist">
				<div class="categorias-tabs-botones">
				<button type="button" class="categorias-tab is-active" data-tab="g1"><?= $ofimaActiva ? 'Línea' : 'grupo 1'; ?></button>
				<button type="button" class="categorias-tab" data-tab="g2"><?= $ofimaActiva ? 'Sublínea' : 'grupo 2'; ?></button>
				<button type="button" class="categorias-tab" data-tab="marcas"><?= $ofimaActiva ? 'Grupo' : 'marcas'; ?></button>
				<button type="button" class="categorias-tab" data-tab="g3"><?= $ofimaActiva ? 'Clasificación 1' : 'grupo 3'; ?></button>
				</div>
				<div class="categorias-filtro">
					<label><input type="radio" name="filtroHab" value="habilitados" checked> Habilitados</label>
					<label><input type="radio" name="filtroHab" value="deshabilitados"> Deshabilitados</label>
					<label><input type="radio" name="filtroHab" value="todos"> Todos</label>
				</div>
			</div>

			<div class="categorias-pane is-active" id="tab-g1">
			<span id="respG1"></span>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head cabeza-roja">
							<h3>GRUPO 1</h3>
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
							$conteoGrupo1 = [];
							$consultaConteoG1 = $conexionBdPrincipal->query("SELECT prod_grupo1 AS id, COUNT(*) AS total FROM productos WHERE prod_id_empresa='".$idEmpresa."' GROUP BY prod_grupo1");
							while ($consultaConteoG1 && ($filaConteo = mysqli_fetch_assoc($consultaConteoG1))) {
								$conteoGrupo1[(int) $filaConteo['id']] = (int) $filaConteo['total'];
							}
							$usuariosGrupo1 = [];
							$consultaUsuariosG1 = $conexionBdPrincipal->query("SELECT usr_id, usr_nombre FROM usuarios WHERE usr_id_empresa='".$idEmpresa."'");
							while ($consultaUsuariosG1 && ($filaUsuario = mysqli_fetch_assoc($consultaUsuariosG1))) {
								$usuariosGrupo1[(int) $filaUsuario['usr_id']] = $filaUsuario['usr_nombre'];
							}
							$consulta2 = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=1  AND catp_id_empresa='".$idEmpresa."'");
							$no = 1;
							$totalP2=0;
							while($res2 = mysqli_fetch_array($consulta2, MYSQLI_BOTH)){
								$numProductos2 = $conteoGrupo1[(int) $res2[0]] ?? 0;
								$totalP2 += $numProductos2;
								$usuario2 = ['usr_nombre' => $usuariosGrupo1[(int) ($res2['catp_usuario'] ?? 0)] ?? ''];
							?>
							<?php
								$habilitadaGrupo1 = !isset($res2['catp_habilitada']) || (int) $res2['catp_habilitada'] === 1;
							?>
							<tr class="<?= $habilitadaGrupo1 ? 'categoria-habilitada' : 'categoria-deshabilitada'; ?>" data-habilitada="<?= $habilitadaGrupo1 ? '1' : '0'; ?>">
								<td><?=$no;?></td>
								<td><?= $ofimaActiva ? htmlspecialchars((string) ($res2['catp_cod_grupo'] ?? ''), ENT_QUOTES, 'UTF-8') : $res2[0]; ?></td>
                                <td><?=$res2[1];?></td>
								<!--<td><?=$res[2];?></td>-->
								<td style="text-align: center;">
									<a href="productos.php?grupo1=<?=$res2[0];?>" data-toggle="tooltip" title="Productos"><?=$numProductos2;?></a>
								</td>
								<?php if (Modulos::validarRol([403], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
								<td>
										<span style="font-size: 9px;"><?= strtoupper((string) (is_array($usuario2) ? ($usuario2['usr_nombre'] ?? '') : '')); ?></span>
										<br><span style="font-size: 9px;"><?=$res2['catp_fecha'];?></span>
								</td>
								
								<td>
										<input type="text" title="prod_utilidad_minima" alt="catp_utilidad_minima" name="<?=$res2[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res2['catp_utilidad_minima'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_utilidad" alt="catp_utilidad_lista" name="<?=$res2[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res2['catp_utilidad_lista'];?>">
										
								</td>
								
								
								<td>
										<input type="text" title="prod_descuento1" alt="catp_dcto_max" name="<?=$res2[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res2['catp_dcto_max'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_descuento2" alt="catp_utilidad_dealer" name="<?=$res2[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res2['catp_utilidad_dealer'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_descuento_web" alt="catp_utilidad_web" name="<?=$res2[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res2['catp_utilidad_web'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_comision" alt="catp_comision" name="<?=$res2[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res2['catp_comision'];?>">
								</td>
								<?php } ?>
                                <td><h4>
																	<?php if (Modulos::validarRol([41], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
                                    <a href="#" class="js-editar-grupo" data-tab="g1" data-id="<?= (int) $res2[0]; ?>" data-nombre="<?= htmlspecialchars((string) $res2['catp_nombre'], ENT_QUOTES, 'UTF-8'); ?>" data-codigo="<?= htmlspecialchars((string) ($res2['catp_cod_grupo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" data-habilitada="<?= $habilitadaGrupo1 ? '1' : '0'; ?>" title="Editar"><i class="icon-edit"></i></a>
																	<?php } ?>
																	<?php if (Modulos::validarRol([62], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && false) {?>
                                    <a href="bd_delete/categoriasp-eliminar.php?id=<?=$res2[0];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
																	<?php } ?>
                                </h4></td>
							</tr>
                            <?php $no++;}?>
							</tbody>
							<tfoot>
								<tr style="font-weight: bold;">
									<td colspan="2">TOTAL</td>
									<td style="text-align: center;"><?=$totalP2;?></td>
									<td colspan="6">&nbsp;</td>
								</tr>	
							</tfoot>	
							</table>
						</div>
					</div>
				</div>
			</div>
			</div>

			<div class="categorias-pane" id="tab-g2"></div>
			<div class="categorias-pane" id="tab-marcas"></div>
			<div class="categorias-pane" id="tab-g3"></div>

			
			
	
		
			</div>
		</div>
	</div>
	<?php include("includes/pie.php");?>
</div>
<div id="drawerGrupoOverlay"></div>
<aside id="drawerCrearGrupo" aria-hidden="true">
	<div class="drawer-header">
		<h2 class="drawer-header-title" id="drawerGrupoTitulo">Nuevo grupo</h2>
		<button type="button" class="drawer-cerrar" id="cerrarDrawerGrupo" aria-label="Cerrar">&times;</button>
	</div>
	<form id="formDrawerGrupo" class="drawer-body">
		<input type="hidden" name="ajax" value="1">
		<input type="hidden" name="grupo" id="drawerGrupoValor" value="1">
		<input type="hidden" name="id" id="drawerRegistroId" value="">
		<p class="drawer-intro" id="drawerGrupoIntro">Completa los datos para agregarlo al listado.</p>
		<div class="campo">
			<label for="drawerNombre">Nombre <span class="req">*</span></label>
			<input type="text" name="nombre" id="drawerNombre" required placeholder="Nombre">
		</div>
		<div class="campo">
			<label for="drawerCodigo">Código<?php if ($ofimaActiva) { ?> <span class="req">*</span><?php } ?></label>
			<input type="text" name="cod_grupo" id="drawerCodigo" maxlength="20" placeholder="Código" <?php if ($ofimaActiva) { echo 'required'; } ?>>
		</div>
		<div id="drawerHabilitadoWrap">
			<label class="campo-check"><input type="checkbox" name="habilitada" value="1" checked> Habilitado</label>
		</div>
		<p class="drawer-error" id="drawerGrupoError"></p>
		<div class="drawer-actions">
			<button type="button" class="btn" id="cancelarDrawerGrupo">Cancelar</button>
			<button type="submit" class="btn btn-success">Guardar</button>
		</div>
	</form>
</aside>
<script>
	function filtrarHabilitados() {
		var modo = $('input[name="filtroHab"]:checked').val();
		$('body').toggleClass('ver-habilitados', modo === 'habilitados' || modo === 'todos');
		$('body').toggleClass('ver-no-habilitados', modo === 'deshabilitados' || modo === 'todos');
	}
	$('input[name="filtroHab"]').on('change', filtrarHabilitados);
	var tabsCargadas = { g1: true };
	function mostrarTab(tab) {
		$('.categorias-tab').removeClass('is-active');
		$('.categorias-tab[data-tab="' + tab + '"]').addClass('is-active');
		$('.categorias-pane').removeClass('is-active');
		$('#tab-' + tab).addClass('is-active');
		if (tab === 'g1' || tabsCargadas[tab]) {
			return;
		}
		var $pane = $('#tab-' + tab);
		$pane.html('<p class="categorias-vacio">Cargando...</p>');
		$.get('ajax/ajax-categorias-tab.php', { tab: tab }, function (html) {
			$pane.html(html);
			tabsCargadas[tab] = true;
		}).fail(function () {
			$pane.html('<p class="categorias-vacio">No se pudo cargar este listado.</p>');
		});
	}
	$('.categorias-tab').on('click', function () {
		mostrarTab($(this).data('tab'));
	});
	var tabInicial = new URLSearchParams(window.location.search).get('tab');
	if (tabInicial && tabInicial !== 'g1') {
		mostrarTab(tabInicial);
	}
	function tabActiva() {
		return $('.categorias-tab.is-active').data('tab') || 'g1';
	}
	function abrirDrawerGrupo(registro) {
		var tab = (registro && registro.tab) ? registro.tab : tabActiva();
		var editando = registro && registro.id;
		var titulosNuevo = { g1: 'Nueva línea', g2: 'Nueva sublínea', marcas: 'Nueva marca', g3: 'Nueva clasificación' };
		var titulosEditar = { g1: 'Editar línea', g2: 'Editar sublínea', marcas: 'Editar marca', g3: 'Editar clasificación' };
		var grupos = { g1: '1', g2: '2', g3: '3' };
		var intros = {
			g1: editando ? 'Actualiza los datos de la línea.' : 'Se creará en la línea.',
			g2: editando ? 'Actualiza los datos de la sublínea.' : 'Se creará en la sublínea.',
			marcas: editando ? 'Actualiza los datos de la marca.' : 'Se creará como marca.',
			g3: editando ? 'Actualiza los datos de la clasificación.' : 'Se creará en la clasificación.'
		};
		$('#drawerCrearGrupo').attr('data-tono', tab);
		$('#drawerGrupoTitulo').text((editando ? titulosEditar : titulosNuevo)[tab] || 'Grupo');
		$('#drawerGrupoIntro').text(intros[tab] || '');
		$('#drawerGrupoValor').val(grupos[tab] || '1');
		$('#drawerRegistroId').val(editando ? registro.id : '');
		$('#drawerHabilitadoWrap').show();
		$('#drawerNombre').val(editando ? registro.nombre : '');
		$('#drawerCodigo').val(editando ? registro.codigo : '');
		$('#drawerHabilitadoWrap input').prop('checked', editando ? registro.habilitada === '1' : true);
		$('#drawerGrupoError').text('');
		$('#drawerGrupoOverlay, #drawerCrearGrupo').addClass('is-open');
		$('body').addClass('drawer-open');
	}
	function cerrarDrawerGrupo() {
		$('#drawerGrupoOverlay, #drawerCrearGrupo').removeClass('is-open');
		$('body').removeClass('drawer-open');
	}
	$('#btnAbrirDrawerGrupo').on('click', function (e) {
		e.preventDefault();
		abrirDrawerGrupo();
	});
	$(document).on('click', '.js-editar-grupo', function (e) {
		e.preventDefault();
		abrirDrawerGrupo({
			id: $(this).data('id'),
			tab: $(this).data('tab'),
			nombre: $(this).attr('data-nombre') || '',
			codigo: $(this).attr('data-codigo') || '',
			habilitada: String($(this).data('habilitada'))
		});
	});
	$('#cerrarDrawerGrupo, #cancelarDrawerGrupo, #drawerGrupoOverlay').on('click', cerrarDrawerGrupo);
	$('#formDrawerGrupo').on('submit', function (e) {
		e.preventDefault();
		var tab = $('#drawerCrearGrupo').attr('data-tono') || tabActiva();
		var id = $('#drawerRegistroId').val();
		var editando = id !== '';
		var url;
		if (tab === 'marcas') {
			url = editando ? 'bd_update/marcas-actualizar.php' : 'bd_create/marcas-guardar.php';
		} else {
			url = editando ? 'bd_update/categoriasp-actualizar.php' : 'bd_create/categoriasp-guardar.php';
		}
		var datos = {
			ajax: '1',
			id: id,
			nombre: $('#drawerNombre').val(),
			habilitada: $('#drawerHabilitadoWrap input').is(':checked') ? '1' : '0'
		};
		if (tab === 'marcas') {
			datos.cod_ofima = $('#drawerCodigo').val();
		} else {
			datos.grupo = $('#drawerGrupoValor').val();
			datos.cod_grupo = $('#drawerCodigo').val();
		}
		$('#drawerGrupoError').text('');
		$.post(url, datos, function (resp) {
			if (resp && resp.success) {
				window.location.href = 'categoriasp.php?tab=' + encodeURIComponent(tab);
				return;
			}
			$('#drawerGrupoError').text((resp && resp.message) ? resp.message : 'No se pudo guardar.');
		}, 'json').fail(function () {
			$('#drawerGrupoError').text('No se pudo guardar.');
		});
	});
</script>
</body>
</html>