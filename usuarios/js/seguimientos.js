$(document).ready(function() {

	// ID del contenedor (fieldset) que quieres ocultar/mostrar
    const fieldsetContainerId = 'campos_controlados';
    const $fieldsetContainer = $('#' + fieldsetContainerId);

    const $checkboxControl = $('#miCheckboxControl'); // Selecciona el checkbox
    const fieldIdsToControl = ['fechaPC', 'horaPC', 'minutosRecordarAntes', 'canalPC', 'asunto', 'encargado']; // Los IDs de tus campos

    // Define la lógica de toggle (puede ser anónima o una función nombrada)
    const applyToggleLogic = function() {
        const isChecked = $checkboxControl.is(':checked'); // Usa .is(':checked') de jQuery

        $fields = fieldIdsToControl.map(id => $('#' + id)); // Seleccionar los campos cada vez

        if (isChecked) {
            $fields.forEach($field => {
                $field.prop('required', false);
            });
            $fieldsetContainer.hide();
        } else {
            $fields.forEach($field => {
                $field.prop('required', true);
                $field.val('');
            });
            $fieldsetContainer.show();
        }
    };

    // Aplica la lógica al cargar la página (para el estado inicial)
    applyToggleLogic();

    // Adjunta el evento 'change'
    $checkboxControl.on('change', applyToggleLogic);
});