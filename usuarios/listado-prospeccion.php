<?php
// PHP para simular datos de 50 clientes
function generarClientesSimulados($cantidad = 50) {
    $clientes = [];
    $nombres = ['Juan', 'María', 'Pedro', 'Ana', 'Luis', 'Sofía', 'Carlos', 'Laura', 'Diego', 'Elena'];
    $apellidos = ['García', 'Rodríguez', 'Martínez', 'López', 'González', 'Pérez', 'Sánchez', 'Ramírez', 'Torres', 'Flores'];
    $dominios = ['ejemplo.com', 'mail.com', 'negocio.org', 'empresa.net'];
    $prefijosTelefono = ['300', '301', '302', '305', '310', '311', '312', '315', '320'];

    for ($i = 1; $i <= $cantidad; $i++) {
        $nombre = $nombres[array_rand($nombres)] . ' ' . $apellidos[array_rand($apellidos)];
        $telefono = $prefijosTelefono[array_rand($prefijosTelefono)] . rand(1000000, 9999999);
        $email = strtolower(str_replace(' ', '.', $nombre)) . '@' . $dominios[array_rand($dominios)];
        $email = str_replace(['á','é','í','ó','ú','ñ'], ['a','e','i','o', 'u','n'], $email);

        // Simular contactos adicionales para la vista detalle
        $contactosAdicionales = [];
        $numContactos = rand(0, 2); // 0 a 2 contactos adicionales por cliente
        for ($j = 0; $j < $numContactos; $j++) {
            $contactoNombre = $nombres[array_rand($nombres)] . ' ' . $apellidos[array_rand($apellidos)];
            $contactoCargo = ['Gerente', 'Asistente', 'Secretario', 'Administrador'][array_rand(['Gerente', 'Asistente', 'Secretario', 'Administrador'])];
            $contactoTelefono = $prefijosTelefono[array_rand($prefijosTelefono)] . rand(1000000, 9999999);
            $contactosAdicionales[] = [
                'nombre' => $contactoNombre,
                'cargo' => $contactoCargo,
                'telefono' => $contactoTelefono
            ];
        }

        $clientes[] = [
            'id' => $i,
            'nombre_cliente' => $nombre,
            'telefono' => $telefono,
            'email' => $email,
            'notas_previas' => 'Posible interés en producto Y. ID: '.$i,
            'gestion_estado' => 'none', // 'none', 'valido', 'no_valido'
            'contactos' => $contactosAdicionales // Para la vista maestro-detalle
        ];
    }
    return $clientes;
}

$clientesData = generarClientesSimulados(50);
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

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #3f51b5;
            text-align: center;
            margin-bottom: 30px;
        }
        #gridContainer {
            width: 95%;
            margin: 0 auto;
            max-width: 1200px;
        }
        /* Estilos generales para DevExtreme */
        .dx-datagrid-headers {
            background-color: #e0e0e0;
        }
        .dx-datagrid-header-panel, .dx-toolbar {
            background-color: #f0f0f0 !important;
        }
        /* Estilos para el botón Gestionar (combinando Bootstrap con DevExtreme) */
        /* Aseguramos que el texto del botón custom sea blanco */
        .dx-button.dx-datagrid-text-content {
            color: white !important;
        }
        .dx-button.dx-datagrid-text-content .dx-button-text {
            color: white !important;
        }

        /* Estilos del Popup */
        .popup-form-content {
            padding: 15px;
        }
        .dx-popup-content .dx-field-item {
            padding-bottom: 10px;
        }
        .dx-popup-content label {
            font-weight: bold;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }
        .dx-popup-content textarea,
        .dx-popup-content select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .dx-radiobutton-wrapper {
            margin-right: 15px;
            display: inline-block;
        }
        /* Estilos para los botones de Guardar/Cancelar en el popup (sobreescribiendo Bootstrap si es necesario) */
        .dx-toolbar-bottom .dx-toolbar-item:last-child .dx-button {
            background-color: #f44336; /* Color rojo para Cancelar */
        }
        .dx-toolbar-bottom .dx-toolbar-item:nth-last-child(2) .dx-button {
            background-color: #4CAF50; /* Color verde para Guardar */
        }

        /* --- Estilos para la coloración de filas --- */
        .managed-invalid {
            background-color: #ffebee !important; /* Rojo muy claro */
        }
        .managed-invalid:hover {
             background-color: #ffcdd2 !important; /* Un poco más oscuro al pasar el mouse */
        }
        .managed-valid {
            background-color: #e8f5e9 !important; /* Verde muy claro */
        }
        .managed-valid:hover {
            background-color: #c8e6c9 !important; /* Un poco más oscuro al pasar el mouse */
        }
        /* Asegurarse de que el color de la fila no afecte el texto */
        .dx-row.managed-invalid .dx-datagrid-text-content,
        .dx-row.managed-valid .dx-datagrid-text-content {
            color: #333; /* O un color que contraste bien */
        }

        /* Estilos para la vista maestro-detalle */
        .master-detail-container {
            padding: 15px;
            border-top: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .master-detail-container h5 {
            margin-top: 0;
            color: #555;
        }
        .contact-list {
            list-style: none;
            padding: 0;
        }
        .contact-list li {
            margin-bottom: 5px;
            border-bottom: 1px dashed #eee;
            padding-bottom: 5px;
        }
        .contact-list li:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .contact-list span {
            font-weight: normal;
        }
    </style>
</head>
<body>

    <h1>Listado de Clientes para Prospección</h1>

    <div id="gridContainer"></div>

    <div id="popupContainer"></div>

    <script>
        const clientesData = <?php echo json_encode($clientesData, JSON_UNESCAPED_UNICODE); ?>;

        $(function() {
            const agentesComerciales = [
                { id: 1, nombre: 'Jaime Mendoza' },
                { id: 2, nombre: 'Joan Mendoza' },
                { id: 3, nombre: 'Maria Fernanda' },
            ];

            let currentClienteData = null; // Para almacenar los datos del cliente actual gestionado
            let gridInstance = null; // Para almacenar la instancia del DataGrid

            // 1. Inicializar el DataGrid de DevExtreme
            gridInstance = $("#gridContainer").dxDataGrid({ // Asignar la instancia a la variable
                dataSource: clientesData,
                keyExpr: "id",

                showBorders: true,
                showRowLines: true,
                rowAlternationEnabled: true,
                hoverStateEnabled: true,

                scrolling: {
                    mode: "virtual",
                    rowRenderingMode: "virtual"
                },
                height: 600,
                headerFilter: { visible: true },
                filterRow: { visible: true, applyFilter: "auto" },
                searchPanel: { visible: true, width: 240, placeholder: "Buscar..." },
                columnAutoWidth: true,
                allowColumnResizing: true,
                columnResizingMode: "widget",

                columns: [
                    { dataField: "id", caption: "ID", width: 70, alignment: "center", allowFiltering: false },
                    { dataField: "nombre_cliente", caption: "Nombre del Cliente", allowFiltering: true },
                    { dataField: "telefono", caption: "Teléfono", allowFiltering: false },
                    { dataField: "email", caption: "Email", allowFiltering: true },
                    {
                        caption: "Gestionar",
                        type: "buttons",
                        buttons: [{
                            text: "Gestionar",
                            // Aplica clases de Bootstrap, y DevExtreme las fusiona
                            cssClass: "btn btn-sm btn-success dx-button", // btn-sm para un botón más pequeño
                            onClick: function(e) {
                                currentClienteData = e.row.data; // Almacenar los datos del cliente
                                const popupInstance = $("#popupContainer").dxPopup("instance");
                                console.log("Intento de mostrar Popup. Instancia:", popupInstance); // Depuración
                                if (popupInstance) {
                                    popupInstance.show();
                                } else {
                                    console.error("No se pudo obtener la instancia del dxPopup.");
                                    DevExpress.ui.notify("Error: No se pudo iniciar el formulario de gestión.", "error", 3000);
                                }
                            }
                        }]
                    }
                ],
                pager: {
                    showPageSizeSelector: true,
                    allowedPageSizes: [10, 20, 50, 100],
                    showInfo: true,
                    infoText: "Página {0} de {1} ({2} elementos)"
                },
                paging: {
                    pageSize: 15
                },

                // --- Implementación de Master-Detail View ---
                masterDetail: {
                    enabled: true,
                    template: function(container, options) {
                        const cliente = options.data; // Datos de la fila principal
                        const contactos = cliente.contactos;

                        const detailContent = $('<div>').addClass('master-detail-container');
                        detailContent.append('<h5>Contactos Adicionales:</h5>');

                        if (contactos && contactos.length > 0) {
                            const ul = $('<ul>').addClass('contact-list');
                            contactos.forEach(contacto => {
                                ul.append(`<li>
                                    <strong>${contacto.nombre}</strong> (${contacto.cargo})<br>
                                    <span>Teléfono: ${contacto.telefono}</span>
                                </li>`);
                            });
                            detailContent.append(ul);
                        } else {
                            detailContent.append('<p>No hay contactos adicionales registrados.</p>');
                        }

                        detailContent.append(`<p><strong>Notas Previas:</strong> ${cliente.notas_previas || 'N/A'}</p>`);

                        container.append(detailContent);
                    }
                },

                // --- Implementación de Coloración de Filas ---
                onRowPrepared: function(e) {
                    if (e.rowType === "data") { // Asegurarse de que sea una fila de datos
                        // Remover clases previas para evitar conflictos si el estado cambia
                        $(e.rowElement).removeClass('managed-valid managed-invalid');
                        
                        if (e.data.gestion_estado === 'valido') {
                            $(e.rowElement).addClass('managed-valid');
                        } else if (e.data.gestion_estado === 'no_valido') {
                            $(e.rowElement).addClass('managed-invalid');
                        }
                    }
                }
            });

            // 2. Inicializar el Pop-up (modal) de DevExtreme
            $("#popupContainer").dxPopup({
                width: 600,
                height: "auto",
                showTitle: true,
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
                        elementAttr: { class: 'btn btn-success' }, // Clases Bootstrap para el botón
                        onClick: function() {
                            const clasificacionRadioGroup = $('#clasificacionGroup').dxRadioGroup('instance');
                            const clasificacion = clasificacionRadioGroup.option('value');
                            const agente = $('#selectAgenteComercial').val();
                            const notas = $('#notasProspecto').val();

                            if (!clasificacion) {
                                DevExpress.ui.notify("Por favor, selecciona una clasificación.", "error", 2000);
                                return;
                            }
                            if (clasificacion === 'valido' && !agente) {
                                DevExpress.ui.notify("Por favor, asigna un agente comercial para prospectos válidos.", "error", 2000);
                                return;
                            }

                            // 1. Actualizar el estado de gestión en los datos del cliente (currentClienteData)
                            if (currentClienteData) {
                                currentClienteData.gestion_estado = clasificacion;
                                // console.log("Datos del cliente actualizados:", currentClienteData); // Depuración
                            }

                            // Aquí iría tu llamada AJAX a PHP para guardar en la DB
                            // Incluye currentClienteData.id, clasificacion, agente, notas

                            DevExpress.ui.notify("¡Gestión guardada con éxito!", "success", 2000);
                            $("#popupContainer").dxPopup("instance").hide(); // Ocultar el modal inmediatamente
                        }
                    }
                }, {
                    toolbar: 'bottom',
                    location: 'after',
                    widget: 'dxButton',
                    options: {
                        text: 'Cancelar',
                        elementAttr: { class: 'btn btn-danger' }, // Clases Bootstrap para el botón
                        onClick: function() {
                            $("#popupContainer").dxPopup("instance").hide(); // Ocultar el modal inmediatamente
                        }
                    }
                }],
                // contentTemplate se encarga de CONSTRUIR el HTML del formulario UNA VEZ.
                contentTemplate: function(contentElement) {
                    const content = $('<div>').addClass('popup-form-content');
                    content.append('<p><b>Cliente: </b> <span id="popupClientName"></span> (ID: <span id="popupClientId"></span>)</p>');
                    content.append('<p><b>Notas Previas: </b> <span id="popupClientNotasPrevias"></span></p>');

                    content.append('<div class="mb-3"><label class="form-label">Clasificar Prospecto:</label>' +
                                   '<div id="clasificacionGroup"></div></div>');

                    content.append('<div class="mb-3"><label for="selectAgenteComercial" class="form-label">Asignar a Agente Comercial:</label>' +
                                   '<select id="selectAgenteComercial" class="form-select"></select></div>'); // Clase form-select de Bootstrap

                    content.append('<div class="mb-3"><label for="notasProspecto" class="form-label">Notas de Gestión:</label>' +
                                   '<textarea id="notasProspecto" rows="5" class="form-control" placeholder="Añade tus notas aquí..."></textarea></div>'); // Clase form-control de Bootstrap

                    // Inicializar dxRadioGroup
                    content.find('#clasificacionGroup').dxRadioGroup({
                        items: [
                            { text: 'Válido para Siguiente Fase', value: 'valido' },
                            { text: 'No Válido (Descartar)', value: 'no_valido' }
                        ],
                        valueExpr: 'value',
                        displayExpr: 'text',
                        layout: 'horizontal',
                        onValueChanged: function(e) {
                            const $agenteSelect = $('#selectAgenteComercial');
                            if (e.value === 'valido') {
                                $agenteSelect.prop('disabled', false).prop('required', true);
                            } else {
                                $agenteSelect.prop('disabled', true).prop('required', false).val('');
                            }
                        }
                    });

                    // Poblar el Dropdown de Agentes Comerciales (HTML Select)
                    const $selectAgenteComercial = content.find('#selectAgenteComercial');
                    $selectAgenteComercial.append($('<option>', { value: '', text: 'Selecciona un agente' }));
                    agentesComerciales.forEach(agente => {
                        $selectAgenteComercial.append($('<option>', {
                            value: agente.id,
                            text: agente.nombre
                        }));
                    });
                    $selectAgenteComercial.prop('disabled', true);

                    return content;
                },
                // onShowing se encarga de POBLAR Y RESETEAR los valores de los elementos cada vez que se va a mostrar el popup
                onShowing: function(e) {
                    if (currentClienteData) {
                        // Poblar detalles del cliente en el popup
                        $('#popupClientId').text(currentClienteData.id);
                        $('#popupClientName').text(currentClienteData.nombre_cliente);
                        $('#popupClientNotasPrevias').text(currentClienteData.notas_previas || 'No hay notas previas.');

                        // Resetear los componentes del formulario
                        const radioGroupInstance = $('#clasificacionGroup').dxRadioGroup('instance');
                        if (radioGroupInstance) {
                            radioGroupInstance.option('value', null); // Limpiar la selección del radio
                        }
                        $('#selectAgenteComercial').val('').prop('disabled', true); // Resetear select de agente
                        $('#notasProspecto').val(''); // Limpiar textarea de notas
                    }
                },
                // onHidden se ejecuta DESPUÉS de que el popup se ha ocultado completamente
                onHidden: function(e) {
                    // Refrescar el DataGrid para que se aplique el nuevo color de fila
                    // Esto es necesario porque 'gestion_estado' se actualiza en currentClienteData
                    // y el grid necesita saber que sus datos han cambiado para re-renderizar la fila.
                    if (gridInstance) {
                        gridInstance.refresh(); // O gridInstance.getDataSource().reload();
                    }
                    currentClienteData = null; // Limpiar los datos después de que el popup se esconde
                }
            });
        });
    </script>

</body>
</html>