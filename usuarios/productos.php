<?php
include("sesion.php");

$idPagina = 36;

$tabla = 'productos';
$pk = 'prod_id';
include("includes/verificar-paginas.php");
include("includes/head.php");

require_once RUTA_PROYECTO.'/usuarios/class/Producto.php';
include_once RUTA_PROYECTO.'/usuarios/includes/api-ofima-conexion.php';
$ofimaProductosActiva = ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa);
?>
<!-- styles -->
<link href="css/tablecloth.css" rel="stylesheet">
<link href="css/clientes-listado.css" rel="stylesheet">
<!--============j avascript===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootbox.js"></script>
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
	$(function() {
		if ($('#data-table').length && !$('body').hasClass('clientes-page')) {
		$('#data-table').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
		});
		}
	});
	$(function() {
		$('.tbl-simple').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
		});
	});

	$(function() {
		$(".tbl-paper-theme").tablecloth({
			theme: "paper"
		});
	});

	$(function() {
		$(".tbl-dark-theme").tablecloth({
			theme: "dark"
		});
	});
	$(function() {
		$('.tbl-paper-theme,.tbl-dark-theme').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
		});


	});
</script>

<script type="text/javascript">
	function productos(enviada) {
		var campo    = enviada.title;
		var producto = enviada.name;
		var proceso  = 1;
		var valor    = enviada.value;

		var costo;
		var utilidad;
		var precioNuevo;
		var precioNuevoIva;
		var precioNuevoUSD;

		const descuentoDealer          = document.getElementById("descuentoDealer" + producto).value;
		const descuentoDealerSobreCien = (descuentoDealer / 100);
		const descuentoWeb             = document.getElementById("dctoWeb" + producto).value;
		const descuentoWebSobreCien    = (descuentoWeb / 100);       
		const utilidadActual           = document.getElementById("utilidad" + producto).value;
		const utilidadSobreCien        = (utilidadActual / 100);
		const costoActual              = document.getElementById("costo" + producto).value;
		const costoActualUSD           = document.getElementById("costoUSD" + producto).value;

		precioNuevo        = Math.round(parseFloat(costoActual) / (1 - parseFloat(utilidadSobreCien)));
		precioNuevoIva     = Math.round(parseFloat(precioNuevo) + (parseFloat(precioNuevo) * 0.19));

		precioNuevoUSD     = Math.round(parseFloat(costoActualUSD) / (1 - parseFloat(utilidadSobreCien)));

		const precioDealer = Math.round(precioNuevo - (precioNuevo * descuentoDealerSobreCien));
		const precioWeb    = Math.round(precioNuevo - (precioNuevo * descuentoWebSobreCien));

		if (campo == 'prod_utilidad' || campo == 'prod_costo') {
			document.getElementById("precioDealer" + producto).innerHTML = "$" + precioDealer.toLocaleString();
			document.getElementById("precioWeb" + producto).innerHTML = "$" + precioWeb.toLocaleString();
			document.getElementById("precioLista" + producto).innerHTML = "$" + precioNuevo.toLocaleString();
			document.getElementById("precioListaIva" + producto).innerHTML = "$" + precioNuevoIva.toLocaleString();
			document.getElementById("precioListaUSD" + producto).innerHTML = "$" + precioNuevoUSD.toLocaleString();

			const fields = ["precioDealer", "precioWeb", "precioLista", "precioListaIva", "precioListaUSD"];

			fields.forEach(field => {
				const element = document.getElementById(field + producto);

				element.style.backgroundColor = "yellow";
				setTimeout(() => {
					element.style.backgroundColor = "";
				}, 3000);
			});
		}

		if (campo == 'prod_descuento2') {
			document.getElementById("precioDealer" + producto).innerHTML = "$" + precioDealer.toLocaleString();
			document.getElementById("precioDealer" + producto).style.backgroundColor = "yellow"

			setTimeout(() => {
				document.getElementById("precioDealer" + producto).style.backgroundColor = "";
			}, 3000);
		}

		if (campo == 'prod_descuento_web') {
			document.getElementById("precioWeb" + producto).innerHTML = "$" + precioWeb.toLocaleString();
			document.getElementById("precioWeb" + producto).style.backgroundColor = "yellow"

			setTimeout(() => {
				document.getElementById("precioWeb" + producto).style.backgroundColor = "";
			}, 3000);
		}

		productosToast('Guardando cambios…', 'info');
		datos = "producto=" + (producto) + "&proceso=" + (proceso) + "&valor=" + (valor) + "&campo=" + (campo) + "&tabla=" + $("#tabla").val() + "&pk=" + $("#pk").val();
		$.ajax({
			type: "POST",
			url: "ajax/ajax-productos.php",
			data: datos,
			success: function(data) {
				productosMostrarRespuestaAjax(data);
			},
			error: function () {
				productosToast('Error de red al guardar los cambios.', 'error');
			}
		});
	}

	//PRODUCTOS PREDETERMINADOS
	function pred(enviada) {
		var valorActual = enviada.title;
		var producto = enviada.name;
		var proceso = 6;

		if (valorActual == 0) {
			document.getElementById("p" + producto).innerHTML = "SI";
			document.getElementById("p" + producto).title = 1;
		}

		if (valorActual == 1) {
			document.getElementById("p" + producto).innerHTML = "NO";
			document.getElementById("p" + producto).title = 0;
		}

		productosToast('Actualizando producto predeterminado…', 'info');
		datos = "producto=" + (producto) + "&proceso=" + (proceso) + "&valorActual=" + (valorActual);
		$.ajax({
			type: "POST",
			url: "ajax/ajax-productos.php",
			data: datos,
			success: function(data) {
				productosMostrarRespuestaAjax(data);
			},
			error: function () {
				productosToast('Error de red al actualizar el producto predeterminado.', 'error');
			}
		});
	}

	function productosToastOffsetY() {
		var nav = document.querySelector('.navbar .navbar-inner') || document.querySelector('.navbar-inner') || document.querySelector('.navbar');
		if (!nav) return 96;
		var bottom = nav.getBoundingClientRect().bottom;
		return Math.max(88, Math.round(bottom + 12));
	}

	function productosToast(texto, tipo) {
		tipo = tipo || 'info';
		var colores = {
			// Mismo verde del alert-success / VIS. WEB (#047857)
			success: '#047857',
			error: '#b91c1c',
			warning: '#b45309',
			info: '#334155'
		};
		var color = colores[tipo] || colores.info;
		var duracion = 4200;
		if (tipo === 'success') duracion = 7000;
		else if (tipo === 'error') duracion = 5500;
		else if (tipo === 'warning') duracion = 6000;
		if (typeof Toastify === 'function') {
			Toastify({
				text: String(texto || ''),
				duration: duracion,
				gravity: 'top',
				position: 'right',
				close: true,
				stopOnFocus: true,
				backgroundColor: color,
				offset: {
					x: 16,
					y: productosToastOffsetY()
				},
				style: {
					background: color,
					backgroundColor: color,
					color: '#fff',
					borderRadius: '10px',
					boxShadow: '0 12px 30px rgba(15, 23, 42, 0.25)',
					fontFamily: "'Plus Jakarta Sans', -apple-system, sans-serif",
					fontSize: '0.875rem',
					fontWeight: '600',
					maxWidth: '420px',
					padding: '12px 16px',
					zIndex: 10060
				}
			}).showToast();
			return;
		}
		var resp = document.getElementById('resp');
		if (resp) {
			resp.style.display = '';
			resp.innerHTML = '<span style="color:' + color + ';">' + String(texto || '') + '</span>';
		}
	}

	function productosMostrarRespuestaAjax(data) {
		var resp = document.getElementById('resp');
		if (resp) {
			resp.innerHTML = '';
			resp.style.display = 'none';
		}
		if (data == null || data === '') return;

		var $tmp = $('<div>').append($.parseHTML(String(data), document, true));
		var $alert = $tmp.find('.alert').first();
		if ($alert.length) {
			var tipo = 'info';
			if ($alert.hasClass('alert-success')) tipo = 'success';
			else if ($alert.hasClass('alert-danger') || $alert.hasClass('alert-error')) tipo = 'error';
			else if ($alert.hasClass('alert-warning')) tipo = 'warning';
			var texto = ($alert.text() || '').replace(/×/g, ' ').replace(/\s+/g, ' ').trim();
			texto = texto.replace(/^Exito!\s*/i, 'Éxito: ').replace(/^Éxito!\s*/i, 'Éxito: ');
			if (texto) productosToast(texto, tipo);
			$alert.remove();
		}

		var leftover = ($tmp.html() || '').trim();
		if (!leftover) return;

		// Conserva scripts u HTML residual sin volver a mostrar el alert en #resp
		var $holder = $('<div style="display:none"></div>').appendTo(document.body);
		$holder.html(leftover);
		setTimeout(function () { $holder.remove(); }, 0);

		var textoPlano = $tmp.text().replace(/\s+/g, ' ').trim();
		if (!$alert.length && textoPlano) {
			productosToast(textoPlano, 'info');
		}
	}

	window.productosToast = productosToast;
	window.productosMostrarRespuestaAjax = productosMostrarRespuestaAjax;

	function visweb(enviada) {
		var valorActual = parseInt(enviada.title, 10) === 1 ? 1 : 0;
		var producto = enviada.name;
		var el = document.getElementById('vw' + producto);
		var estadoOptimistic = valorActual === 0 ? 1 : 0;

		if (el) {
			el.innerHTML = estadoOptimistic === 1 ? 'SI' : 'NO';
			el.title = String(estadoOptimistic);
		}

		productosToast('Actualizando visible en la web…', 'info');

		$.ajax({
			type: 'POST',
			url: 'ajax/ajax-productos.php',
			dataType: 'json',
			data: {
				producto: producto,
				proceso: 10,
				valorActual: valorActual
			},
			success: function (data) {
				if (!data) {
					productosToast('No se pudo actualizar la visibilidad en la web.', 'error');
					if (el) {
						el.innerHTML = valorActual === 1 ? 'SI' : 'NO';
						el.title = String(valorActual);
					}
					return;
				}
				var estadoFinal = data.estado === null || data.estado === undefined
					? valorActual
					: (parseInt(data.estado, 10) === 1 ? 1 : 0);
				if (el) {
					el.innerHTML = estadoFinal === 1 ? 'SI' : 'NO';
					el.title = String(estadoFinal);
				}
				productosToast(data.message || 'Visible en la web actualizado.', data.tipo || (data.success ? 'success' : 'error'));
			},
			error: function () {
				if (el) {
					el.innerHTML = valorActual === 1 ? 'SI' : 'NO';
					el.title = String(valorActual);
				}
				productosToast('Error de red al actualizar la visibilidad en la web.', 'error');
			}
		});
	}
</script>

<?php include("includes/funciones-js.php"); ?>
<style>
	.productos-encabezado {
		display: flex;
		align-items: stretch;
		gap: 1rem;
		margin-bottom: 1.5rem;
	}
	.productos-encabezado .clientes-hero {
		flex: 1 1 auto;
		margin-bottom: 0;
		align-items: center;
		box-sizing: border-box;
	}
	.productos-hero-vendido {
		flex: 0 0 280px;
		width: 280px;
		max-width: 280px;
		margin: 0;
		overflow: hidden;
		border-radius: 12px;
		font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
		box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
	}
	.productos-hero-vendido .board-widgets-head {
		background: #f8fafc;
		padding: 0.45rem 0.75rem;
	}
	.productos-hero-vendido .board-widgets-head h4 {
		font-family: inherit;
		font-size: 0.7rem;
		font-weight: 700;
		letter-spacing: 0.02em;
		text-transform: uppercase;
		color: #475569;
	}
	.productos-hero-vendido .board-widgets-content {
		text-align: center;
		color: #fff;
		padding: 0.55rem 0.75rem 0.65rem;
	}
	.productos-hero-vendido .n-counter {
		display: block;
		font-size: 1.75rem;
		font-weight: 700;
		line-height: 1.1;
		font-family: inherit;
	}
	.productos-hero-vendido .n-sources {
		display: block;
		font-size: 0.75rem;
		opacity: 0.9;
	}
	.productos-hero-vendido .board-widgets-botttom a {
		height: auto;
		min-height: 0;
		line-height: 1.35;
		padding: 0.55rem 1.75rem 0.55rem 0.75rem;
		white-space: normal;
		font-family: inherit;
		font-size: 0.75rem;
		font-weight: 600;
	}
	.productos-hero-vendido .board-widgets-botttom a i {
		font-size: 1rem;
		line-height: 1;
		height: auto;
		width: auto;
		top: 50%;
		right: 0.55rem;
		transform: translateY(-50%);
	}
	.productos-icono-ofima {
		display: inline-block;
		margin-left: 0.15rem;
		cursor: default;
		line-height: 1;
	}
	.productos-icono-ofima.is-ok { color: #16a34a; }
	.productos-icono-ofima.is-off { color: #94a3b8; }
	@media (max-width: 900px) {
		.productos-encabezado { flex-direction: column; align-items: stretch; }
		.productos-hero-vendido { flex: 1 1 auto; max-width: none; width: 100%; }
		.productos-filtros-buscar { grid-template-columns: 1fr; }
	}
	.clientes-page .clientes-panel { overflow: visible; border-radius: 14px; }
	.clientes-page .clientes-panel-header { border-radius: 13px 13px 0 0; }
	.clientes-page .clientes-panel-body { border-radius: 0 0 13px 13px; }
	.clientes-page .clientes-busqueda-principal {
		display: flex;
		align-items: center;
		gap: 0.75rem;
		margin-bottom: 0.45rem;
		padding: 0.4rem 0.75rem;
	}
	.clientes-page .clientes-busqueda-label {
		display: block;
		margin: 0;
		line-height: 1;
		white-space: nowrap;
	}
	.clientes-page .clientes-busqueda-hint { display: none; }
	.clientes-page .clientes-busqueda-input-wrap { flex: 1; margin: 0; }
	.clientes-page .clientes-busqueda-icon {
		top: 50%;
		left: 0.7rem;
		transform: translateY(-50%);
		line-height: 1;
	}
	.clientes-page .clientes-busqueda-principal input[type="search"] {
		height: 36px;
		min-height: 36px;
		margin: 0;
		padding: 0 5.2rem 0 2.15rem;
		line-height: 36px;
		font-size: 0.875rem;
	}
	.clientes-page .clientes-busqueda-btn { height: 28px; line-height: 28px; }
	.clientes-page .clientes-leyenda { margin: 0 0 0.4rem; text-align: left; }
	.clientes-page .clientes-table-wrap { max-height: calc(100vh - 220px); overflow: auto; }
	.clientes-page .clientes-table { font-size: 0.7rem; }
	.clientes-page .clientes-table thead th {
		position: sticky;
		top: 0;
		z-index: 5;
		background: #f8fafc;
		white-space: nowrap;
		font-size: 0.62rem;
		padding: 0.4rem 0.35rem;
		letter-spacing: 0.02em;
	}
	.clientes-page .clientes-table th,
	.clientes-page .clientes-table td { border-right: 1px solid #e2e8f0; }
	.clientes-page .clientes-table th:last-child,
	.clientes-page .clientes-table td:last-child { border-right: 0; }
	.clientes-page .clientes-table tbody td,
	.clientes-page .clientes-table thead th { text-align: center; vertical-align: middle !important; }
	.clientes-page .clientes-table tbody td span[style*="visibility: hidden"],
	.clientes-page .clientes-table tbody td span[style*="visibility:hidden"] {
		position: absolute !important;
		width: 0 !important;
		height: 0 !important;
		overflow: hidden !important;
	}
	.clientes-page .clientes-table tbody td { padding: 0.35rem 0.35rem; }
	.clientes-page .clientes-table tbody tr:nth-child(even) td,
	.clientes-page .clientes-table tbody tr:nth-child(even):hover td { background: #f1f5f9; }
	.clientes-page .clientes-table tbody tr:nth-child(odd) td,
	.clientes-page .clientes-table tbody tr:nth-child(odd):hover td { background: #fff; }
	.clientes-page .clientes-table tbody tr:hover { background: transparent; }
	.clientes-page .clientes-table th[data-col="codigo"],
	.clientes-page .clientes-table td.col-codigo {
		position: relative;
		box-sizing: border-box;
		width: var(--ancho-codigo, 93px);
		min-width: var(--ancho-codigo, 93px);
		max-width: var(--ancho-codigo, 93px);
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	.clientes-page .clientes-table td.col-codigo .codigo-producto {
		display: block;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		font-weight: 700;
		text-align: center;
	}
	.clientes-page .clientes-table th[data-col="nombre"],
	.clientes-page .clientes-table td.col-nombre {
		position: relative;
		width: var(--ancho-nombre, 220px);
		min-width: var(--ancho-nombre, 220px);
		max-width: var(--ancho-nombre, 220px);
		text-align: left;
		white-space: nowrap;
		overflow: hidden;
	}
	.clientes-page .clientes-table td.col-nombre .columnas1 { width: auto !important; max-width: none; white-space: nowrap; overflow: hidden; }
	.col-nombre-agarre,
	.col-codigo-agarre { position: absolute; top: 0; right: 0; width: 8px; height: 100%; cursor: col-resize; z-index: 2; }
	.clientes-page .clientes-table td.col-nombre h4 {
		display: flex;
		justify-content: center;
		align-items: center;
		gap: 0.35rem;
		width: 100%;
		margin: 0.2rem 0 0;
	}
	.clientes-page .clientes-table td.col-nombre h4 a {
		display: inline-block;
		transform-origin: center;
		transition: transform .15s ease;
	}
	.clientes-page .clientes-table td.col-nombre h4 a:hover { transform: scale(1.45); }
	.clientes-page .clientes-table input[type="text"] { width: 52px !important; font-size: 0.7rem; padding: 0.15rem; }
	.precio-par { display: inline-flex; flex-direction: column; border: 1px solid #94a3b8; line-height: 1.2; background: #fff; }
	.precio-par-fila { display: flex; align-items: center; gap: 0.35rem; margin: 0; padding: 0 0.35rem; border-bottom: 1px solid #94a3b8; font-weight: 600; color: #0f172a; text-transform: none; letter-spacing: 0; }
	.precio-par-fila:last-child { border-bottom: 0; }
	.precio-par-fila span { flex: 0 0 auto; }
	.clientes-page .clientes-table .precio-par input[type="text"] { width: 78px !important; height: 22px; margin: 0; padding: 0; border: 0; background: #fff !important; text-align: right; box-shadow: none; }
	.clientes-page .clientes-busqueda-principal .productos-columnas { flex: 0 0 auto; }
	.clientes-page .clientes-busqueda-principal #btnColumnasProducto {
		height: 36px;
		color: #334155;
		background: #fff;
		border: 1px solid #cbd5e1;
		white-space: nowrap;
	}
	.clientes-page .clientes-busqueda-principal #btnColumnasProducto:hover { background: #f1f5f9; color: #0f172a; }
	.productos-columnas { position: relative; }
	.productos-columnas-panel {
		display: none;
		position: absolute;
		right: 0;
		top: calc(100% + 6px);
		z-index: 40;
		width: 230px;
		max-height: 320px;
		overflow: auto;
		padding: 0.45rem 0.7rem;
		background: #fff;
		border: 1px solid #e2e8f0;
		border-radius: 10px;
		box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
	}
	.productos-columnas.is-open .productos-columnas-panel { display: block; }
	.productos-columnas-panel label {
		display: flex;
		align-items: center;
		gap: 0.4rem;
		margin: 0;
		padding: 0.28rem 0;
		color: #334155;
		font-size: 0.75rem;
		font-weight: 600;
		letter-spacing: 0;
		text-transform: none;
		cursor: pointer;
	}
	.productos-mas-opciones { position: relative; }
	.productos-mas-opciones .dropdown-menu {
		right: 0;
		left: auto;
		top: calc(100% + 6px);
		float: none;
		min-width: 240px;
		margin: 0;
		padding: 0.35rem 0;
		background: #fff;
		border: 1px solid #e2e8f0;
		border-radius: 10px;
		box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
		font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
		z-index: 40;
	}
	.productos-mas-opciones .dropdown-menu > li > a {
		display: block;
		padding: 0.5rem 0.85rem;
		color: #0f172a;
		font-size: 0.8rem;
		font-weight: 600;
		line-height: 1.35;
		white-space: normal;
	}
	.productos-mas-opciones .dropdown-menu > li > a:hover,
	.productos-mas-opciones .dropdown-menu > li > a:focus {
		background: #ecfdf5;
		color: #047857;
		text-decoration: none;
	}
	.confirmacion-producto {
		position: fixed;
		inset: 0;
		z-index: 100050;
		display: none;
		align-items: center;
		justify-content: center;
		padding: 1rem;
		background: rgba(15, 23, 42, 0.45);
	}
	.confirmacion-producto.is-open { display: flex; }
	.confirmacion-producto-caja {
		width: min(440px, 92vw);
		background: #fff;
		border-radius: 14px;
		box-shadow: 0 24px 60px rgba(15, 23, 42, 0.28);
		font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
		overflow: hidden;
	}
	.confirmacion-producto-caja p {
		margin: 0;
		padding: 1.4rem 1.4rem 0.5rem;
		font-size: 1rem;
		font-weight: 600;
		line-height: 1.45;
		color: #0f172a;
	}
	.confirmacion-producto-acciones {
		display: flex;
		justify-content: flex-end;
		gap: 0.5rem;
		padding: 0.85rem 1.1rem;
		background: #f8fafc;
		border-top: 1px solid #e2e8f0;
	}
	.confirmacion-producto-acciones button {
		border-radius: 8px;
		font-weight: 600;
		padding: 0.45rem 0.9rem;
		cursor: pointer;
		font-family: inherit;
	}
	#confirmacionProductoCancelar { border: 1px solid #cbd5e1; background: #fff; color: #334155; }
	#confirmacionProductoAceptar { border: 0; background: #1d4ed8; color: #fff; }
	.clientes-page .clientes-panel-header {
		align-items: center;
		flex-wrap: wrap;
		row-gap: 0.65rem;
	}
	.clientes-page .clientes-panel-header > div:first-child {
		display: flex;
		flex: 0 0 auto;
		flex-direction: column;
		align-items: flex-start;
		gap: 0.1rem;
		min-width: 0;
		margin-right: 1rem;
	}
	.clientes-page .clientes-panel-header > div:first-child h3,
	.clientes-page .clientes-panel-header > div:first-child p {
		white-space: nowrap;
	}
	.clientes-page .clientes-panel-header > div:first-child p {
		margin: 0;
		line-height: 1.3;
	}
	.clientes-page .clientes-panel-toolbar { align-items: flex-end; }
	.clientes-panel-header .productos-filtros-buscar {
		display: flex;
		flex: 1 1 720px;
		flex-wrap: nowrap;
		justify-content: flex-end;
		align-items: flex-end;
		gap: 0.4rem;
		margin: 0 0 0 auto;
		min-width: min(100%, 720px);
		max-width: 100%;
		grid-template-columns: none;
	}
	.clientes-panel-header .productos-filtros-buscar > .clientes-panel-toolbar {
		align-self: flex-end;
		flex: 0 0 auto;
		flex-wrap: nowrap;
		margin: 0 0 0 0.15rem;
		height: 33px;
	}
	.clientes-panel-header .productos-filtros-buscar .clientes-toolbar-btn {
		height: 33px;
		box-sizing: border-box;
		padding-top: 0;
		padding-bottom: 0;
		display: inline-flex;
		align-items: center;
	}
	.clientes-panel-header .productos-filtro {
		width: auto;
		flex: 1 1 108px;
		min-width: 96px;
		max-width: 158px;
	}
	@media (max-width: 1280px) {
		.clientes-panel-header .productos-filtros-buscar {
			flex: 1 1 100%;
			flex-wrap: wrap;
			justify-content: flex-start;
			min-width: 100%;
			margin-left: 0;
		}
		.clientes-page .clientes-panel-header > div:first-child h3,
		.clientes-page .clientes-panel-header > div:first-child p {
			white-space: normal;
		}
	}
	.clientes-panel-header .productos-filtro label { color: rgba(255, 255, 255, 0.85); margin-bottom: 0.15rem; font-size: 0.6rem; }
	.clientes-panel-header .productos-filtro-input { height: 33px; min-height: 33px; padding: 0.2rem 0.45rem; font-size: 0.75rem; border-radius: 8px; color: #0f172a; background: #fff; box-sizing: border-box; }
	.clientes-page .clientes-table th.col-sel,
	.clientes-page .clientes-table td.col-sel {
		width: 42px;
		min-width: 42px;
		text-align: center;
		vertical-align: middle;
	}
	.productos-sel-label {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		margin: 0;
		cursor: pointer;
	}
	.productos-sel-label input {
		width: 16px;
		height: 16px;
		margin: 0;
		accent-color: #047857;
		cursor: pointer;
	}
	.clientes-table tr.is-selected {
		background: rgba(16, 185, 129, 0.08);
	}
	.clientes-page .clientes-table th[data-col="web"],
	.clientes-page .clientes-table td[data-col="web"],
	.clientes-page .clientes-table td.col-web {
		width: 104px;
		min-width: 104px;
		max-width: 104px;
		box-sizing: border-box;
		vertical-align: middle;
		overflow: hidden;
	}
	.productos-web-head {
		display: flex;
		flex-direction: column;
		align-items: stretch;
		gap: 0.25rem;
		line-height: 1.15;
		width: 100%;
		max-width: 104px;
		box-sizing: border-box;
	}
	.productos-web-head-label {
		font-size: 0.6875rem;
		font-weight: 700;
		letter-spacing: 0.03em;
		white-space: nowrap;
	}
	.productos-web-bulk-select {
		display: none;
		width: 100%;
		max-width: 100%;
		min-width: 0;
		height: 26px;
		border: 1px solid #059669;
		border-radius: 6px;
		background: #fff;
		color: #0f172a;
		font-size: 0.6875rem;
		font-weight: 600;
		padding: 0 0.2rem;
		box-sizing: border-box;
		box-shadow: 0 0 0 1px rgba(5, 150, 105, 0.2);
	}
	.productos-web-bulk-select.is-visible {
		display: block;
	}
	.productos-web-bulk-select:disabled {
		opacity: 0.6;
		cursor: wait;
	}
	.productos-bulk-bar {
		position: fixed;
		left: 50%;
		bottom: 22px;
		transform: translateX(-50%) translateY(120%);
		z-index: 1200;
		display: flex;
		align-items: center;
		gap: 0.75rem;
		flex-wrap: wrap;
		padding: 0.7rem 0.9rem;
		border-radius: 14px;
		background: #0f172a;
		color: #fff;
		box-shadow: 0 12px 40px rgba(15, 23, 42, 0.35);
		opacity: 0;
		pointer-events: none;
		transition: transform 0.22s ease, opacity 0.22s ease;
		max-width: calc(100vw - 24px);
	}
	.productos-bulk-bar.is-open {
		transform: translateX(-50%) translateY(0);
		opacity: 1;
		pointer-events: auto;
	}
	.productos-bulk-count {
		font-size: 0.875rem;
		font-weight: 600;
		white-space: nowrap;
	}
	.productos-bulk-count span { color: #6ee7b7; }
	.productos-bulk-msg {
		font-size: 0.875rem;
		font-weight: 600;
		margin: 0;
		flex: 1 1 180px;
		min-width: 0;
		line-height: 1.35;
	}
	.productos-bulk-actions {
		display: flex;
		gap: 0.4rem;
		flex-wrap: wrap;
	}
	.productos-bulk-btn {
		border: 0;
		border-radius: 9px;
		padding: 0.45rem 0.75rem;
		font-size: 0.8125rem;
		font-weight: 600;
		cursor: pointer;
		font-family: inherit;
	}
	.productos-bulk-btn:disabled {
		opacity: 0.55;
		cursor: wait;
	}
	.productos-bulk-btn.is-si { background: #059669; color: #fff; }
	.productos-bulk-btn.is-no { background: #e2e8f0; color: #0f172a; }
	.productos-bulk-btn.is-clear { background: transparent; color: #94a3b8; border: 1px solid #334155; }
	.productos-bulk-hint {
		font-size: 0.75rem;
		color: #94a3b8;
		margin: 0;
		width: 100%;
	}
	.productos-filtro label {
		display: block;
		margin-bottom: 0.35rem;
		font-size: 0.6875rem;
		font-weight: 700;
		letter-spacing: 0.04em;
		text-transform: uppercase;
		color: #64748b;
	}
	.productos-filtro { position: relative; }
	.productos-filtro select.js-filtro-buscar {
		position: absolute;
		width: 1px;
		height: 1px;
		opacity: 0;
		pointer-events: none;
	}
	.productos-filtro-input {
		width: 100%;
		min-height: 42px;
		padding: 0.55rem 0.75rem;
		border: 1px solid #cbd5e1;
		border-radius: 8px;
		font-size: 0.875rem;
		background: #fff;
		box-sizing: border-box;
		font-family: inherit;
	}
	.productos-filtro-input:focus {
		outline: none;
		border-color: #6366f1;
		box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
	}
	.productos-filtro-lista {
		display: none;
		position: absolute;
		z-index: 30;
		left: 0;
		right: 0;
		top: calc(100% - 2px);
		max-height: 220px;
		margin: 0;
		padding: 0.25rem 0;
		list-style: none;
		overflow: auto;
		background: #fff;
		border: 1px solid #cbd5e1;
		border-radius: 8px;
		box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
	}
	.productos-filtro-lista.is-open { display: block; }
	.productos-filtro-lista,
	.productos-filtro-lista li { color: #0f172a; }
	.productos-filtro-lista li { padding: 0.45rem 0.75rem; cursor: pointer; font-size: 0.875rem; }
	.productos-filtro-lista li:hover,
	.productos-filtro-lista li.is-elegida { background: #e2e8f0; color: #0f172a; }
	.productos-filtro-lista li.is-vacio { color: #94a3b8; cursor: default; }
	#drawerGaleriaOverlay { position: fixed; inset: 0; background: rgba(15,23,42,.45); z-index: 99998; opacity: 0; visibility: hidden; }
	#drawerGaleriaOverlay.is-open { opacity: 1; visibility: visible; }
	#drawerGaleriaProducto { position: fixed; top: 0; right: 0; width: min(720px, 90vw); height: 100vh; background: #fff; z-index: 99999; display: flex; flex-direction: column; transform: translateX(100%); transition: transform .35s ease; box-shadow: -8px 0 32px rgba(15,23,42,.15); }
	#drawerGaleriaProducto.is-open { transform: translateX(0); }
	#drawerGaleriaProducto .drawer-header { display: flex; align-items: center; justify-content: space-between; padding: 1.1rem 1.25rem; color: #fff; background: #0f766e; }
	#drawerGaleriaProducto .drawer-header h2 { margin: 0; font-size: 1.1rem; }
	#drawerGaleriaProducto .drawer-close { background: rgba(255,255,255,.15); border: 0; color: #fff; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; }
	#drawerGaleriaProducto .drawer-body { flex: 1; overflow: auto; padding: 1rem 1.25rem; }
	#drawerGaleriaProducto .galeria-subir { display: flex; gap: .5rem; align-items: center; margin-bottom: 1rem; }
	#drawerGaleriaProducto .galeria-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: .75rem; }
	#drawerGaleriaProducto .galeria-item { border: 1px solid #e2e8f0; border-radius: 10px; padding: .5rem; background: #f8fafc; }
	#drawerGaleriaProducto .galeria-item img { width: 100%; height: 120px; object-fit: contain; background: #fff; border-radius: 8px; }
	#drawerGaleriaProducto .galeria-item button { margin-top: .4rem; width: 100%; border: 0; border-radius: 8px; background: #fee2e2; color: #991b1b; font-weight: 600; cursor: pointer; padding: .35rem; }
	#drawerGaleriaProducto .galeria-vacio { color: #64748b; margin: 0; }
	#drawerHistorialOverlay { position: fixed; inset: 0; background: rgba(15,23,42,.45); z-index: 99998; opacity: 0; visibility: hidden; }
	#drawerHistorialOverlay.is-open { opacity: 1; visibility: visible; }
	#drawerHistorialProducto { position: fixed; top: 0; right: 0; width: min(860px, 92vw); height: 100vh; background: #fff; z-index: 99999; display: flex; flex-direction: column; transform: translateX(100%); transition: transform .35s ease; box-shadow: -8px 0 32px rgba(15,23,42,.15); }
	#drawerHistorialProducto.is-open { transform: translateX(0); }
	#drawerHistorialProducto .drawer-header { display: flex; align-items: center; justify-content: space-between; padding: 1.1rem 1.25rem; color: #fff; background: #1d4ed8; }
	#drawerHistorialProducto .drawer-header h2 { margin: 0; font-size: 1.1rem; }
	#drawerHistorialProducto .drawer-close { background: rgba(255,255,255,.15); border: 0; color: #fff; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; }
	#drawerHistorialProducto .drawer-body { flex: 1; overflow: auto; padding: 1rem 1.25rem; }
	#drawerHistorialProducto table { width: 100%; border-collapse: collapse; font-size: .8125rem; }
	#drawerHistorialProducto th, #drawerHistorialProducto td { padding: .45rem .5rem; border-bottom: 1px solid #e2e8f0; text-align: left; }
	#drawerHistorialProducto th { position: sticky; top: 0; background: #f8fafc; color: #475569; }
	#drawerHistorialProducto .historial-vacio { color: #64748b; margin: 0; }
	#drawerBodegasOverlay { position: fixed; inset: 0; background: rgba(15,23,42,.45); z-index: 100000; opacity: 0; visibility: hidden; }
	#drawerBodegasOverlay.is-open { opacity: 1; visibility: visible; }
	#drawerBodegasProducto { position: fixed; top: 0; right: 0; width: min(760px, 92vw); height: 100vh; background: #fff; z-index: 100001; display: flex; flex-direction: column; transform: translateX(100%); transition: transform .35s ease; box-shadow: -8px 0 32px rgba(15,23,42,.15); }
	#drawerBodegasProducto.is-open { transform: translateX(0); }
	#drawerBodegasProducto .drawer-header { display: flex; align-items: center; justify-content: space-between; padding: 1.1rem 1.25rem; color: #fff; background: #b45309; }
	#drawerBodegasProducto .drawer-header h2 { margin: 0; font-size: 1.1rem; }
	#drawerBodegasProducto .drawer-close { background: rgba(255,255,255,.15); border: 0; color: #fff; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; }
	#drawerBodegasProducto .drawer-body { flex: 1; overflow: auto; padding: 1rem 1.25rem; }
	#drawerBodegasProducto table { width: 100%; border-collapse: collapse; font-size: .8125rem; }
	#drawerBodegasProducto th, #drawerBodegasProducto td { padding: .45rem .5rem; border-bottom: 1px solid #e2e8f0; text-align: left; }
	#drawerBodegasProducto th { position: sticky; top: 0; background: #f8fafc; color: #475569; }
	#drawerBodegasProducto .bodegas-vacio, #drawerBodegasProducto .bodegas-aviso { color: #64748b; margin: 0 0 .75rem; }
	#drawerBodegasProducto input[type="number"] { width: 70px; text-align: center; }
	.productos-paginacion {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: space-between;
		gap: 0.75rem 1rem;
		margin-top: 0.75rem;
		padding: 0.5rem 0.75rem;
		font-size: 0.8125rem;
		line-height: 1;
		color: #475569;
		background: #f8fafc;
		border: 1px solid #e2e8f0;
		border-radius: 10px;
	}
	.productos-paginacion-grupo,
	.productos-paginacion form {
		display: flex;
		align-items: center;
		gap: 0.4rem;
		margin: 0;
	}
	.productos-paginacion label { margin: 0; line-height: 34px; }
	.productos-paginacion select,
	.productos-paginacion input[type="number"],
	.productos-paginacion button,
	.productos-paginacion a,
	.productos-paginacion .es-actual {
		box-sizing: border-box;
		height: 34px;
		min-height: 34px;
		margin: 0;
		padding: 0 0.7rem;
		border-radius: 8px;
		font-size: 0.8125rem;
		line-height: 32px;
		vertical-align: middle;
	}
	.productos-paginacion select,
	.productos-paginacion input[type="number"] {
		border: 1px solid #cbd5e1;
		background: #fff;
		color: #0f172a;
	}
	.productos-paginacion input[type="number"] { width: 4.2rem; text-align: center; }
	.productos-paginacion a,
	.productos-paginacion .es-actual,
	.productos-paginacion .es-puntos {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 34px;
		border: 1px solid #cbd5e1;
		background: #fff;
		color: #334155;
		text-decoration: none;
		font-weight: 600;
	}
	.productos-paginacion a:hover { background: #e2e8f0; color: #0f172a; }
	.productos-paginacion .es-actual { background: #1d4ed8; border-color: #1d4ed8; color: #fff; }
	.productos-paginacion .es-puntos { border: 0; background: transparent; min-width: 1.2rem; height: auto; }
	.productos-paginacion button {
		border: 0;
		background: #1d4ed8;
		color: #fff;
		font-weight: 600;
		cursor: pointer;
	}
</style>

<?php
$columna = '';
if (Modulos::validarRol([400], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
	$columna = 'columnas1';
?>

	<style type="text/css">
		#scrolly {
			width: 1400px;
			height: 600px;
			overflow: auto;
			overflow-y: auto;
			margin: 0 auto;
			white-space: nowrap
		}

		.columnas1 {
			width: 200px !important;
			/*text-overflow:ellipsis;*/
			white-space: nowrap;
			overflow: auto;
		}
	</style>

<?php
}
?>


</head>

<body class="clientes-page">
	<?php if (Modulos::validarRol([37], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
		include("includes/drawer-crear-producto.php");
	} ?>

	<input type="hidden" value="<?= $tabla; ?>" name="tabla" id="tabla">
	<input type="hidden" value="<?= $pk; ?>" name="pk" id="pk">

	<div class="layout">
		<?php include("includes/encabezado.php"); ?>

		

		<div class="main-wrapper">
			<div class="container-fluid clientes-page-inner">
				<?php
				$productoMasVendido = Producto::productoMasVendido($conexionBdPrincipal);
				$busquedaProducto = isset($_GET['busqueda']) ? (string) $_GET['busqueda'] : '';
				$tamanosPaginaProducto = [10, 25, 50, 100, 200];
				$porPaginaProducto = (isset($_GET['por']) && in_array((int) $_GET['por'], $tamanosPaginaProducto, true)) ? (int) $_GET['por'] : 10;
				?>

				<div class="productos-encabezado">
				<?php if (!empty($productoMasVendido['nombre_producto'])) { ?>
				<div class="productos-hero-vendido board-widgets magenta">
							<div class="board-widgets-head clearfix">
						<h4 class="pull-left"><i class="icon-certificate"></i> Producto más vendido este año</h4>
							</div>
							<div class="board-widgets-content">
						<span class="n-counter"><?= (int) $productoMasVendido['total_unidades_vendidas']; ?></span><span class="n-sources">Unidades</span>
							</div>
							<div class="board-widgets-botttom">
						<a href="#" class="js-editar-producto" data-id="<?= (int) $productoMasVendido['id_producto']; ?>"><?= htmlspecialchars($productoMasVendido['nombre_producto']); ?><i class="icon-double-angle-right"></i></a>
							</div>
						</div>
				<?php } ?>
				<div class="clientes-hero">
					<div>
						<h1 class="clientes-hero-title"><?= htmlspecialchars($paginaActual['pag_nombre'] ?? 'Productos'); ?></h1>
						<p class="clientes-hero-subtitle">Catálogo, precios y existencias</p>
					</div>
					<div class="clientes-hero-actions">
						<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
												<?php if (Modulos::validarRol([37], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
							<a href="#" class="btn btn-success js-abrir-crear-producto" id="btnCrearProductoRapido" aria-haspopup="dialog"><i class="icon-plus"></i> Agregar nuevo</a>
													<?php } ?>
													<?php if (Modulos::validarRol([21], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
							<a href="productos-importar.php" class="btn btn-info"><i class="icon-upload"></i> Importar</a>
													<?php } ?>
											</div>
				</div>
				</div>

				<?php include("includes/notificaciones.php"); ?>
				<span id="resp"></span>

				<section class="clientes-panel">
					<div class="clientes-panel-header">
						<div>
							<h3>Listado de productos</h3>
							<p>Busque por código o nombre y filtre el catálogo</p>
						</div>
						<div class="productos-filtros-buscar">
													<?php
								$vistaActual = '';
								foreach (['web', 'pdt', 'stock', 'utilidad'] as $claveVista) {
									if (!empty($_GET[$claveVista]) || (isset($_GET['vista']) && $_GET['vista'] === $claveVista)) {
										$vistaActual = $claveVista;
									}
								}
								$habilitadoActual = (isset($_GET['habilitado']) && (string) $_GET['habilitado'] === '0') ? '0' : '1';
								?>
								<div class="productos-filtro">
									<label for="filtroHabilitadoProducto">Habilitado</label>
									<select id="filtroHabilitadoProducto" class="js-filtro-buscar" name="habilitado" form="formBusquedaProductos">
										<option value="1"<?= $habilitadoActual === '1' ? ' selected' : ''; ?>>Habilitados</option>
										<option value="0"<?= $habilitadoActual === '0' ? ' selected' : ''; ?>>No habilitados</option>
									</select>
								</div>
								<?php if (Modulos::validarRol([401], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
								<div class="productos-filtro">
									<label for="filtroVistaProducto">Filtro</label>
									<select id="filtroVistaProducto" class="js-filtro-buscar" name="vista" form="formBusquedaProductos">
										<option value="">Todos</option>
										<option value="web"<?= $vistaActual === 'web' ? ' selected' : ''; ?>>Visible web</option>
										<option value="pdt"<?= $vistaActual === 'pdt' ? ' selected' : ''; ?>>Predeterminados</option>
										<option value="stock"<?= $vistaActual === 'stock' ? ' selected' : ''; ?>>Sin existencias</option>
										<option value="utilidad"<?= $vistaActual === 'utilidad' ? ' selected' : ''; ?>>Sin utilidad</option>
									</select>
								</div>
								<?php } ?>
								<div class="productos-filtro">
									<label for="filtroGrupo1Producto"><?= $ofimaProductosActiva ? 'Línea' : 'Grupo 1'; ?></label>
									<select id="filtroGrupo1Producto" class="js-filtro-buscar" name="grupo1" form="formBusquedaProductos">
										<option value="">Todos</option>
										<?php
										$grupos1 = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=1 AND catp_habilitada=1 AND catp_id_empresa='".$idEmpresa."'");
													while ($grupo1 = mysqli_fetch_array($grupos1, MYSQLI_BOTH)) {
											$sel = isset($_GET['grupo1']) && (string) $_GET['grupo1'] === (string) $grupo1[0] ? ' selected' : '';
													?>
											<option value="<?= (int) $grupo1[0]; ?>"<?= $sel; ?>><?= htmlspecialchars($grupo1['catp_nombre']); ?></option>
													<?php } ?>
									</select>
								</div>
								<div class="productos-filtro">
									<label for="filtroGrupo2Producto"><?= $ofimaProductosActiva ? 'Sublínea' : 'Grupo 2'; ?></label>
									<select id="filtroGrupo2Producto" class="js-filtro-buscar" name="grupo2" form="formBusquedaProductos">
										<option value="">Todos</option>
													<?php
										$grupos2 = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=2 AND catp_habilitada=1 AND catp_id_empresa='".$idEmpresa."'");
													while ($grupo2 = mysqli_fetch_array($grupos2, MYSQLI_BOTH)) {
											$sel = isset($_GET['grupo2']) && (string) $_GET['grupo2'] === (string) $grupo2[0] ? ' selected' : '';
													?>
											<option value="<?= (int) $grupo2[0]; ?>"<?= $sel; ?>><?= htmlspecialchars($grupo2['catp_nombre']); ?></option>
													<?php } ?>
									</select>
								</div>
								<div class="productos-filtro">
									<label for="filtroMarcaProducto"><?= $ofimaProductosActiva ? 'Grupo' : 'Marca'; ?></label>
									<select id="filtroMarcaProducto" class="js-filtro-buscar" name="marca" form="formBusquedaProductos">
										<option value="">Todas</option>
													<?php
										$marcas = $conexionBdPrincipal->query("SELECT * FROM marcas WHERE mar_id_empresa='".$idEmpresa."' AND mar_habilitada=1");
													while ($marca = mysqli_fetch_array($marcas, MYSQLI_BOTH)) {
											$sel = isset($_GET['marca']) && (string) $_GET['marca'] === (string) $marca[0] ? ' selected' : '';
													?>
											<option value="<?= (int) $marca[0]; ?>"<?= $sel; ?>><?= htmlspecialchars($marca[1]); ?></option>
													<?php } ?>
									</select>
								</div>
								<div class="productos-filtro">
									<label for="filtroGrupo3Producto"><?= $ofimaProductosActiva ? 'Clasificación 1' : 'Grupo 3'; ?></label>
									<select id="filtroGrupo3Producto" class="js-filtro-buscar" name="grupo3" form="formBusquedaProductos">
										<option value="">Todos</option>
										<?php
										$grupos3 = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=3 AND catp_habilitada=1 AND catp_id_empresa='".$idEmpresa."'");
										while ($grupo3 = mysqli_fetch_array($grupos3, MYSQLI_BOTH)) {
											$sel = isset($_GET['grupo3']) && (string) $_GET['grupo3'] === (string) $grupo3[0] ? ' selected' : '';
										?>
											<option value="<?= (int) $grupo3[0]; ?>"<?= $sel; ?>><?= htmlspecialchars($grupo3['catp_nombre']); ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="clientes-panel-toolbar">
									<a href="productos.php" class="clientes-toolbar-btn"><i class="icon-th-large"></i> Todos</a>
									<?php if (Modulos::validarRol([153], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
										<a href="productos-condiciones.php" class="clientes-toolbar-btn"><i class="icon-random"></i> Condicionar</a>
									<?php } ?>
									<div class="productos-mas-opciones dropdown">
										<a href="#" class="clientes-toolbar-btn dropdown-toggle" data-toggle="dropdown">Más opciones <b class="caret"></b></a>
												<ul class="dropdown-menu">
											<?php if (Modulos::validarRol([152], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
												<li><a href="productos-store.php">Editar productos Store JM</a></li>
											<?php } ?>
											<?php if (Modulos::validarRol([121], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
												<li><a href="productos-predeterminados.php">Editar productos predeterminados</a></li>
											<?php } ?>
											<?php if (Modulos::validarRol([21], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
												<li><a href="productos-importar.php">Importar excel</a></li>
											<?php } ?>
											<?php if (Modulos::validarRol([208], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
												<li><a href="guardar-precios.php" onclick="return confirm('Desea guardar los precios actuales en el historial?');">Guardar precios en historial</a></li>
													<?php } ?>
										</ul>
									</div>
								</div>
							</div>
						</div>
					<div class="clientes-panel-body">
						<form id="formBusquedaProductos" action="<?= $_SERVER['PHP_SELF']; ?>" method="get" class="clientes-busqueda-principal">
							<label class="clientes-busqueda-label" for="productosBusqueda">Buscar productos</label>
							<div class="clientes-busqueda-input-wrap">
								<i class="icon-search clientes-busqueda-icon" aria-hidden="true"></i>
								<input type="search" id="productosBusqueda" name="busqueda" value="<?= htmlspecialchars($busquedaProducto); ?>" placeholder="<?= $ofimaProductosActiva ? 'Código Ofima o nombre del producto' : 'Código o nombre del producto'; ?>" autocomplete="off">
								<button type="submit" class="clientes-busqueda-btn">Buscar</button>
								<input type="hidden" name="por" value="<?= (int) $porPaginaProducto; ?>">
					</div>
							<div class="productos-columnas">
								<button type="button" class="clientes-toolbar-btn" id="btnColumnasProducto"><i class="icon-eye-open"></i> Columnas</button>
								<div class="productos-columnas-panel" id="panelColumnasProducto"></div>
				</div>
							<p class="clientes-busqueda-hint">Escriba y pulse Enter. La búsqueda usa código y nombre.</p>
						</form>

						<p class="clientes-leyenda">Los productos que están en cotizaciones, pedidos, remisiones, facturas o combos no se pueden eliminar.</p>
						<div class="clientes-table-wrap">
								<table class="clientes-table" id="data-table">
									<thead>
										<tr>
											<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
												<th data-col="sel" class="col-sel" title="Seleccionar">
													<label class="productos-sel-label">
														<input type="checkbox" id="prodSelTodos" aria-label="Seleccionar todos los de esta página">
													</label>
												</th>
											<?php } ?>
											<th data-col="no">No</th>

											<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
												<th data-col="pdt">PDT</th>
												<th data-col="web">
													<div class="productos-web-head">
														<span class="productos-web-head-label">VIS. WEB</span>
														<select id="prodWebBulkSelect" class="productos-web-bulk-select" title="Cambiar visible en la web de los seleccionados" aria-label="Cambiar visible en la web de los seleccionados">
															<option value="">Estado…</option>
															<option value="1">SI</option>
															<option value="0">NO</option>
														</select>
													</div>
												</th>
											<?php } ?>

											<th data-col="codigo"><?= $ofimaProductosActiva ? 'CÓDIGO OFIMA' : 'CÓDIGO'; ?></th>
											<th data-col="nombre">Nombre</th>

											<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
												<th data-col="costo">Costo</th>
											<?php } ?>

											<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
												<th data-col="utilidad">Utilidad (%)</th>
											<?php } ?>

											<th data-col="precio">Precio lista</th>

											<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
												<th data-col="precio-iva">Precio lista + IVA</th>
											<?php } ?>

											<th data-col="precio-usd">Precio lista (USD)</th>
											<th data-col="precio-dolar">Precio lista según dólar hoy</th>

											<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
												
												<th data-col="dcto-dealer" title="Sobre el precio de lista.">Descuento Dealer. (%)</th>
												<th data-col="precio-dealer">Precio dealer</th>
												<th data-col="dcto-web" title="Sobre el precio de lista.">Descuento Web. (%)</th>
												<th data-col="precio-web">Precio web</th>
											<?php } ?>

											<th data-col="dcto-max" title="Sobre el precio de lista.">Dcto. Max. (%)</th>

											<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
												<th data-col="comision">Comisión (%)</th>
												<th data-col="comision-ext">Comisión externo (%)</th>	
											<?php } ?>

											<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
												<th data-col="grupo1"><?= $ofimaProductosActiva ? 'Línea' : 'Grupo 1'; ?></th>
											<?php } ?>
											<th data-col="grupo2"><?= $ofimaProductosActiva ? 'Sublínea' : 'Grupo 2'; ?></th>
											<th data-col="marca"><?= $ofimaProductosActiva ? 'Grupo' : 'Marca'; ?></th>
											<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
												<th data-col="fabrica">P. Fábrica USD</th>
												<th data-col="flete">Flete USD</th>
												<th data-col="aduana">Aduana USD</th>
											<?php } ?>
											<th data-col="existencia">Existencia</th>
											<!--<th>Precio Min.</th>
											<th>Comisión estimada</th>-->
										</tr>
									</thead>
									<tbody id="productos_buscar">
										<?php
										include RUTA_PROYECTO . '/usuarios/includes/productos-listado-consulta.php';
										include RUTA_PROYECTO . '/usuarios/includes/productos-listado-filas.php';
										?>
									</tbody>
								</table>
						</div>
						<?php include RUTA_PROYECTO . '/usuarios/includes/productos-listado-paginacion.php'; ?>
					</div>
				</section>
			</div>
		</div>
	</div>
	<div id="drawerBodegasOverlay"></div>
	<aside id="drawerBodegasProducto" role="dialog" aria-modal="true" aria-hidden="true">
		<div class="drawer-header">
			<h2 id="drawerBodegasTitulo">Bodegas por producto</h2>
			<button type="button" class="drawer-close" id="btnCerrarBodegas" aria-label="Cerrar">&times;</button>
		</div>
		<div class="drawer-body">
			<p class="bodegas-aviso" id="bodegasAviso"></p>
			<p class="bodegas-vacio" id="bodegasMensaje"></p>
			<table>
				<thead>
					<tr>
						<th>Bodega</th>
						<th>Existencias</th>
						<th>Actualización</th>
						<th>Responsable</th>
					</tr>
				</thead>
				<tbody id="bodegasCuerpo"></tbody>
			</table>
		</div>
	</aside>
	<div id="drawerHistorialOverlay"></div>
	<aside id="drawerHistorialProducto" role="dialog" aria-modal="true" aria-hidden="true">
		<div class="drawer-header">
			<h2 id="drawerHistorialTitulo">Historial de precios</h2>
			<button type="button" class="drawer-close" id="btnCerrarHistorial" aria-label="Cerrar">&times;</button>
		</div>
		<div class="drawer-body">
			<p class="historial-vacio" id="historialMensaje"></p>
			<table>
				<thead>
					<tr>
						<th>Precio anterior</th>
						<th>Precio nuevo</th>
						<th>Origen</th>
						<th>Actualización</th>
						<th>Responsable</th>
					</tr>
				</thead>
				<tbody id="historialCuerpo"></tbody>
			</table>
		</div>
	</aside>
	<div id="drawerGaleriaOverlay"></div>
	<aside id="drawerGaleriaProducto" role="dialog" aria-modal="true" aria-hidden="true">
		<div class="drawer-header">
			<h2 id="drawerGaleriaTitulo">Galería</h2>
			<button type="button" class="drawer-close" id="btnCerrarGaleria" aria-label="Cerrar">&times;</button>
		</div>
		<div class="drawer-body">
			<form class="galeria-subir" id="formGaleriaProducto">
				<input type="hidden" name="ajax" value="1">
				<input type="hidden" name="id" id="galeriaProductoId" value="">
				<input type="file" name="archivo" id="galeriaArchivo" accept="image/*" required>
				<button type="submit" class="btn btn-success">Subir foto</button>
			</form>
			<p class="galeria-vacio" id="galeriaMensaje"></p>
			<div class="galeria-grid" id="galeriaGrid"></div>
		</div>
	</aside>
	<div id="confirmacionProducto" class="confirmacion-producto" aria-hidden="true">
		<div class="confirmacion-producto-caja" role="dialog" aria-modal="true">
			<p id="confirmacionProductoTexto"></p>
			<div class="confirmacion-producto-acciones">
				<button type="button" id="confirmacionProductoCancelar">Cancelar</button>
				<button type="button" id="confirmacionProductoAceptar">Aceptar</button>
			</div>
		</div>
	</div>
	<?php if (Modulos::validarRol([402], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
	<div class="productos-bulk-bar" id="productosBulkBar" aria-hidden="true" role="dialog" aria-modal="true">
		<div class="productos-bulk-count"><span id="productosBulkCount">0</span> seleccionados</div>
		<p class="productos-bulk-msg" id="productosBulkMsg"></p>
		<div class="productos-bulk-actions">
			<button type="button" class="productos-bulk-btn is-clear" id="productosBulkCancelar">Cancelar</button>
			<button type="button" class="productos-bulk-btn is-si" id="productosBulkAceptar">Aceptar</button>
		</div>
		<p class="productos-bulk-hint" id="productosBulkHint">Solo productos habilitados pueden pasar a SI. El lote sincroniza con la tienda JM.</p>
	</div>
	<?php } ?>
	<script>
	(function () {
		var confirmacion = document.getElementById('confirmacionProducto');
		var confirmacionTexto = document.getElementById('confirmacionProductoTexto');
		var destinoConfirmacion = '';
		var onAcceptConfirmacion = null;
		var onCancelConfirmacion = null;
		function cerrarConfirmacion() {
			confirmacion.classList.remove('is-open');
			confirmacion.setAttribute('aria-hidden', 'true');
			destinoConfirmacion = '';
			onAcceptConfirmacion = null;
			onCancelConfirmacion = null;
		}
		function abrirConfirmacionProducto(texto, opciones) {
			opciones = opciones || {};
			destinoConfirmacion = opciones.destino || '';
			onAcceptConfirmacion = typeof opciones.onAccept === 'function' ? opciones.onAccept : null;
			onCancelConfirmacion = typeof opciones.onCancel === 'function' ? opciones.onCancel : null;
			confirmacionTexto.textContent = texto || '';
			confirmacion.classList.add('is-open');
			confirmacion.setAttribute('aria-hidden', 'false');
		}
		window.abrirConfirmacionProducto = abrirConfirmacionProducto;
		document.getElementById('confirmacionProductoCancelar').addEventListener('click', function () {
			var onCancel = onCancelConfirmacion;
			cerrarConfirmacion();
			if (onCancel) onCancel();
		});
		document.getElementById('confirmacionProductoAceptar').addEventListener('click', function () {
			var destino = destinoConfirmacion;
			var onAccept = onAcceptConfirmacion;
			cerrarConfirmacion();
			if (onAccept) {
				onAccept();
				return;
			}
			if (destino) window.location.href = destino;
		});
		confirmacion.addEventListener('click', function (e) {
			if (e.target === confirmacion) {
				var onCancel = onCancelConfirmacion;
				cerrarConfirmacion();
				if (onCancel) onCancel();
			}
		});
		document.addEventListener('click', function (e) {
			var eliminar = e.target.closest ? e.target.closest('.js-eliminar-producto') : null;
			var replicar = e.target.closest ? e.target.closest('.js-replicar-producto') : null;
			var enlace = eliminar || replicar;
			if (!enlace) return;
			e.preventDefault();
			e.stopPropagation();
			var nombreProducto = enlace.getAttribute('data-nombre') || 'este producto';
			abrirConfirmacionProducto(
				eliminar
					? '¿Desea eliminar el producto ' + nombreProducto + '?'
					: '¿Desea replicar el producto ' + nombreProducto + ' a soporte operativo?',
				{ destino: enlace.getAttribute('href') }
			);
		}, true);
		var galeria = document.getElementById('drawerGaleriaProducto');
		var galeriaOverlay = document.getElementById('drawerGaleriaOverlay');
		var galeriaGrid = document.getElementById('galeriaGrid');
		var galeriaMensaje = document.getElementById('galeriaMensaje');
		var galeriaForm = document.getElementById('formGaleriaProducto');
		var galeriaId = document.getElementById('galeriaProductoId');
		function cerrarGaleria() {
			galeria.classList.remove('is-open');
			galeriaOverlay.classList.remove('is-open');
			galeria.setAttribute('aria-hidden', 'true');
		}
		function pintarGaleria(fotos) {
			galeriaGrid.innerHTML = '';
			galeriaMensaje.textContent = fotos.length ? '' : 'Este producto no tiene fotos en la galería.';
			fotos.forEach(function (foto) {
				var item = document.createElement('div');
				item.className = 'galeria-item';
				var img = document.createElement('img');
				img.src = 'files/productos/galeria/' + encodeURIComponent(foto.nombre);
				img.alt = foto.nombre;
				var borrar = document.createElement('button');
				borrar.type = 'button';
				borrar.textContent = 'Eliminar';
				borrar.addEventListener('click', function () {
					if (!confirm('Seguro desea eliminar esta foto?')) return;
					fetch('bd_delete/productos-galeria-eliminar.php?idItem=' + encodeURIComponent(foto.id) + '&ajax=1', { credentials: 'same-origin' })
						.then(function (r) { return r.json(); })
						.then(function () { cargarGaleria(galeriaId.value); });
				});
				item.appendChild(img);
				item.appendChild(borrar);
				galeriaGrid.appendChild(item);
			});
		}
		function cargarGaleria(id) {
			galeriaMensaje.textContent = 'Cargando...';
			galeriaGrid.innerHTML = '';
			fetch('ajax/ajax-producto-galeria.php?id=' + encodeURIComponent(id), { credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (data) {
					if (!data || !data.success) {
						galeriaMensaje.textContent = (data && data.message) || 'No se pudo cargar la galería.';
						return;
					}
					document.getElementById('drawerGaleriaTitulo').textContent = data.producto || 'Galería';
					pintarGaleria(data.fotos || []);
				})
				.catch(function () { galeriaMensaje.textContent = 'No se pudo cargar la galería.'; });
		}
		document.addEventListener('click', function (e) {
			var link = e.target.closest ? e.target.closest('.js-galeria-producto') : null;
			if (!link) return;
			e.preventDefault();
			galeriaId.value = link.getAttribute('data-id');
			galeria.classList.add('is-open');
			galeriaOverlay.classList.add('is-open');
			galeria.setAttribute('aria-hidden', 'false');
			cargarGaleria(galeriaId.value);
		});
		var historial = document.getElementById('drawerHistorialProducto');
		var historialOverlay = document.getElementById('drawerHistorialOverlay');
		var historialCuerpo = document.getElementById('historialCuerpo');
		var historialMensaje = document.getElementById('historialMensaje');
		function cerrarHistorial() {
			historial.classList.remove('is-open');
			historialOverlay.classList.remove('is-open');
			historial.setAttribute('aria-hidden', 'true');
		}
		var bodegas = document.getElementById('drawerBodegasProducto');
		var bodegasOverlay = document.getElementById('drawerBodegasOverlay');
		var bodegasCuerpo = document.getElementById('bodegasCuerpo');
		var bodegasMensaje = document.getElementById('bodegasMensaje');
		var bodegasAviso = document.getElementById('bodegasAviso');
		var bodegasProductoId = '';
		function cerrarBodegas() {
			bodegas.classList.remove('is-open');
			bodegasOverlay.classList.remove('is-open');
			bodegas.setAttribute('aria-hidden', 'true');
		}
		function pintarBodegas(lista, soloOfima) {
			bodegasCuerpo.innerHTML = '';
			bodegasAviso.textContent = soloOfima ? 'Las existencias solo se actualizan desde Ofima.' : '';
			bodegasMensaje.textContent = lista.length ? '' : 'Este producto no tiene bodegas asignadas.';
			lista.forEach(function (fila) {
				var tr = document.createElement('tr');
				var tdBodega = document.createElement('td');
				tdBodega.textContent = fila.bodega;
				var tdExistencias = document.createElement('td');
				if (soloOfima) {
					tdExistencias.textContent = fila.existencias;
				} else {
					var input = document.createElement('input');
					input.type = 'number';
					input.min = '0';
					input.value = fila.existencias;
					input.addEventListener('change', function () {
						var cantidad = input.value === '' ? '0' : input.value;
						fetch('ajax/ajax-bodegas-existencias.php?idRegistro=' + encodeURIComponent(fila.id) + '&existencias=' + encodeURIComponent(cantidad) + '&idProducto=' + encodeURIComponent(bodegasProductoId), { credentials: 'same-origin' })
							.then(function () { cargarBodegas(bodegasProductoId); });
					});
					tdExistencias.appendChild(input);
				}
				var tdFecha = document.createElement('td');
				tdFecha.textContent = fila.fecha;
				var tdResponsable = document.createElement('td');
				tdResponsable.textContent = fila.responsable;
				tr.appendChild(tdBodega);
				tr.appendChild(tdExistencias);
				tr.appendChild(tdFecha);
				tr.appendChild(tdResponsable);
				bodegasCuerpo.appendChild(tr);
			});
		}
		function cargarBodegas(id) {
			bodegasProductoId = id;
			bodegasMensaje.textContent = 'Cargando...';
			bodegasCuerpo.innerHTML = '';
			fetch('ajax/ajax-producto-bodegas.php?id=' + encodeURIComponent(id), { credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (data) {
					if (!data || !data.success) {
						bodegasMensaje.textContent = (data && data.message) || 'No se pudieron cargar las bodegas.';
						return;
					}
					document.getElementById('drawerBodegasTitulo').textContent = data.producto || 'Bodegas por producto';
					pintarBodegas(data.bodegas || [], !!data.solo_ofima);
				})
				.catch(function () { bodegasMensaje.textContent = 'No se pudieron cargar las bodegas.'; });
		}
		document.addEventListener('click', function (e) {
			var link = e.target.closest ? e.target.closest('.js-bodegas-producto') : null;
			if (!link) return;
			e.preventDefault();
			bodegas.classList.add('is-open');
			bodegasOverlay.classList.add('is-open');
			bodegas.setAttribute('aria-hidden', 'false');
			cargarBodegas(link.getAttribute('data-id'));
		});
		document.getElementById('btnCerrarBodegas').addEventListener('click', cerrarBodegas);
		bodegasOverlay.addEventListener('click', cerrarBodegas);
		document.addEventListener('click', function (e) {
			var link = e.target.closest ? e.target.closest('.js-historial-producto') : null;
			if (!link) return;
			e.preventDefault();
			historial.classList.add('is-open');
			historialOverlay.classList.add('is-open');
			historial.setAttribute('aria-hidden', 'false');
			historialMensaje.textContent = 'Cargando...';
			historialCuerpo.innerHTML = '';
			fetch('ajax/ajax-producto-historial.php?id=' + encodeURIComponent(link.getAttribute('data-id')), { credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (data) {
					if (!data || !data.success) {
						historialMensaje.textContent = (data && data.message) || 'No se pudo cargar el historial.';
						return;
					}
					document.getElementById('drawerHistorialTitulo').textContent = data.producto || 'Historial de precios';
					var filas = data.movimientos || [];
					historialMensaje.textContent = filas.length ? '' : 'Este producto no tiene historial de precios.';
					filas.forEach(function (mov) {
						var tr = document.createElement('tr');
						[mov.anterior, mov.nuevo, mov.origen, mov.fecha, mov.responsable].forEach(function (texto) {
							var td = document.createElement('td');
							td.textContent = texto || '';
							tr.appendChild(td);
						});
						historialCuerpo.appendChild(tr);
					});
				})
				.catch(function () { historialMensaje.textContent = 'No se pudo cargar el historial.'; });
		});
		document.getElementById('btnCerrarHistorial').addEventListener('click', cerrarHistorial);
		historialOverlay.addEventListener('click', cerrarHistorial);
		document.getElementById('btnCerrarGaleria').addEventListener('click', cerrarGaleria);
		galeriaOverlay.addEventListener('click', cerrarGaleria);
		galeriaForm.addEventListener('submit', function (e) {
			e.preventDefault();
			var datos = new FormData(galeriaForm);
			fetch('bd_create/productos-fotos-guardar.php', { method: 'POST', body: datos, credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function () {
					galeriaForm.reset();
					galeriaId.value = datos.get('id');
					cargarGaleria(datos.get('id'));
				})
				.catch(function () { galeriaMensaje.textContent = 'No se pudo subir la foto.'; });
		});
		function activarAjusteColumna(selector, variable, clave, minimo) {
			var th = document.querySelector(selector);
			if (!th) return;
			var anchoGuardado = parseInt(localStorage.getItem(clave) || '', 10);
			if (anchoGuardado) document.documentElement.style.setProperty(variable, anchoGuardado + 'px');
			var agarre = document.createElement('span');
			agarre.className = selector.indexOf('codigo') !== -1 ? 'col-codigo-agarre' : 'col-nombre-agarre';
			th.appendChild(agarre);
			agarre.addEventListener('mousedown', function (e) {
				e.preventDefault();
				var inicioX = e.clientX;
				var inicioAncho = th.getBoundingClientRect().width;
				function mover(ev) {
					var nuevo = Math.max(minimo, inicioAncho + ev.clientX - inicioX);
					document.documentElement.style.setProperty(variable, nuevo + 'px');
				}
				function soltar() {
					document.removeEventListener('mousemove', mover);
					document.removeEventListener('mouseup', soltar);
					localStorage.setItem(clave, String(Math.round(th.getBoundingClientRect().width)));
				}
				document.addEventListener('mousemove', mover);
				document.addEventListener('mouseup', soltar);
			});
		}
		activarAjusteColumna('#data-table th[data-col="codigo"]', '--ancho-codigo', 'productos-ancho-codigo', 72);
		activarAjusteColumna('#data-table th[data-col="nombre"]', '--ancho-nombre', 'productos-ancho-nombre', 140);
		var tabla = document.getElementById('data-table');
		var boton = document.getElementById('btnColumnasProducto');
		var panel = document.getElementById('panelColumnasProducto');
		if (tabla && boton && panel) {
			var clave = 'productos-columnas-ocultas';
			var ocultas = [];
			try { ocultas = JSON.parse(localStorage.getItem(clave) || '[]'); } catch (e) { ocultas = []; }
			var fijas = { codigo: true, nombre: true, sel: true };
			function aplicar() {
				tabla.querySelectorAll('thead th[data-col]').forEach(function (th, indice) {
					var oculta = ocultas.indexOf(th.getAttribute('data-col')) !== -1;
					tabla.querySelectorAll('tr > *:nth-child(' + (indice + 1) + ')').forEach(function (celda) {
						celda.style.display = oculta ? 'none' : '';
					});
				});
			}
			window.productosAplicarColumnas = aplicar;
			tabla.querySelectorAll('thead th[data-col]').forEach(function (th) {
				var id = th.getAttribute('data-col');
				var label = document.createElement('label');
				var input = document.createElement('input');
				input.type = 'checkbox';
				input.checked = ocultas.indexOf(id) === -1;
				input.disabled = !!fijas[id];
				input.addEventListener('change', function () {
					if (input.checked) ocultas = ocultas.filter(function (item) { return item !== id; });
					else if (ocultas.indexOf(id) === -1) ocultas.push(id);
					localStorage.setItem(clave, JSON.stringify(ocultas));
					aplicar();
				});
				label.appendChild(input);
				label.appendChild(document.createTextNode(th.textContent.trim()));
				panel.appendChild(label);
			});
			aplicar();
			boton.addEventListener('click', function (e) {
				e.stopPropagation();
				boton.parentNode.classList.toggle('is-open');
			});
			document.addEventListener('click', function (e) {
				if (!boton.parentNode.contains(e.target)) boton.parentNode.classList.remove('is-open');
			});
		}

		(function () {
			var tbody = document.getElementById('productos_buscar');
			var paginacionWrap = document.getElementById('productosPaginacionContenedor');
			if (!tbody || !paginacionWrap) return;
			var requestId = 0;

			function paramsActuales() {
				return new URLSearchParams(window.location.search);
			}

			function actualizarUrl(pagina, por) {
				var params = paramsActuales();
				if (por) params.set('por', String(por));
				if (pagina && pagina > 1) params.set('inicio', String(pagina));
				else params.delete('inicio');
				var query = params.toString();
				window.history.replaceState({}, '', 'productos.php' + (query ? '?' + query : ''));
			}

			function cargarListado(pagina, por) {
				var params = paramsActuales();
				if (por) params.set('por', String(por));
				if (pagina && pagina > 1) params.set('inicio', String(pagina));
				else params.delete('inicio');
				var id = ++requestId;
				tbody.style.opacity = '0.55';
				fetch('ajax/ajax-productos-listado.php?' + params.toString(), { credentials: 'same-origin' })
					.then(function (r) { return r.json(); })
					.then(function (data) {
						if (id !== requestId) return;
						tbody.style.opacity = '';
						if (!data || !data.success) return;
						tbody.innerHTML = data.html || '';
						var contenedorPadre = paginacionWrap.parentNode;
						var tmp = document.createElement('div');
						tmp.innerHTML = data.pagination || '';
						var nueva = tmp.firstElementChild;
						if (nueva && contenedorPadre) {
							contenedorPadre.replaceChild(nueva, paginacionWrap);
							paginacionWrap = document.getElementById('productosPaginacionContenedor');
						}
						actualizarUrl(data.pagina || 1, data.por);
						if (typeof window.productosAplicarColumnas === 'function') {
							window.productosAplicarColumnas();
						}
						if (typeof window.productosBulkRefresh === 'function') {
							window.productosBulkRefresh();
						}
						if (window.jQuery && jQuery.fn.tooltip) {
							jQuery('[data-toggle="tooltip"]').tooltip();
						}
					})
					.catch(function () {
						if (id !== requestId) return;
						tbody.style.opacity = '';
					});
			}

			document.addEventListener('click', function (e) {
				var link = e.target.closest ? e.target.closest('a.js-productos-pagina') : null;
				if (!link || !link.closest('.productos-paginacion')) return;
				e.preventDefault();
				var pagina = parseInt(link.getAttribute('data-pagina'), 10) || 1;
				var porSel = document.getElementById('productosPorPagina');
				var por = porSel ? parseInt(porSel.value, 10) : null;
				cargarListado(pagina, por);
			});

			document.addEventListener('change', function (e) {
				if (!e.target || !e.target.classList || !e.target.classList.contains('js-productos-por-pagina')) return;
				cargarListado(1, parseInt(e.target.value, 10) || 10);
			});

			document.addEventListener('submit', function (e) {
				if (!e.target || e.target.id !== 'formIrPaginaProducto') return;
				e.preventDefault();
				var input = document.getElementById('productosIrPagina');
				var pagina = parseInt(input && input.value, 10);
				var maximo = parseInt(input && input.max, 10) || 1;
				if (!pagina || pagina < 1) pagina = 1;
				if (pagina > maximo) pagina = maximo;
				var porSel = document.getElementById('productosPorPagina');
				var por = porSel ? parseInt(porSel.value, 10) : null;
				cargarListado(pagina, por);
			});
		})();

		(function () {
			var selectEstado = document.getElementById('prodWebBulkSelect');
			var selTodos = document.getElementById('prodSelTodos');
			var tbody = document.getElementById('productos_buscar');
			var resp = document.getElementById('resp');
			var barra = document.getElementById('productosBulkBar');
			var barraCount = document.getElementById('productosBulkCount');
			var barraMsg = document.getElementById('productosBulkMsg');
			var barraHint = document.getElementById('productosBulkHint');
			var btnCancelar = document.getElementById('productosBulkCancelar');
			var btnAceptar = document.getElementById('productosBulkAceptar');
			if (!selectEstado || !tbody) return;
			var enviando = false;
			var pendienteEstado = null;

			function checks() {
				return Array.prototype.slice.call(tbody.querySelectorAll('.js-prod-sel'));
			}
			function seleccionados() {
				return checks().filter(function (c) { return c.checked; });
			}
			function cerrarBarraConfirmacion() {
				pendienteEstado = null;
				if (barra) {
					barra.classList.remove('is-open');
					barra.setAttribute('aria-hidden', 'true');
				}
				if (btnAceptar) {
					btnAceptar.classList.remove('is-no');
					btnAceptar.classList.add('is-si');
					btnAceptar.textContent = 'Aceptar';
					btnAceptar.disabled = false;
				}
				if (btnCancelar) btnCancelar.disabled = false;
			}
			function abrirBarraConfirmacion(estadoDestino, n) {
				pendienteEstado = estadoDestino;
				var etiqueta = estadoDestino === 1 ? 'SI' : 'NO';
				if (barraCount) barraCount.textContent = String(n);
				if (barraMsg) {
					barraMsg.textContent = n === 1
						? '¿Marcar visible en la web como ' + etiqueta + ' en el producto seleccionado?'
						: '¿Marcar visible en la web como ' + etiqueta + ' en los ' + n + ' seleccionados?';
				}
				if (barraHint) {
					barraHint.textContent = estadoDestino === 1
						? 'Solo productos habilitados pueden pasar a SI. El lote sincroniza con la tienda JM.'
						: 'El lote sincroniza con la tienda JM.';
				}
				if (btnAceptar) {
					btnAceptar.classList.toggle('is-si', estadoDestino === 1);
					btnAceptar.classList.toggle('is-no', estadoDestino !== 1);
					btnAceptar.textContent = 'Marcar visible en la web ' + etiqueta;
				}
				if (barra) {
					barra.classList.add('is-open');
					barra.setAttribute('aria-hidden', 'false');
				}
			}
			function refrescar() {
				var lista = seleccionados();
				tbody.querySelectorAll('tr[data-prod-id]').forEach(function (tr) {
					var c = tr.querySelector('.js-prod-sel');
					tr.classList.toggle('is-selected', !!(c && c.checked));
				});
				if (selTodos) {
					var todos = checks();
					selTodos.checked = todos.length > 0 && todos.every(function (c) { return c.checked; });
					selTodos.indeterminate = lista.length > 0 && lista.length < todos.length;
				}
				selectEstado.classList.toggle('is-visible', lista.length > 0);
				if (lista.length === 0) {
					selectEstado.value = '';
					cerrarBarraConfirmacion();
				}
				selectEstado.title = lista.length > 0
					? ('Cambiar visible en la web de ' + lista.length + ' seleccionado(s)')
					: 'Seleccione productos y elija SI o NO';
			}
			window.productosBulkRefresh = refrescar;

			function aplicarEstadoUi(detalle) {
				(detalle || []).forEach(function (item) {
					if (!item || item.estado === null || item.estado === undefined) return;
					if (item.code === 'not_habilitado' || item.code === 'not_found') return;
					var el = document.getElementById('vw' + item.id);
					if (el) {
						el.innerHTML = Number(item.estado) === 1 ? 'SI' : 'NO';
						el.title = String(item.estado);
					}
					var chk = tbody.querySelector('.js-prod-sel[value="' + item.id + '"]');
					if (chk) chk.setAttribute('data-visible', String(item.estado));
				});
			}

			function toast(texto, tipo) {
				if (typeof window.productosToast === 'function') {
					window.productosToast(texto, tipo);
					return;
				}
				if (resp) {
					resp.style.display = '';
					resp.innerHTML = String(texto || '');
				}
			}

			function enviarLote(estadoDestino) {
				if (enviando) return;
				var ids = seleccionados().map(function (c) { return c.value; });
				if (!ids.length) {
					toast('Seleccione uno o más productos antes de cambiar la visibilidad en la web.', 'warning');
					selectEstado.value = '';
					cerrarBarraConfirmacion();
					return;
				}
				enviando = true;
				selectEstado.disabled = true;
				if (btnAceptar) btnAceptar.disabled = true;
				if (btnCancelar) btnCancelar.disabled = true;
				toast('Actualizando visible en la web de ' + ids.length + ' producto(s)…', 'info');
				var body = new URLSearchParams();
				body.set('proceso', '12');
				body.set('estado', String(estadoDestino));
				ids.forEach(function (id) { body.append('productos[]', id); });

				fetch('ajax/ajax-productos.php', {
					method: 'POST',
					credentials: 'same-origin',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
					body: body.toString()
				})
					.then(function (r) { return r.json(); })
					.then(function (data) {
						enviando = false;
						selectEstado.disabled = false;
						selectEstado.value = '';
						cerrarBarraConfirmacion();
						if (!data || !data.success) {
							toast((data && data.message) ? data.message : 'No se pudo actualizar el lote de visible en la web.', 'error');
							return;
						}
						aplicarEstadoUi(data.detalle || []);
						var etiqueta = parseInt(estadoDestino, 10) === 1 ? 'SI' : 'NO';
						var msg = 'Lote visible en la web → ' + etiqueta + ': ' + (data.ok || 0) + ' actualizado(s)';
						if (data.omitidos) msg += ', ' + data.omitidos + ' omitido(s)';
						if (data.errores) msg += ', ' + data.errores + ' con error';
						if (!data.jm_conectado) msg += '. Sin conexión JM';
						else msg += '. Sync con tienda JM aplicada';
						toast(msg + '.', data.errores ? 'warning' : 'success');
						checks().forEach(function (c) { c.checked = false; });
						refrescar();
					})
					.catch(function () {
						enviando = false;
						selectEstado.disabled = false;
						selectEstado.value = '';
						cerrarBarraConfirmacion();
						toast('Error de red al actualizar el lote de visible en la web.', 'error');
					});
			}

			tbody.addEventListener('change', function (e) {
				if (!e.target || !e.target.classList.contains('js-prod-sel')) return;
				refrescar();
			});
			if (selTodos) {
				selTodos.addEventListener('change', function () {
					var on = selTodos.checked;
					checks().forEach(function (c) { c.checked = on; });
					refrescar();
				});
			}
			if (btnCancelar) {
				btnCancelar.addEventListener('click', function () {
					selectEstado.value = '';
					cerrarBarraConfirmacion();
				});
			}
			if (btnAceptar) {
				btnAceptar.addEventListener('click', function () {
					if (pendienteEstado === null) return;
					enviarLote(pendienteEstado);
				});
			}
			selectEstado.addEventListener('change', function () {
				var valor = selectEstado.value;
				if (valor === '') {
					cerrarBarraConfirmacion();
					return;
				}
				var estadoDestino = parseInt(valor, 10) === 1 ? 1 : 0;
				var n = seleccionados().length;
				if (!n) {
					selectEstado.value = '';
					cerrarBarraConfirmacion();
					toast('Seleccione uno o más productos antes de cambiar la visibilidad en la web.', 'warning');
					return;
				}
				if (!barra) {
					enviarLote(estadoDestino);
					return;
				}
				abrirBarraConfirmacion(estadoDestino, n);
			});
			refrescar();
		})();

		var form = document.getElementById('formBusquedaProductos');
		if (!form) return;
		document.querySelectorAll('select.js-filtro-buscar').forEach(function (select) {
			var wrap = select.parentNode;
			var input = document.createElement('input');
			input.type = 'text';
			input.className = 'productos-filtro-input';
			input.placeholder = 'Buscar...';
			input.autocomplete = 'off';
			input.setAttribute('aria-label', select.id);
			var lista = document.createElement('ul');
			lista.className = 'productos-filtro-lista';
			wrap.appendChild(input);
			wrap.appendChild(lista);
			function textoElegido() {
				var elegida = Array.prototype.filter.call(select.options, function (o) { return o.value === select.value; })[0];
				return elegida ? elegida.text : '';
			}
			function pintar(filtro) {
				var q = (filtro || '').toLowerCase();
				lista.innerHTML = '';
				Array.prototype.forEach.call(select.options, function (o) {
					if (q && o.text.toLowerCase().indexOf(q) === -1) return;
					var li = document.createElement('li');
					li.textContent = o.text;
					if (o.value === select.value) li.className = 'is-elegida';
					li.addEventListener('mousedown', function (e) {
						e.preventDefault();
						select.value = o.value;
						input.value = o.text;
						lista.classList.remove('is-open');
						form.submit();
					});
					lista.appendChild(li);
				});
				if (!lista.children.length) {
					var vacio = document.createElement('li');
					vacio.className = 'is-vacio';
					vacio.textContent = 'Sin resultados';
					lista.appendChild(vacio);
				}
			}
			input.addEventListener('focus', function () {
				pintar('');
				lista.classList.add('is-open');
			});
			input.addEventListener('input', function () {
				pintar(input.value);
				lista.classList.add('is-open');
			});
			input.addEventListener('keydown', function (e) {
				if (e.key !== 'Enter') return;
				e.preventDefault();
				var primera = lista.querySelector('li:not(.is-vacio)');
				if (primera) primera.dispatchEvent(new MouseEvent('mousedown', { bubbles: true }));
			});
			input.addEventListener('blur', function () {
				setTimeout(function () { lista.classList.remove('is-open'); }, 150);
				input.value = textoElegido();
			});
			input.value = textoElegido();
		});
	})();
	</script>
	<?php include("includes/pie.php"); ?>

	</div>
</body>

</html>
