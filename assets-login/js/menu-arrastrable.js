$(document).ready(function() {
  // Habilitar arrastrar y soltar en los elementos del menú
  $('.draggable-menu').sortable({
      containment: '.draggable-menu', // Mantener la ordenación dentro del menú
      cursor: 'move', // Cambiar el cursor a "mover" durante el arrastre
      update: function(event, ui) { // Función al actualizar el orden

          // Obtener el índice final después de que el usuario deja de arrastrar
          let endIndex = ui.item.parent().children().index(ui.item);
          // Obtener el ID del elemento arrastrado
          let elementId = ui.item.attr('id');

          // Enviar los datos al servidor usando fetch con método GET
          fetch('bd_update/actualizar-posicion-menu.php?id='+elementId+'&endPosition='+endIndex, {
              method: 'GET'
          })
          .then(response => response.text()) // Convertir la respuesta a texto
          .then(data => {
            // Mostrar una notificación de éxito
            Toastify({
                text: "La accion ha sido realizada correctamente",
                duration: 3000,
                gravity: "bottom",
                position: "right",
                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                stopOnFocus: true,
                className: "toastify--fadeIn", // Aplicar el efecto fadeIn
                style: {
                    /* Estilos personalizados */
                    'text-align': 'center' // Centrar el texto horizontalmente
                }
            }).showToast();
          })
          .catch(error => {
              // Manejar errores
              console.error('Error:', error);
          });
      }
  });
});


  