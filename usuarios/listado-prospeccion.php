<?php
include("sesion.php");

// ================== Datos Clientes ==================
function consultarClientes() {
    global $conexionBdPrincipal, $idEmpresa, $referenciaLlegada;
    $clientes = [];
    
    $consultaProspectos = $conexionBdPrincipal->query("SELECT *
    FROM prospectos_importacion_detalles
    INNER JOIN prospectos_importacion ON pi_id=pid_id_archivo AND (pi_asesor_encargado = '".$_SESSION["id"]."' || pi_created_by = '".$_SESSION["id"]."')
    ");

    while ($prospectos = mysqli_fetch_assoc($consultaProspectos)) {
        $clientes[] = [
            'id'             => $prospectos['pid_id'],
            'nombre_cliente' => $prospectos['pid_nombres'],
            'telefono'       => $prospectos['pid_telefono'],
            'email'          => $prospectos['pid_email'],
            'gestion_estado' => $prospectos['pid_estado'],
            'notas_previas'  => $prospectos['pid_notas'],
            'fuente'         => $referenciaLlegada[$prospectos['pi_fuente']],
            'fuente_id'      => $prospectos['pi_fuente'],
            'ciudad'         => $prospectos['pi_ciudad_evento'],
        ];
    }
    return $clientes;
}

$clientesData = consultarClientes();

// ================== Datos Agentes ==================
function traerAgentes() {
    global $conexionBdPrincipal, $idEmpresa;
    $agentes = [];

    $consultaUsuarios = $conexionBdPrincipal->query("SELECT usr_id, usr_nombre, usr_email 
    FROM usuarios 
    WHERE usr_bloqueado!=1 
    AND usr_id_empresa='".$idEmpresa."' 
    ORDER BY usr_nombre
    ");

    while ($usuarios = mysqli_fetch_array($consultaUsuarios, MYSQLI_ASSOC)) {
        $agentes[] = [
            'id' => $usuarios['usr_id'],
            'nombre' => $usuarios['usr_nombre']
        ];
    }

    return $agentes;
}

$agentesData = traerAgentes();

// ================== Datos Agentes ==================
function traerFuentes() {
    global $referenciaLlegada;
    $fuentes = [];

    foreach ($referenciaLlegada as $key => $ref) {
        if($key == 0) continue;

        $fuentes[] = [
            'id'     => $key,
            'nombre' => $ref
        ];
    }

    return $fuentes;
}

$fuentesData = traerFuentes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Clientes - Prospección</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn3.devexpress.com/jslib/23.2.3/css/dx.material.blue.light.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn3.devexpress.com/jslib/23.2.3/js/dx.all.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="p-3" style="background:#f5f5f5;font-family:Roboto,sans-serif;">

    <h1 class="text-center text-primary mb-3">Listado de Clientes para Prospección</h1>

    <div id="gridContainer"></div>

    <!-- Popup para Gestionar Prospecto -->
    <div id="popupContainer"></div>

    <!-- Popup para Importar Nueva BD -->
    <div id="popupImportar"></div>

<script>
const clientesData       = <?php echo json_encode($clientesData, JSON_UNESCAPED_UNICODE); ?>;
const agentesComerciales = <?php echo json_encode($agentesData, JSON_UNESCAPED_UNICODE); ?>;
const fuentes            = <?php echo json_encode($fuentesData, JSON_UNESCAPED_UNICODE); ?>;

$(function() {
    let currentClienteData = null;
    let gridInstance = null;

    // ================== GRID PRINCIPAL ==================
    gridInstance = $("#gridContainer").dxDataGrid({
        dataSource: clientesData,
        keyExpr: "id",
        showBorders: true,
        showRowLines: true,
        rowAlternationEnabled: true,
        hoverStateEnabled: true,
        height: 600,
        headerFilter: { visible: true },
        filterRow: { visible: true, applyFilter: "auto" },
        searchPanel: { visible: true, width: 240, placeholder: "Buscar..." },
        columnAutoWidth: true,
        allowColumnResizing: true,
        columnResizingMode: "widget",

        // ---- Toolbar con botón Importar ----
        toolbar: {
            items: [{
                location: "before",
                widget: "dxButton",
                options: {
                    text: "📥 Importar nueva BD",
                    type: "default",
                    onClick: function() {
                        $("#popupImportar").dxPopup("instance").show();
                    }
                }
            }]
        },

        columns: [
            { dataField: "id", caption: "ID", width: 70, alignment: "center", allowFiltering: false },
            { dataField: "nombre_cliente", caption: "Nombre del Cliente" },
            { 
                dataField: "telefono",
                caption: "Teléfono",
                allowFiltering: false,
                cellTemplate: function(container, options) {
                    if (options.value) {
                        $("<a>")
                            .attr("href", "tel:" + options.value)
                            .text(options.value)
                            .appendTo(container);
                    } else {
                        container.text(""); // vacío si no hay teléfono
                    }
                }
            },
            { dataField: "email", caption: "Email" },
            { dataField: "fuente", caption: "Fuente", width: 150, alignment: "center"},
            { dataField: "ciudad", caption: "Ciudad", width: 150, alignment: "center"},
            { dataField: "gestion_estado", caption: "Estado", alignment: "center"},

            {
                caption: "Gestionar",
                type: "buttons",
                buttons: [{
                    text: "Gestionar",
                    cssClass: "btn btn-sm btn-success dx-button",
                    onClick: function(e) {
                        currentClienteData = e.row.data;
                        if (currentClienteData.gestion_estado != 'VALIDO') {
                            $("#popupContainer").dxPopup("instance").show();
                        } else {
                            DevExpress.ui.notify("El prospecto "+currentClienteData.nombre_cliente+" fue marcado como válido y ya fue asignado a un asesor.", "info", 3000);
                            return;
                        }
                    }
                }]
            }
        ],
        paging: { pageSize: 15 },
        pager: { showPageSizeSelector: true, allowedPageSizes: [10,20,50], showInfo: true },

        // Vista detalle
        masterDetail: {
            enabled: true,
            template: function(container, options) {
                const cliente = options.data;

                let notasHtml = "<li>No hay notas.</li>";
                if (cliente.notas_previas) {
                    // asumimos que las notas vienen separadas por saltos de línea
                    const notasArray = cliente.notas_previas.split("\n").filter(n => n.trim());
                    notasHtml = notasArray
                        .map(n => {
                            // ejemplo de nota en BD: "2025-09-02 14:20:35|Llamada realizada"
                            const partes = n.split("|");
                            if (partes.length === 2) {
                                const fecha = partes[0].trim();
                                const texto = partes[1].trim();
                                return `<li>[${fecha}] ${texto}.</li>`;
                            } else {
                                // si no está en el formato esperado, lo mostramos como está
                                return `<li>${n}</li>`;
                            }
                        })
                        .join("");
                }

                container.append(`
                    <div class='p-2 bg-light'>
                        <p><strong>Notas:</strong></p>
                        <ul style="padding-left:18px; margin:0">${notasHtml}</ul>
                    </div>
                `);
            }
        },

        // Colorear filas según estado
        onRowPrepared: function(e) {
            if (e.rowType === "data") {
                $(e.rowElement).removeClass('managed-valid managed-invalid managed-waiting');
                if (e.data.gestion_estado === 'VALIDO') $(e.rowElement).addClass('managed-valid');
                if (e.data.gestion_estado === 'NO_VALIDO') $(e.rowElement).addClass('managed-invalid');
                if (e.data.gestion_estado === 'ESPERA') $(e.rowElement).addClass('managed-waiting');
            }
        }
    }).dxDataGrid('instance');

    // ================== POPUP GESTIONAR ==================
    $("#popupContainer").dxPopup({
        width: 600,
        height: "auto",
        title: "Gestionar Prospecto",
        visible: false,
        dragEnabled: false,
        closeOnOutsideClick: true,
        toolbarItems: [{
            toolbar: 'bottom',
            location: 'after',
            widget: 'dxButton',
            options: {
                text: 'Guardar',
                type: 'success',
                onClick: function() {
                    const clasificacion = $('#clasificacionGroup').dxRadioGroup('instance').option('value');
                    const agente = $('#selectAgenteComercial').val();
                    const notas = $('#notasProspecto').val();

                    if (!clasificacion) {
                        DevExpress.ui.notify("Por favor selecciona una clasificación.", "error", 2000);
                        return;
                    }
                    if (clasificacion === 'VALIDO' && !agente) {
                        DevExpress.ui.notify("Debes asignar un agente.", "error", 2000);
                        return;
                    }

                    // Actualizar estado local
                    currentClienteData.gestion_estado = clasificacion;

                    // ====== FETCH hacia guardar_gestion.php ======
                    fetch("guardar_gestion.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({
                            id_cliente: currentClienteData.id,
                            clasificacion: clasificacion,
                            agente: agente,
                            notas: notas,
                            email: currentClienteData.email,
                            telefono: currentClienteData.telefono,
                            fuente: currentClienteData.fuente,
                            fuente_id: currentClienteData.fuente_id,
                            nombre_cliente: currentClienteData.nombre_cliente,
                            ciudad_evento: currentClienteData.ciudad,
                        })
                    })
                    .then(r => r.json())
                    .then(res => {
                        DevExpress.ui.notify(res.mensaje || "Gestión guardada.", "success", 2000);

                        // Refrescar el grid automáticamente
                        gridInstance.option({
                            dataSource: res.datos
                        });
                        gridInstance.refresh();

                        $("#popupContainer").dxPopup("instance").hide();
                    })
                    .catch(err => {
                        console.error(err);
                        DevExpress.ui.notify("Error al guardar.", "error", 2000);
                    });
                }
            }
        },{
            toolbar: 'bottom',
            location: 'after',
            widget: 'dxButton',
            options: { text: 'Cancelar', onClick: () => $("#popupContainer").dxPopup("instance").hide() }
        }],
        contentTemplate: function(contentElement) {
            const content = $('<div class="p-3">');
            content.append('<p><b>Cliente:</b> <span id="popupClientName"></span> (ID: <span id="popupClientId"></span>)</p>');
            content.append('<div id="popupNotasPreviasContainer" class="mb-3"></div>');

            content.append('<div class="mb-3"><label>Clasificar Prospecto:</label><div id="clasificacionGroup"></div></div>');
            content.append('<div class="mb-3"><label>Agente Comercial:</label><select id="selectAgenteComercial" class="form-select"></select></div>');
            content.append('<div class="mb-3"><label>Notas:</label><textarea id="notasProspecto" rows="4" class="form-control"></textarea></div>');

            content.find('#clasificacionGroup').dxRadioGroup({
                items: [
                    { text: 'Válido para Siguiente Fase', value: 'VALIDO' },
                    { text: 'No Válido (Descartar)', value: 'NO_VALIDO' },
                    { text: 'En Espera', value: 'ESPERA' }
                ],
                valueExpr: 'value',
                displayExpr: 'text',
                layout: 'horizontal',
                onValueChanged: e => {
                    $('#selectAgenteComercial').prop('disabled', e.value !== 'VALIDO');
                }
            });

            const $sel = content.find('#selectAgenteComercial');
            $sel.append(`<option value="">Selecciona un agente</option>`);
            agentesComerciales.forEach(a => $sel.append(`<option value="${a.id}">${a.nombre}</option>`));
            $sel.prop('disabled', true);

            return content;
        },
        onShowing: function() {
            $('#popupClientId').text(currentClienteData.id);
            $('#popupClientName').text(currentClienteData.nombre_cliente);

            // Mostrar notas como lista
            let notasHtml = "<li>No hay notas.</li>";
            if (currentClienteData.notas_previas) {
                // asumimos que las notas están separadas por saltos de línea o guardadas con un delimitador
                const notasArray = currentClienteData.notas_previas.split("\n").filter(n => n.trim());

                notasHtml = notasArray
                    .map(n => {
                        // ejemplo esperado: "2025-09-02 14:20:35|Llamada realizada"
                        const partes = n.split("|");
                        if (partes.length === 2) {
                            const fecha = partes[0].trim();
                            const texto = partes[1].trim();
                            return `<li>[${fecha}] ${texto}.</li>`;
                        } else {
                            return `<li>${n}</li>`;
                        }
                    })
                    .join("");
            }

            $('#popupNotasPreviasContainer').html(`
                <p><b>Notas previas:</b></p>
                <ul style="padding-left:18px; margin:0">${notasHtml}</ul>
            `);


            $('#selectAgenteComercial').val('').prop('disabled', true);
            $('#notasProspecto').val('');
            $('#clasificacionGroup').dxRadioGroup('instance').option('value', null);
        },
        onHidden: function() {
            gridInstance.refresh();
            currentClienteData = null;
        }
    });

    // ================== POPUP IMPORTAR ==================
    $("#popupImportar").dxPopup({
        width: 600,
        height: "auto",
        title: "Importar nueva base de datos",
        visible: false,
        dragEnabled: false,
        closeOnOutsideClick: true,
        contentTemplate: function(contentElement) {
            const content = $('<form id="formImportar" class="p-3" enctype="multipart/form-data">');
            
            content.append('<div class="mb-3"><label>Seleccionar archivo Excel:</label><input type="file" name="archivo" class="form-control" required></div>');
            content.append('<div class="mb-3"><a href="plantilla_prospectos.xlsx" target="_blank" class="btn btn-link">📄 Descargar plantilla de muestra</a></div>');

            //fuentes
            const $self = $('<select name="fuente" class="form-select" required></select>');
            $self.append(`<option value="">Seleccione una fuente</option>`);
            fuentes.forEach(a => $self.append(`<option value="${a.id}">${a.nombre}</option>`));
            content.append('<div class="mb-3"><label>Fuente:</label></div>').append($self);

            // Contenedor para el campo dinámico
            const $extraField = $('<div class="mb-3 mt-3" id="ciudad-container" style="display:none;">' +
                '<label>Ciudad:</label>' +
                '<input type="text" name="ciudad" class="form-control" placeholder="Digite la ciudad">' +
            '</div>');
            content.append($extraField);

            // Evento al seleccionar
            $self.on('change', function () {
                if ($(this).val() === "4") {
                    $("#ciudad-container").show();
                } else {
                    $("#ciudad-container").hide();
                    $("#ciudad-container input").val(""); // limpiar si cambia
                }
            });

            // Agentes
            const $sel = $('<select name="asesor" class="form-select" required></select>');
            $sel.append(`<option value="">Seleccione asesor</option>`);
            agentesComerciales.forEach(a => $sel.append(`<option value="${a.id}">${a.nombre}</option>`));
            content.append('<div class="mb-3 mt-4"><label>Asesor encargado:</label></div>').append($sel);

            content.append('<button type="submit" class="btn btn-primary mt-3">Importar datos</button>');
            
            // Submit
            content.on("submit", function(e){
                e.preventDefault();
                const formData = new FormData(this);

                fetch("procesar_importacion.php", {
                    method: "POST",
                    body: formData
                })
                .then(r => r.json())
                .then(res => {
                    DevExpress.ui.notify(res.mensaje || "Importación completada.", "success", 2000);

                    // Refrescar el grid automáticamente
                    gridInstance.option({
                        dataSource: res.datos
                    });
                    gridInstance.refresh();

                    $("#popupImportar").dxPopup("instance").hide();
                })
                .catch(err => {
                    console.error(err);
                    DevExpress.ui.notify("Error en importación.", "error", 2000);
                });
            });

            return content;
        }
    });

});
</script>

<style>
.managed-invalid { background-color: #ffebee !important; }
.managed-waiting { background-color: #a5acf8ff !important; }
.managed-valid { background-color: #e8f5e9 !important; }
</style>

</body>
</html>
