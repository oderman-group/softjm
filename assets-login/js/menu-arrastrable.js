$(document).ready(function() {
    // Habilitar arrastrar y soltar en los elementos del menú
    $('.draggable-menu').sortable({
      containment: '.draggable-menu', // Mantener la ordenación dentro del menú
      cursor: 'move', // Cambiar el cursor a "mover" durante el arrastre
      update: function(event, ui) { // Función al actualizar el orden
        console.log(event, ui);
        
      }
    });
  });
  