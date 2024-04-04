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
            $.toast({
              heading: 'Redireccionando',
              text: 'Se han modificado las posiciones de los modulos en el menú.',
              position: 'bottom-right',
              showHideTransition: 'slide',
              loaderBg: '#26c281',
              icon: 'warning',
              hideAfter: 5000,
              stack: 6
          });
          })
          .catch(error => {
              // Manejar errores
              console.error('Error:', error);
          });
      }
  });
});


  