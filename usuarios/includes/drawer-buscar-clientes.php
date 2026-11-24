<?php
require_once RUTA_PROYECTO . '/usuarios/includes/api-ofima-conexion.php';
$drawerClientesOfimaActiva = ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa);
$drawerClientesRestringeZona = !Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$drawerClientesAyuda = $drawerClientesRestringeZona
    ? 'Se listan los clientes de sus zonas. Si no está en Ofima, créelo para que aparezca en la cotización.'
    : 'Aquí están todos los clientes. Si no está en Ofima, créelo para que aparezca en la lista de la cotización.';
?>
<style>
#drawerBuscarClientesOverlay {
	position: fixed;
	inset: 0;
	background: rgba(15, 23, 42, 0.45);
	z-index: 99998;
	opacity: 0;
	visibility: hidden;
	pointer-events: none;
	transition: opacity 0.3s ease, visibility 0.3s ease;
}
#drawerBuscarClientesOverlay.is-open { opacity: 1; visibility: visible; pointer-events: auto; }
#drawerBuscarClientes {
	position: fixed;
	top: 0;
	right: 0;
	width: min(100vw, calc(50vw + 320px));
	height: 100vh;
	height: 100dvh;
	margin: 0;
	background: #fff;
	z-index: 100010;
	display: flex;
	flex-direction: column;
	box-shadow: -8px 0 32px rgba(15, 23, 42, 0.15);
	transform: translateX(100%);
	transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}
#drawerBuscarClientes.is-open { transform: translateX(0); }
body.drawer-clientes-open { overflow: hidden; }
#drawerBuscarClientes .drawer-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 0.75rem;
	padding: 1rem 1.15rem;
	background: #1d4ed8;
	color: #fff;
}
#drawerBuscarClientes .drawer-header h2 { margin: 0; font-size: 1.05rem; font-weight: 700; }
#drawerBuscarClientes .drawer-close {
	width: 36px;
	height: 36px;
	border: 0;
	border-radius: 8px;
	background: rgba(255,255,255,0.16);
	color: #fff;
	font-size: 1.25rem;
	line-height: 1;
	cursor: pointer;
}
#drawerBuscarClientes .drawer-body { flex: 1; overflow: auto; padding: 1rem 1.15rem 1.25rem; }
#drawerBuscarClientes .buscar-clientes-ayuda { margin: 0 0 0.75rem; color: #64748b; font-size: 0.8125rem; }
#drawerBuscarClientes .buscar-clientes-input {
	width: 100%;
	height: 40px;
	margin: 0 0 0.85rem;
	padding: 0 0.75rem;
	border: 1px solid #cbd5e1;
	border-radius: 8px;
	box-sizing: border-box;
	box-shadow: none;
}
#drawerBuscarClientes .buscar-clientes-lista { display: flex; flex-direction: column; gap: 0.55rem; }
#drawerBuscarClientes .buscar-clientes-item {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 0.75rem;
	padding: 0.7rem 0.75rem;
	border: 1px solid #e2e8f0;
	border-radius: 10px;
	background: #f8fafc;
}
#drawerBuscarClientes .buscar-clientes-item strong { display: block; color: #0f172a; font-size: 0.875rem; }
#drawerBuscarClientes .buscar-clientes-item span { display: block; color: #64748b; font-size: 0.75rem; }
#drawerBuscarClientes .buscar-clientes-item { flex-wrap: wrap; }
#drawerBuscarClientes .buscar-clientes-acciones { display: flex; flex-direction: row; align-items: center; gap: 0.35rem; flex-shrink: 0; }
#drawerBuscarClientes .buscar-clientes-item .btn { border-radius: 8px; white-space: nowrap; }
#drawerBuscarClientes .buscar-clientes-error {
	flex: 1 0 100%;
	margin: 0.15rem 0 0;
	padding: 0.55rem 0.65rem;
	border-radius: 8px;
	background: #fef2f2;
	color: #991b1b;
	font-size: 0.75rem;
	line-height: 1.35;
	white-space: pre-wrap;
}
#drawerBuscarClientes .buscar-clientes-vacio { margin: 0.5rem 0 0; color: #64748b; font-size: 0.8125rem; }
#drawerBuscarClientes .buscar-clientes-estado {
	display: inline-block;
	padding: 0.2rem 0.55rem;
	border-radius: 999px;
	background: #dcfce7;
	color: #166534;
	font-size: 0.75rem;
	font-weight: 700;
}
</style>
<div id="drawerBuscarClientesOverlay"></div>
<aside id="drawerBuscarClientes" aria-hidden="true">
	<div class="drawer-header">
		<h2>Buscar clientes</h2>
		<button type="button" class="drawer-close" id="cerrarBuscarClientes" aria-label="Cerrar">×</button>
	</div>
	<div class="drawer-body">
		<p class="buscar-clientes-ayuda"><?= htmlspecialchars($drawerClientesAyuda); ?></p>
		<input type="search" class="buscar-clientes-input" id="buscarClientesInput" placeholder="Nombre, documento o correo" autocomplete="off">
		<div class="buscar-clientes-lista" id="buscarClientesLista"></div>
		<p class="buscar-clientes-vacio" id="buscarClientesVacio">Escriba al menos 2 letras.</p>
	</div>
</aside>
<script>
(function () {
	var boton = document.getElementById('btnBuscarClientes');
	var drawer = document.getElementById('drawerBuscarClientes');
	var overlay = document.getElementById('drawerBuscarClientesOverlay');
	var cerrar = document.getElementById('cerrarBuscarClientes');
	var input = document.getElementById('buscarClientesInput');
	var lista = document.getElementById('buscarClientesLista');
	var vacio = document.getElementById('buscarClientesVacio');
	var ofimaActiva = <?= $drawerClientesOfimaActiva ? 'true' : 'false'; ?>;
	var timer = null;
	if (!boton || !drawer || !input || !lista) return;

	function abrir() {
		drawer.classList.add('is-open');
		overlay.classList.add('is-open');
		drawer.setAttribute('aria-hidden', 'false');
		document.body.classList.add('drawer-clientes-open');
		input.focus();
	}
	function cerrarDrawer() {
		drawer.classList.remove('is-open');
		overlay.classList.remove('is-open');
		drawer.setAttribute('aria-hidden', 'true');
		document.body.classList.remove('drawer-clientes-open');
	}
	function texto(valor) {
		return String(valor || '').replace(/[&<>"']/g, function (c) {
			return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[c];
		});
	}
	function agregarALista(cliente) {
		var select = document.querySelector('select[name="cliente"]');
		if (!select || !cliente || !cliente.id) return;
		if (select.querySelector('option[value="' + cliente.id + '"]')) return;
		var option = document.createElement('option');
		option.value = String(cliente.id);
		option.textContent = cliente.nombre || ('Cliente #' + cliente.id);
		select.appendChild(option);
	}
	function pintar(clientes) {
		lista.innerHTML = '';
		if (!clientes.length) {
			vacio.hidden = false;
			vacio.textContent = 'No hay clientes con ese texto.';
			return;
		}
		vacio.hidden = true;
		clientes.forEach(function (cliente) {
			var item = document.createElement('div');
			item.className = 'buscar-clientes-item';
			var detalle = [cliente.documento, cliente.email].filter(Boolean).join(' · ');
			var accion = '';
			var editar = '<button type="button" class="btn btn-info btn-editar-cliente" data-id="' + cliente.id + '">Editar</button>';
			if (cliente.integrado_ofima) {
				accion = '<div class="buscar-clientes-acciones"><span class="buscar-clientes-estado">En Ofima</span>' + editar + '</div>';
			} else if (ofimaActiva) {
				accion = '<div class="buscar-clientes-acciones">' + editar + '<button type="button" class="btn btn-warning btn-crear-ofima" data-id="' + cliente.id + '">Crear en Ofima</button></div>';
			} else {
				accion = '<div class="buscar-clientes-acciones">' + editar + '<span class="buscar-clientes-estado" style="background:#f1f5f9;color:#475569;">Sin Ofima</span></div>';
			}
			item.innerHTML = '<div><strong>' + texto(cliente.nombre) + '</strong><span>' + texto(detalle) + '</span></div>' + accion;
			item.dataset.nombre = cliente.nombre || '';
			lista.appendChild(item);
		});
	}
	function buscar() {
		var q = input.value.trim();
		if (q.length < 2) {
			lista.innerHTML = '';
			vacio.hidden = false;
			vacio.textContent = 'Escriba al menos 2 letras.';
			return;
		}
		vacio.hidden = false;
		vacio.textContent = 'Buscando...';
		fetch('ajax/ajax-buscar-clientes-cotizacion.php?q=' + encodeURIComponent(q), { credentials: 'same-origin' })
			.then(function (r) { return r.json(); })
			.then(function (data) { pintar((data && data.clientes) || []); })
			.catch(function () {
				lista.innerHTML = '';
				vacio.hidden = false;
				vacio.textContent = 'No se pudo buscar.';
			});
	}

	boton.addEventListener('click', function (e) {
		e.preventDefault();
		abrir();
	});
	cerrar.addEventListener('click', cerrarDrawer);
	overlay.addEventListener('click', cerrarDrawer);
	input.addEventListener('input', function () {
		clearTimeout(timer);
		timer = setTimeout(buscar, 300);
	});
	function mostrarErrorItem(item, data) {
		if (!item) return;
		var caja = item.querySelector('.buscar-clientes-error');
		if (!caja) {
			caja = document.createElement('div');
			caja.className = 'buscar-clientes-error';
			item.appendChild(caja);
		}
		var lineas = [];
		if (data && data.message) lineas.push(String(data.message));
		if (data && data.codigo_http) lineas.push('Código: ' + data.codigo_http);
		if (data && data.detalle && String(data.detalle) !== String(data.message || '')) lineas.push(String(data.detalle));
		caja.textContent = lineas.join('\n\n') || 'No se pudo crear en Ofima.';
		caja.hidden = false;
	}
	window.alActualizarClienteBusqueda = function (cliente) {
		if (!cliente || !cliente.id) return;
		var botonEditar = lista.querySelector('.btn-editar-cliente[data-id="' + cliente.id + '"]');
		var item = botonEditar ? botonEditar.closest('.buscar-clientes-item') : null;
		if (!item) return;
		var nombre = item.querySelector('strong');
		var detalle = item.querySelector('span');
		if (nombre && cliente.nombre) nombre.textContent = cliente.nombre;
		if (detalle) detalle.textContent = [cliente.documento, cliente.email].filter(Boolean).join(' · ');
		var error = item.querySelector('.buscar-clientes-error');
		if (error) error.remove();
		item.dataset.nombre = cliente.nombre || item.dataset.nombre || '';
	};
	lista.addEventListener('click', function (e) {
		var editar = e.target.closest('.btn-editar-cliente');
		if (editar) {
			if (typeof window.abrirDrawerEditarCliente === 'function') {
				window.abrirDrawerEditarCliente(editar.getAttribute('data-id'));
			}
			return;
		}
		var btn = e.target.closest('.btn-crear-ofima');
		if (!btn || btn.disabled) return;
		var id = btn.getAttribute('data-id');
		var item = btn.closest('.buscar-clientes-item');
		if (!window.confirm('¿Crear este cliente en Ofima?')) return;
		btn.disabled = true;
		btn.textContent = 'Creando...';
		var cuerpo = new FormData();
		cuerpo.append('id', id);
		fetch('ajax/ajax-cliente-crear-ofima.php', { method: 'POST', body: cuerpo, credentials: 'same-origin' })
			.then(function (r) { return r.json(); })
			.then(function (data) {
				if (data && data.success && data.integrado_ofima) {
					agregarALista({ id: data.id || id, nombre: data.nombre || (item ? item.dataset.nombre : '') });
					btn.outerHTML = '<span class="buscar-clientes-estado">En Ofima</span>';
					return;
				}
				btn.disabled = false;
				btn.textContent = 'Crear en Ofima';
				mostrarErrorItem(item, data || { message: 'No se pudo crear en Ofima.' });
			})
			.catch(function () {
				btn.disabled = false;
				btn.textContent = 'Crear en Ofima';
				mostrarErrorItem(item, { message: 'No se pudo crear en Ofima.' });
			});
	});
})();
</script>
