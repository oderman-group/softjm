$(document).ready(function() {

	// ID del contenedor (fieldset) que quieres ocultar/mostrar
    const fieldsetContainerId = 'campos_controlados';
    const $fieldsetContainer = $('#' + fieldsetContainerId);

    const $checkboxControl = $('#miCheckboxControl'); // Selecciona el checkbox
    const fieldIdsToControl = ['fechaPC', 'horaPC', 'minutosRecordarAntes', 'canalPC', 'asunto']; // Los IDs de tus campos

    const tipoTicket = document.getElementById("tik_tipo_tiket").value;
    const tipoNegocio = document.getElementById("tik_tipo_negocio").value;

    // Define la lógica de toggle (puede ser anónima o una función nombrada)
    const applyToggleLogic = function() {
        const isChecked = $checkboxControl.is(':checked'); // Usa .is(':checked') de jQuery

        $fields = fieldIdsToControl.map(id => $('#' + id)); // Seleccionar los campos cada vez

        if (isChecked) {
            $fields.forEach($field => {
                $field.prop('required', false);
            });
            $fieldsetContainer.hide();

            if (tipoTicket == 1 && tipoNegocio == 1) {
                bootbox.alert("Al cerrar el ticket en este punto se entenderá que este negocio fue perdido. Para que este ticket sea efectivo se debe generar el pedido a partir de la cotización asociada, y terminar el proceso en una factura de venta.", function () {
                    //callback
                });
            }
        } else {
            $fields.forEach($field => {
                if ($field.attr('id') !== 'fechaPC' && $field.attr('id') !== 'horaPC') {
                    $field.val('');
                }
                $field.prop('required', true);
            });
            $fieldsetContainer.show();
        }
    };

    // Aplica la lógica al cargar la página (para el estado inicial)
    applyToggleLogic();

    // Adjunta el evento 'change'
    $checkboxControl.on('change', applyToggleLogic);
});