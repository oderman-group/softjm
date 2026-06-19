// ========== VARIABLES GLOBALES ==========
var select = null; // Se inicializa cuando el DOM esté listo
var todasLasPaginas = []; // Array para almacenar todas las páginas
var paginasFiltradas = []; // Array para las páginas filtradas por búsqueda
var listaModulosGlobal = []; // Lista de todos los módulos (id, nombre) para el dropdown
var rolIdGlobal = 0; // ID del rol actual para recargar tras cambiar módulo

// Inicializar select cuando el DOM esté listo
function initSelect() {
    if (!select) {
        select = document.getElementById('paginasSeleccionadas');
    }
    return select;
}

// ========== MEJORAR VISUALIZACIÓN DE SELECTS ==========

/**
 * Mejora la visualización de los selects cuando se selecciona una opción
 */
function mejorarSelects() {
    // Select de roles
    const roleSwitcher = document.getElementById('roleSwitcher');
    if (roleSwitcher) {
        roleSwitcher.addEventListener('change', function() {
            if (this.value) {
                this.style.color = '#333';
                this.style.fontWeight = '700';
                this.style.background = 'white';
            } else {
                this.style.color = '#999';
                this.style.fontWeight = '400';
                this.style.background = '#fafafa';
            }
        });
        
        // Aplicar estilo inicial
        if (roleSwitcher.value) {
            roleSwitcher.style.color = '#333';
            roleSwitcher.style.fontWeight = '700';
            roleSwitcher.style.background = 'white';
        }
    }
    
    // Select de usuarios
    const userSelect = document.getElementById('userToAdd');
    if (userSelect) {
        userSelect.addEventListener('change', function() {
            if (this.value) {
                // Auto-agregar usuario al rol cuando se selecciona
                agregarUsuarioAlRol();
            }
        });
        
        // Aplicar estilo inicial
        if (userSelect.value) {
            userSelect.style.color = '#333';
            userSelect.style.fontWeight = '700';
            userSelect.style.background = 'white';
        }
    }
}

/**
 * Actualiza la lista de usuarios disponibles en el select
 * Remueve usuarios que ya están asignados al rol actual
 */
function actualizarUsuariosDisponibles() {
    const rolId = new URLSearchParams(window.location.search).get('id');
    const userSelect = document.getElementById('userToAdd');
    
    if (!userSelect) return;
    
    // Obtener usuarios actuales del rol
    fetch(`ajax/ajax-usuarios-rol.php?rolId=${rolId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const usuariosAsignados = data.usuarios.map(u => u.id);
                
                // Filtrar opciones del select
                const options = userSelect.querySelectorAll('option');
                options.forEach(option => {
                    if (option.value && usuariosAsignados.includes(parseInt(option.value))) {
                        option.style.display = 'none';
                        option.disabled = true;
                    } else if (option.value) {
                        option.style.display = 'block';
                        option.disabled = false;
                    }
                });
                
                // Si no hay usuarios disponibles, mostrar mensaje
                const usuariosDisponibles = Array.from(options).filter(opt => 
                    opt.value && !opt.disabled
                );
                
                if (usuariosDisponibles.length === 0) {
                    userSelect.innerHTML = '<option value="">No hay usuarios disponibles para agregar</option>';
                    userSelect.disabled = true;
                }
            }
        })
        .catch(error => {
            console.error('Error al actualizar usuarios disponibles:', error);
        });
}

// ========== FUNCIÓN PRINCIPAL: CARGAR TODOS LOS MÓDULOS ==========
/**
 * Carga todos los módulos con sus páginas de una sola vez
 * @param {int} tipoUsuario - ID del tipo de usuario/rol
 */
function cargarTodosLosModulos(tipoUsuario) {
    $('#modulesView').html('<div style="text-align: center; padding: 40px;"><div class="loading-spinner"></div><p style="margin-top: 20px;">Cargando módulos y páginas...</p></div>');
    
    fetch('ajax/ajax-todos-modulos-paginas.php?tipoUsuario=' + tipoUsuario, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        console.log('Datos recibidos del servidor:', data);
        todasLasPaginas = data.paginas || [];
        listaModulosGlobal = (data.modulos || []).map(m => ({ id: m.id, nombre: m.nombre }));
        rolIdGlobal = tipoUsuario;
        renderizarModulos(data.modulos || []);
        actualizarContadores();
    })
    .catch(error => {
        console.error('Error:', error);
        $('#modulesView').html('<div class="alert alert-danger">Error al cargar los datos. Por favor, recarga la página.</div>');
    });
}

// ========== RENDERIZAR MÓDULOS ==========
/**
 * Renderiza todos los módulos con sus páginas
 * @param {array} modulos - Array de módulos con sus páginas
 */
function renderizarModulos(modulos) {
    let html = '';
    
    modulos.forEach((modulo, index) => {
        const totalPaginas = modulo.paginas.length;
        const paginasActivas = modulo.paginas.filter(p => p.tiene_permiso).length;
        const porcentaje = totalPaginas > 0 ? Math.round((paginasActivas / totalPaginas) * 100) : 0;
        
        html += `
        <div class="module-view" data-module-id="${modulo.id}">
            <div class="module-header" onclick="toggleModule(${modulo.id})">
                <div class="module-title">
                    <i class="fa-solid fa-folder"></i>
                    <span>${modulo.nombre}</span>
                    <span class="module-badge">${totalPaginas} páginas</span>
                </div>
                <div class="module-stats">
                    <span><i class="fa-solid fa-check-circle"></i> ${paginasActivas}/${totalPaginas} activas (${porcentaje}%)</span>
                    <i class="fa-solid fa-chevron-down" id="arrow-${modulo.id}"></i>
                </div>
            </div>
            <div class="module-content ${index === 0 ? 'active' : ''}" id="module-content-${modulo.id}">
                ${renderizarTablaPaginas(modulo.paginas, modulo.id, modulos)}
            </div>
        </div>
        `;
    });
    
    $('#modulesView').html(html);
}

// ========== RENDERIZAR TABLA DE PÁGINAS ==========
/**
 * Renderiza la tabla de páginas de un módulo
 * @param {array} paginas - Array de páginas del módulo
 * @param {int} moduloId - ID del módulo actual
 * @param {array} todosLosModulos - Array de todos los módulos (id, nombre) para el dropdown
 */
function renderizarTablaPaginas(paginas, moduloId, todosLosModulos) {
    if (paginas.length === 0) {
        return '<div style="padding: 20px; text-align: center; color: #999;">No hay páginas en este módulo</div>';
    }
    const modulos = todosLosModulos || listaModulosGlobal;
    
    let html = `
    <div class="pages-table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h5 style="margin: 0;"><i class="fa-solid fa-file"></i> Páginas del Módulo</h5>
            <button type="button" class="btn btn-sm btn-primary" onclick="toggleAllPages(${moduloId})">
                <i class="fa-solid fa-check-double"></i> Seleccionar Todas
            </button>
        </div>
        <table class="table-modern">
            <thead>
                <tr>
                    <th width="80">ID</th>
                    <th>Nombre de la Página</th>
                    <th width="140">Módulo</th>
                    <th width="150">Ruta</th>
                    <th width="120" style="text-align: center;">Permiso</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    paginas.forEach(pagina => {
        const checked = pagina.tiene_permiso ? 'checked' : '';
        const descripcion = pagina.descripcion || 'Sin descripción disponible';
        const rutaCompleta = (pagina.ruta || '').toLowerCase();
        const rutaFinal = rutaCompleta.split('/').pop() || rutaCompleta;
        const moduloActual = pagina.modulo_id != null ? pagina.modulo_id : moduloId;
        let optionsModulo = '';
        modulos.forEach(m => {
            const sel = (m.id === moduloActual) ? ' selected' : '';
            optionsModulo += `<option value="${m.id}"${sel}>${escapeHtml(m.nombre)}</option>`;
        });
        html += `
        <tr data-page-id="${pagina.id}" data-page-name="${pagina.nombre.toLowerCase()}" data-page-route="${rutaCompleta}" data-page-route-end="${rutaFinal}" data-page-description="${descripcion.toLowerCase()}" data-page-permiso="${pagina.tiene_permiso ? '1' : '0'}">
            <td><strong>#${pagina.id}</strong></td>
            <td>
                <div class="page-info">
                    <span class="page-name">${pagina.nombre}</span>
                    ${pagina.descripcion ? `<span class="page-description"><i class="fa-solid fa-info-circle"></i> ${pagina.descripcion}</span>` : ''}
                    ${pagina.ruta ? `<span class="page-route"><i class="fa-solid fa-file-code"></i> ${pagina.ruta}</span>` : ''}
                </div>
            </td>
            <td>
                <select class="page-module-select form-control" data-page-id="${pagina.id}" title="Cambiar módulo (se guarda automáticamente)" onchange="cambiarModuloPagina(${pagina.id}, this.value)">
                    ${optionsModulo}
                </select>
            </td>
            <td><code style="font-size: 11px;">${pagina.ruta || 'N/A'}</code></td>
            <td style="text-align: center;">
                <label class="custom-checkbox">
                    <input type="checkbox" 
                           class="page-checkbox" 
                           value="${pagina.id}" 
                           data-module-id="${moduloId}"
                           onchange="seleccionarPagina(this)" 
                           ${checked}>
                    <span class="checkbox-slider"></span>
                </label>
            </td>
        </tr>
        `;
    });
    
    html += `
            </tbody>
        </table>
    </div>
    `;
    
    return html;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ========== TOGGLE MODULE (EXPANDIR/CONTRAER) ==========
/**
 * Expande o contrae un módulo
 * @param {int} moduleId - ID del módulo
 */
function toggleModule(moduleId) {
    const content = document.getElementById('module-content-' + moduleId);
    const arrow = document.getElementById('arrow-' + moduleId);
    
    if (content.classList.contains('active')) {
        content.classList.remove('active');
        arrow.style.transform = 'rotate(0deg)';
    } else {
        content.classList.add('active');
        arrow.style.transform = 'rotate(180deg)';
    }
}

// ========== SELECCIONAR/DESELECCIONAR TODAS LAS PÁGINAS DE UN MÓDULO ==========
/**
 * Selecciona o deselecciona todas las páginas de un módulo
 * @param {int} moduleId - ID del módulo
 */
function toggleAllPages(moduleId) {
    const checkboxes = document.querySelectorAll(`.page-checkbox[data-module-id="${moduleId}"]`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
        if (allChecked) {
            if (checkbox.checked) {
                checkbox.checked = false;
                eliminarPagina(checkbox.value);
            }
        } else {
            if (!checkbox.checked) {
                checkbox.checked = true;
                agregarPagina(checkbox.value);
            }
        }
    });
    
    actualizarContadores();
}

// ========== SELECCIONAR/DESELECCIONAR PÁGINA INDIVIDUAL ==========
/**
 * Maneja la selección/deselección de una página individual
 * @param {HTMLElement} checkbox - Elemento checkbox
 */
function seleccionarPagina(checkbox) {
    const pageId = checkbox.value;
    
    if (checkbox.checked) {
        agregarPagina(pageId);
    } else {
        eliminarPagina(pageId);
    }
    
    actualizarContadores();
}

// ========== AGREGAR PÁGINA AL SELECT OCULTO ==========
/**
 * Agrega una página al select oculto para enviar al servidor
 * @param {string} pageId - ID de la página
 */
function agregarPagina(pageId) {
    // Verificar si ya existe
    if (document.getElementById('pag-' + pageId)) {
        return;
    }
    
    var selectElement = initSelect();
    if (!selectElement) {
        console.error('Elemento select no encontrado');
        return;
    }
    
    var nuevaOpcion = document.createElement('option');
    nuevaOpcion.value = pageId;
    nuevaOpcion.id = "pag-" + pageId;
    nuevaOpcion.textContent = pageId;
    nuevaOpcion.selected = true;
    selectElement.appendChild(nuevaOpcion);
}

// ========== ELIMINAR PÁGINA DEL SELECT OCULTO ==========
/**
 * Elimina una página del select oculto
 * @param {string} pageId - ID de la página
 */
function eliminarPagina(pageId) {
    var selectElement = initSelect();
    var opcionAEliminar = document.getElementById('pag-' + pageId);
    if (opcionAEliminar && selectElement) {
        selectElement.removeChild(opcionAEliminar);
    }
}

// ========== ACTUALIZAR CONTADORES ==========
/**
 * Actualiza los contadores de páginas totales y seleccionadas
 */
function actualizarContadores() {
    const totalPaginas = todasLasPaginas.length;
    const seleccionadas = document.querySelectorAll('.page-checkbox:checked').length;
    
    document.getElementById('totalPages').textContent = totalPaginas;
    document.getElementById('selectedPages').textContent = seleccionadas;
}

// ========== INICIALIZAR FUNCIONALIDAD DE BÚSQUEDA ==========
/**
 * Inicializa la funcionalidad del buscador general
 */
function initSearchFunctionality() {
    const searchInput = document.getElementById('searchGlobal');
    const clearBtn = document.getElementById('clearSearch');
    const searchStats = document.getElementById('searchStats');
    
    // Búsqueda en tiempo real con debounce
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = this.value.trim();
        
        if (searchTerm.length > 0) {
            clearBtn.classList.add('active');
            searchTimeout = setTimeout(() => {
                buscarPaginas(searchTerm);
            }, 300);
        } else {
            clearBtn.classList.remove('active');
            searchStats.classList.remove('active');
            mostrarTodasLasPaginas();
        }
    });
    
    // Limpiar búsqueda
    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        clearBtn.classList.remove('active');
        searchStats.classList.remove('active');
        mostrarTodasLasPaginas();
    });
    
    // Buscar al presionar Enter
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            buscarPaginas(this.value.trim());
        }
    });
}

// ========== BUSCAR PÁGINAS ==========
/**
 * Busca páginas por nombre, módulo o ruta
 * @param {string} searchTerm - Término de búsqueda
 */
function buscarPaginas(searchTerm) {
    if (!searchTerm) {
        mostrarTodasLasPaginas();
        return;
    }
    const term = searchTerm.toLowerCase();
    const allRows = document.querySelectorAll('.table-modern tbody tr');
    let visibleCount = 0;
    let totalCount = 0;
    
    // Ocultar todos los módulos primero
    document.querySelectorAll('.module-view').forEach(module => {
        module.style.display = 'none';
    });
    
    allRows.forEach(row => {
        totalCount++;
        const pageName = row.getAttribute('data-page-name') || '';
        const pageRouteEnd = row.getAttribute('data-page-route-end') || row.getAttribute('data-page-route') || '';
        const pageDescription = row.getAttribute('data-page-description') || '';
        const moduleElement = row.closest('.module-view');
        const moduleName = moduleElement ? 
            moduleElement.querySelector('.module-title span').textContent.toLowerCase() : '';
        
        const matchSearch = pageName.includes(term) || pageRouteEnd.includes(term) || moduleName.includes(term) || pageDescription.includes(term);
        const show = matchSearch;
        if (show) {
            row.style.display = 'table-row';
            row.classList.remove('hidden-by-search');
            visibleCount++;
            if (moduleElement) {
                moduleElement.style.display = 'block';
                const content = moduleElement.querySelector('.module-content');
                content.classList.add('active');
                const arrow = moduleElement.querySelector('[id^="arrow-"]');
                if (arrow) arrow.style.transform = 'rotate(180deg)';
            }
        } else {
            row.style.display = 'none';
            row.classList.add('hidden-by-search');
        }
    });
    
    document.querySelectorAll('.module-view').forEach(module => {
        if (module.style.display !== 'none') {
            const visibleRows = module.querySelectorAll('.table-modern tbody tr:not(.hidden-by-search)');
            if (visibleRows.length === 0) module.style.display = 'none';
        }
    });
    
    // Mostrar estadísticas
    const searchStats = document.getElementById('searchStats');
    searchStats.innerHTML = `
        <i class="fa-solid fa-filter"></i> 
        Se encontraron <strong>${visibleCount}</strong> páginas de <strong>${totalCount}</strong> totales
        ${visibleCount === 0 ? '<span style="color: #f44336; margin-left: 10px;">No se encontraron resultados</span>' : ''}
    `;
    searchStats.classList.add('active');
}

// ========== CAMBIAR MÓDULO DE UNA PÁGINA ==========
/**
 * Cambia el módulo asociado a una página. Guardado automático vía AJAX.
 * @param {int} paginaId - ID de la página
 * @param {string} moduloId - ID del nuevo módulo
 */
function cambiarModuloPagina(paginaId, moduloId) {
    if (!moduloId) return;
    const selectEl = document.querySelector('.page-module-select[data-page-id="' + paginaId + '"]');
    if (selectEl) selectEl.disabled = true;
    const formData = new FormData();
    formData.append('pagina_id', paginaId);
    formData.append('modulo_id', moduloId);
    fetch('ajax/ajax-pagina-cambiar-modulo.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarNotificacion('Módulo actualizado correctamente. Recargando...', 'success');
            if (rolIdGlobal) cargarTodosLosModulos(rolIdGlobal);
        } else {
            mostrarNotificacion(data.error || 'Error al cambiar el módulo', 'error');
            if (selectEl) selectEl.disabled = false;
        }
    })
    .catch(err => {
        console.error(err);
        mostrarNotificacion('Error de conexión', 'error');
        if (selectEl) selectEl.disabled = false;
    });
}

// ========== MOSTRAR TODAS LAS PÁGINAS ==========
/**
 * Muestra todas las páginas (quita el filtro de búsqueda)
 */
function mostrarTodasLasPaginas() {
    document.querySelectorAll('.module-view').forEach(module => {
        module.style.display = 'block';
        const rows = module.querySelectorAll('.table-modern tbody tr');
        rows.forEach(row => {
            row.style.display = 'table-row';
            row.classList.remove('hidden-by-search');
        });
        const content = module.querySelector('.module-content');
        const arrow = module.querySelector('[id^="arrow-"]');
        if (module === document.querySelector('.module-view:first-child')) {
            content.classList.add('active');
            if (arrow) arrow.style.transform = 'rotate(180deg)';
        } else {
            content.classList.remove('active');
            if (arrow) arrow.style.transform = 'rotate(0deg)';
        }
    });
}

// ========== FUNCIONES LEGACY (para compatibilidad) ==========
/**
 * Función legacy - mantener por compatibilidad
 * @param {int} modulo 
 * @param {int} tipoUsuario 
 */
function mostrarPaginas(modulo, tipoUsuario) {
    // Esta función ya no se usa pero se mantiene por compatibilidad
    console.log('Función mostrarPaginas es legacy, ahora se usa cargarTodosLosModulos');
}

// ========== GESTIÓN DE USUARIOS DEL ROL ==========

/**
 * Carga los usuarios que tienen el rol actual
 * @param {int} rolId - ID del rol
 */
function cargarUsuariosDelRol(rolId) {
    const usersGrid = document.getElementById('usersGrid');
    const usersCount = document.getElementById('usersCount');
    
    usersGrid.innerHTML = '<div style="text-align: center; padding: 20px;"><div class="loading-spinner"></div></div>';
    
    fetch(`ajax/ajax-usuarios-rol.php?rolId=${rolId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                usersCount.textContent = data.total;
                
                if (data.usuarios.length === 0) {
                    usersGrid.innerHTML = `
                        <div class="empty-state">
                            <i class="fa-solid fa-users-slash"></i>
                            <p>No hay usuarios asignados a este rol</p>
                        </div>
                    `;
                } else {
                    usersGrid.innerHTML = data.usuarios.map(usuario => `
                        <div class="user-card" data-user-id="${usuario.id}">
                            <div class="user-info">
                                <div class="user-avatar">${usuario.iniciales}</div>
                                <div class="user-details">
                                    <div class="user-name">${usuario.nombre}</div>
                                    <div class="user-email">${usuario.email}</div>
                                </div>
                            </div>
                            <button type="button" class="btn-remove-user" 
                                    onclick="quitarUsuarioDelRol(${usuario.id})" 
                                    title="Quitar usuario del rol">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </div>
                    `).join('');
                }
            } else {
                usersGrid.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            usersGrid.innerHTML = '<div class="alert alert-danger">Error al cargar usuarios</div>';
        });
}

/**
 * Agrega un usuario al rol actual (llamado automáticamente al seleccionar)
 */
function agregarUsuarioAlRol() {
    const userSelect = document.getElementById('userToAdd');
    const usuarioId = userSelect.value;
    const rolId = new URLSearchParams(window.location.search).get('id');
    
    if (!usuarioId) {
        return; // No hacer nada si no hay usuario seleccionado
    }
    
    // Deshabilitar select mientras se procesa
    userSelect.disabled = true;
    userSelect.style.opacity = '0.6';
    
    const formData = new FormData();
    formData.append('usuarioId', usuarioId);
    formData.append('rolId', rolId);
    
    fetch('ajax/ajax-agregar-usuario-rol.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar mensaje de éxito
            mostrarNotificacion('Usuario agregado correctamente', 'success');
            
            // Recargar lista de usuarios
            cargarUsuariosDelRol(rolId);
            
            // Resetear select y habilitarlo
            userSelect.value = '';
            userSelect.disabled = false;
            userSelect.style.opacity = '1';
            userSelect.style.color = '#999';
            userSelect.style.fontWeight = '400';
            userSelect.style.background = '#fafafa';
            
            // Actualizar lista de usuarios disponibles
            actualizarUsuariosDisponibles();
        } else {
            mostrarNotificacion(data.error || 'Error al agregar usuario', 'error');
            // Rehabilitar select en caso de error
            userSelect.disabled = false;
            userSelect.style.opacity = '1';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error de conexión', 'error');
        // Rehabilitar select en caso de error
        userSelect.disabled = false;
        userSelect.style.opacity = '1';
    });
}

/**
 * Quita un usuario del rol actual
 * @param {int} usuarioId - ID del usuario a quitar
 */
function quitarUsuarioDelRol(usuarioId) {
    if (!confirm('¿Estás seguro de quitar este usuario del rol?')) {
        return;
    }
    
    const rolId = new URLSearchParams(window.location.search).get('id');
    const userCard = document.querySelector(`.user-card[data-user-id="${usuarioId}"]`);
    
    // Animación de salida
    if (userCard) {
        userCard.style.opacity = '0.5';
        userCard.style.pointerEvents = 'none';
    }
    
    const formData = new FormData();
    formData.append('usuarioId', usuarioId);
    
    fetch('ajax/ajax-quitar-usuario-rol.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarNotificacion('Usuario removido correctamente', 'success');
            cargarUsuariosDelRol(rolId);
            
            // Actualizar lista de usuarios disponibles
            actualizarUsuariosDisponibles();
        } else {
            mostrarNotificacion(data.error || 'Error al remover usuario', 'error');
            if (userCard) {
                userCard.style.opacity = '1';
                userCard.style.pointerEvents = 'auto';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error al remover usuario', 'error');
        if (userCard) {
            userCard.style.opacity = '1';
            userCard.style.pointerEvents = 'auto';
        }
    });
}

/**
 * Cambia a otro rol (recarga la página con el nuevo rol)
 * @param {int} rolId - ID del rol al que cambiar
 */
function cambiarRol(rolId) {
    if (rolId) {
        window.location.href = `roles-editar.php?id=${rolId}`;
    }
}

/**
 * Muestra una notificación temporal
 * @param {string} mensaje - Mensaje a mostrar
 * @param {string} tipo - Tipo de notificación: 'success', 'error', 'info'
 */
function mostrarNotificacion(mensaje, tipo = 'info') {
    // Crear elemento de notificación
    const notif = document.createElement('div');
    notif.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        background: ${tipo === 'success' ? '#4caf50' : tipo === 'error' ? '#f44336' : '#2196f3'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        z-index: 10000;
        animation: slideInRight 0.3s ease;
        font-weight: 600;
    `;
    
    notif.innerHTML = `
        <i class="fa-solid fa-${tipo === 'success' ? 'check-circle' : tipo === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        ${mensaje}
    `;
    
    document.body.appendChild(notif);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        notif.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => notif.remove(), 300);
    }, 3000);
}

// Agregar animaciones CSS para notificaciones
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);