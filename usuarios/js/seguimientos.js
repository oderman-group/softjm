import { clsGenerales } from './_generales.js';
$(document).ready(function() {

    const clsGenerales_ = new clsGenerales();

	// ID del contenedor (fieldset) que quieres ocultar/mostrar
    const fieldsetContainerId = 'campos_controlados';
    const $fieldsetContainer = $('#' + fieldsetContainerId);

    const $checkboxControl = $('#miCheckboxControl'); // Selecciona el checkbox
    const fieldIdsToControl = ['fechaPC', 'horaPC', 'horaPCF', 'minutosRecordarAntes', 'canalPC', 'asunto', 'encargado']; // Los IDs de tus campos

    // Define la lógica de toggle (puede ser anónima o una función nombrada)
    const applyToggleLogic = function() {
        const isChecked = $checkboxControl.is(':checked'); // Usa .is(':checked') de jQuery

        let $fields = fieldIdsToControl.map(id => $('#' + id)); // Seleccionar los campos cada vez

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
    //applyToggleLogic();

    // Adjunta el evento 'change'
    $checkboxControl.on('change', applyToggleLogic);


    const fechaPC = document.getElementById("fechaPC");
    const horaPC = document.getElementById("horaPC");
    const horaPCF = document.getElementById("horaPCF");

    fechaPC.value = clsGenerales_.fnFechaActual();

    $("#frmSeguimiento").on("submit", function(e) {
        e.preventDefault(); // evita que el formulario se envíe					

        document.getElementById("overlay").style.display = "flex";
        // Si la validación pasa, enviar el formulario manualmente
        this.submit();
    });

    $("#horaPC").on("input", function() {
        let hora = $(this).val();
        if (!hora) return;

        let [h, m] = hora.split(":").map(Number);
        m += 10;
        if (m >= 60) {
            h += Math.floor(m / 60);
            m = m % 60;
        }
        if (h >= 24) h = h % 24;

        let horaFinal = 
            String(h).padStart(2, "0") + ":" + 
            String(m).padStart(2, "0");

        $("#horaPCF").val(horaFinal);
    });
    

    const btnGuardar = document.getElementById("btnGuardar");
    btnGuardar.addEventListener('click', btnGuardarClic);
    function btnGuardarClic(e) {
        e.preventDefault();
    
        if ($("#contacto").val() === "") {
            clsGenerales_.mtdMostrarMensajeAlerta('Seleccione un contacto.', 'error');
            return;
        }
        if ($("#formaContacto").val() === "") {
            clsGenerales_.mtdMostrarMensajeAlerta('Seleccione como fue el contacto.', 'error');
            return;
        }
        if ($("#canal").val() === "") {
            clsGenerales_.mtdMostrarMensajeAlerta('Seleccione un canal de contacto.', 'error');
            return;
        }
        if ($("#observaciones").val() === "") {
            clsGenerales_.mtdMostrarMensajeAlerta('Digite una observacion o descripcion.', 'error');
            return;
        }

        if (!$("#fechaPC").val() || $("#fechaPC").val() < clsGenerales_.fnFechaActual()) {
            clsGenerales_.mtdMostrarMensajeAlerta('Seleccione una fecha mayor o igual al dia de hoy para el proximo contacto.', 'error');
            $("#fechaPC").focus();
            return;
        }

        if (!$("#horaPC").val() ) {
            clsGenerales_.mtdMostrarMensajeAlerta('Seleccione una hora de inicio para el proximo contacto.', 'error');
            $("#horaPC").focus();
            return;
        }

        if (!$("#horaPCF").val() ) {
            clsGenerales_.mtdMostrarMensajeAlerta('Seleccione una hora de fin para el proximo contacto.', 'error');
            $("#horaPCF").focus();
            return;
        }

        if (!$("#minutosRecordarAntes").val() || $("#minutosRecordarAntes").val() <= 0) {
            clsGenerales_.mtdMostrarMensajeAlerta('Seleccione unos minutos para recordar el proximo contacto.', 'error');
            $("#minutosRecordarAntes").focus();
            return;
        }

        const [h1, m1] = $("#horaPC").val().split(':').map(Number);
        const [h2, m2] = $("#horaPCF").val().split(':').map(Number);
        const minutosInicio = h1 * 60 + m1;
        const minutosFin = h2 * 60 + m2;

        if (minutosFin <= minutosInicio) {
            clsGenerales_.mtdMostrarMensajeAlerta('La hora de fin debe ser posterior a la hora de inicio.', 'error');
            return; // Detiene el envío
        }

        if ($("#canalPC").val() === "") {
            clsGenerales_.mtdMostrarMensajeAlerta('Seleccione un medio de contacto para el proximo contacto.', 'error');
            return;
        }
        if (!$("#asunto").val()) {
            clsGenerales_.mtdMostrarMensajeAlerta('Digite el asunto a tratar para el proximo contacto.', 'error');
            return;
        }

        let valores = $("#encargado").val();
        if (!valores || valores.length === 0) {
            clsGenerales_.mtdMostrarMensajeAlerta('Seleccione un encargado para el proximo contacto.', 'error');
            return;
        }       

        $("#frmSeguimiento").trigger("submit");
    
    }


});