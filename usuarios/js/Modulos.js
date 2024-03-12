/**
 * Me muestra las paginas segun el modulo seleccionado
 * @param {int} modulo 
 */
function mostrarModulos(modulo, clioId){
    $('#divTablePaginas').empty().hide().html("").show(1);
    
    fetch('ajax/ajax-modulos.php?modulo=' + modulo + '&clioId=' + clioId, {
        method: 'GET'
    })
    .then(response => response.text())
    .then(data => {
        $('#divTablePaginas').empty().hide().html(data).show(1);
        
        // Inicializar DataTables después de agregar las nuevas filas
        $('#data-table').DataTable();
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

var select = document.getElementById('ModulosSeleccionadas');

/**
 * Esta función verifica si la pagina fue selecionada o no para agregar o eliminar de la seleción.
 * @param datos //Datos de la pagina selecionada
 */
function seleccionarPagina(datos) {
    var page = datos.value;
    var all = document.getElementById('all'); 
    if (datos.checked) {
        var cont=0;
        checkboxes.forEach(function(checkElement) {
            if(checkElement.checked){
                cont=cont+1;
            }
        });
        if(cont==checkboxes.length){
            all.checked=true;
        }
        agregarPagina(page);
    } else {
        if(all.checked){
            all.checked=false;
        }
        eliminarPagina(page);
    }
}

/**
 * Esta función agrega una pagina a la seleción cuando es selecionada.
 * @param page
 */
function agregarPagina(page) {
    var nuevaOpcion = document.createElement('option');
    nuevaOpcion.value = page;
    nuevaOpcion.id = "pag-"+page;
    nuevaOpcion.textContent = page;
    nuevaOpcion.selected = true;
    select.appendChild(nuevaOpcion);
}

/**
 * Esta función elimina una pagina de la seleción cuando deja de estar selecionada.
 * @param page 
 */
function eliminarPagina(page) {
    var opcionAEliminar = document.getElementById('pag-'+page);
    if (opcionAEliminar) {
        select.removeChild(opcionAEliminar);
    }
}