/**
 * Me muestra los Sub modulos segun el modulo seleccionado
 * @param {int} modulo 
 */
function mostrarModulos(modulo, clioId){
    $('#divTableModulos').empty().hide().html("").show(1);
    
    fetch('ajax/ajax-modulos.php?modulo=' + modulo + '&clioId=' + clioId, {
        method: 'GET'
    })
    .then(response => response.text())
    .then(data => {
        $('#divTableModulos').empty().hide().html(data).show(1);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

var select = document.getElementById('ModulosSeleccionadas');

/**
 * Esta función verifica si el Modulo fue selecionado o no para agregar o eliminar de la seleción.
 * @param datos //Datos de la Modulo selecionado
 */
function seleccionarModulo(datos) {
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
        agregarModulo(page);
    } else {
        if(all.checked){
            all.checked=false;
        }
        eliminarModulo(page);
    }
}

/**
 * Esta función agrega un Modulo a la seleción cuando es selecionada.
 * @param page
 */
function agregarModulo(page) {
    var nuevaOpcion = document.createElement('option');
    nuevaOpcion.value = page;
    nuevaOpcion.id = "pag-"+page;
    nuevaOpcion.textContent = page;
    nuevaOpcion.selected = true;
    select.appendChild(nuevaOpcion);
}

/**
 * Esta función elimina una modulo de la seleción cuando deja de estar selecionada.
 * @param page 
 */
function eliminarModulo(page) {
    var opcionAEliminar = document.getElementById('pag-'+page);
    if (opcionAEliminar) {
        select.removeChild(opcionAEliminar);
    }
}

/**
 * Este metodo me muestra los li de los modulos
 **/
function mostrarLi(idCliente) {
    var ul = document.getElementById('myTab1'); // Obtener el ul
    ul.innerHTML = ''; // Limpiar el contenido del ul

    // Obtener las opciones seleccionadas
    var selectedOptions = document.querySelectorAll('select[name="modulo[]"] option:checked');
    
    // Iterar sobre las opciones seleccionadas
    selectedOptions.forEach(function(option) {
        // Crear un nuevo elemento li
        var li = document.createElement('li');
    
        // Crear un nuevo elemento a dentro del li
        var a = document.createElement('a');
        a.onclick = function() {
            mostrarModulos(option.value, idCliente);
        };
        var i = document.createElement('i');
        i.classList.add('icon-tasks');
        var text = document.createTextNode(option.textContent);
        a.appendChild(i);
        a.appendChild(text);
        
        // Agregar el elemento a al li
        li.appendChild(a);
        
        // Establecer un identificador único para el li (puedes usar el valor de la opción)
        li.id = 'mod' + option.value;
        // Agregar el li al ul
        ul.appendChild(li);
    });
}