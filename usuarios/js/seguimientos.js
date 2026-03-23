$(document).ready(function() {

	// ID del contenedor (fieldset) que quieres ocultar/mostrar
    const fieldsetContainerId = 'campos_controlados';
    const $fieldsetContainer = $('#' + fieldsetContainerId);

    const $form = $('#formAgregarSeguimiento');
    const $alert = $('#seguimientoFormAlert');
    const $checkboxControl = $('#miCheckboxControl'); // Selecciona el checkbox
    const fieldIdsToControl = ['fechaPC', 'horaPC', 'minutosRecordarAntes', 'canalPC', 'asunto']; // Los IDs de tus campos
    const $encargado = $('#encargado');
    const $btnGuardar = $('#btnGuardarSeguimiento');
    const btnHtmlOriginal = $btnGuardar.html();
    let initialized = false;
    let submitting = false;

    const tipoTicket = document.getElementById("tik_tipo_tiket").value;
    const tipoNegocio = document.getElementById("tik_tipo_negocio").value;

    const clearValidationState = function() {
        $alert.hide().empty();
        $form.find('.control-group').removeClass('error');
    };

    const markFieldError = function($field) {
        if ($field && $field.length) {
            $field.closest('.control-group').addClass('error');
        }
    };

    const isChosenFieldVisible = function($field) {
        if (!$field.hasClass('chzn-select')) {
            return $field.is(':visible');
        }
        const fieldId = $field.attr('id');
        if (!fieldId) {
            return $field.is(':visible');
        }
        const $chosenContainer = $('#' + fieldId + '_chzn');
        if (!$chosenContainer.length) {
            return $field.is(':visible');
        }
        return $chosenContainer.is(':visible');
    };

    const isSelectSingleEmpty = function($select) {
        const el = $select[0];
        if (!el || !el.options || el.selectedIndex < 0) {
            return true;
        }
        const opt = el.options[el.selectedIndex];
        if (!opt) {
            return true;
        }
        const v = opt.value;
        return v === null || v === undefined || String(v).trim() === '';
    };

    const isFieldEmpty = function($field) {
        if ($field.is('select')) {
            if ($field.is('select[multiple]')) {
                const vals = $field.val();
                return !vals || vals.length === 0;
            }
            return isSelectSingleEmpty($field);
        }
        const v = $field.val();
        return v === null || v === undefined || String(v).trim() === '';
    };

    const getValidationErrors = function() {
        const errors = [];
        const camposBase = [
            { id: 'contacto', label: 'Contacto' },
            { id: 'formaContacto', label: 'Cómo fue el contacto' },
            { id: 'canal', label: 'Canal de contacto' },
            { id: 'observaciones', label: 'Observaciones/Descripción' }
        ];

        const requiereProximoContacto = !$checkboxControl.is(':checked');
        const camposProximoContacto = [
            { id: 'fechaPC', label: 'Fecha próximo contacto' },
            { id: 'horaPC', label: 'Hora próximo contacto' },
            { id: 'minutosRecordarAntes', label: 'Recordatorio (minutos antes)' },
            { id: 'canalPC', label: 'Medio de contacto' },
            { id: 'asunto', label: 'Asunto a tratar' },
            { id: 'encargado', label: 'Encargado del próximo contacto' }
        ];

        const camposAValidar = requiereProximoContacto ? camposBase.concat(camposProximoContacto) : camposBase;

        camposAValidar.forEach(function(campo) {
            const $field = $form.find('#' + campo.id);
            if (!$field.length) {
                return;
            }

            if (!isChosenFieldVisible($field)) {
                return;
            }

            if (isFieldEmpty($field)) {
                errors.push(campo.label);
                markFieldError($field);
            }
        });

        return errors;
    };

    const showValidationErrors = function(errors) {
        const html = '<strong>Por favor complete los siguientes campos obligatorios:</strong><br>' + errors.join('<br>');
        $alert.html(html).show();
        $('html, body').animate({ scrollTop: $form.offset().top - 20 }, 300);
    };

    const setSubmitState = function(isLoading) {
        if (!$btnGuardar.length) return;

        if (isLoading) {
            $btnGuardar.prop('disabled', true)
                .removeClass('btn-info')
                .addClass('btn-warning')
                .html('<i class="icon-spinner icon-spin"></i> <span class="btn-text">Guardando...</span>');
        } else {
            $btnGuardar.prop('disabled', false)
                .removeClass('btn-warning')
                .addClass('btn-info')
                .html(btnHtmlOriginal);
        }
    };

    const syncEncargadoRequired = function() {
        const requiereProximoContacto = !$checkboxControl.is(':checked');
        $encargado.prop('required', requiereProximoContacto);
    };

    // Define la lógica de toggle (puede ser anónima o una función nombrada)
    const applyToggleLogic = function() {
        const isChecked = $checkboxControl.is(':checked'); // Usa .is(':checked') de jQuery

        const $fields = fieldIdsToControl.map(id => $('#' + id)); // Seleccionar los campos cada vez

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
                // Solo limpiar al volver desde "cerrar ticket"; no al cargar por primera vez.
                if (initialized && $field.attr('id') !== 'fechaPC' && $field.attr('id') !== 'horaPC') {
                    $field.val('');
                }
                $field.prop('required', true);

                if ($field.is('select')) {
                    $field.trigger('liszt:updated');
                }
            });
            $fieldsetContainer.show();
        }

        syncEncargadoRequired();
        initialized = true;
    };

    // Aplica la lógica al cargar la página (para el estado inicial)
    applyToggleLogic();

    // Adjunta el evento 'change'
    $checkboxControl.on('change', applyToggleLogic);

    // Limpia errores al interactuar con el formulario.
    $form.on('change input', 'input, select, textarea', function() {
        $(this).closest('.control-group').removeClass('error');
        if ($alert.is(':visible')) {
            $alert.hide().empty();
        }
    });

    // Validación final y bloqueo de doble envío.
    $form.on('submit', function(e) {
        if (submitting) {
            e.preventDefault();
            return;
        }

        clearValidationState();
        const errors = getValidationErrors();

        if (errors.length > 0) {
            e.preventDefault();
            showValidationErrors(errors);
            setSubmitState(false);
            return;
        }

        submitting = true;
        setSubmitState(true);
    });
});