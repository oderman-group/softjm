export class clsGenerales {

    constructor() {}    

    /**
     * tipo: success - info - warning - error
     */
    mtdMostrarMensaje(mensaje, tipo = "success", time = 5000) {

        let direction = "down-push";
        let position = "top center";

        DevExpress.ui.notify({
            message: mensaje,
            width: 400,
            type: tipo,
            displayTime: time,
        }, {
            position,
            direction
        });
    }

    mtdMostrarMensajeAlerta(mensaje, tipo = "success", time = 3000) {

        const msgAlerta = document.getElementById("msgAlerta");
        const msgAlertaTextoEncabezado = document.getElementById("msgAlertaTextoEncabezado");
        const msgAlertaTexto = document.getElementById("msgAlertaTexto");

       if (tipo === "success") {

            msgAlerta.classList.remove("hidden");
            msgAlerta.style.display = "block";
            msgAlerta.classList.add("alert-success");

            msgAlertaTextoEncabezado.innerText = "Éxito!";
            msgAlertaTexto.innerText = mensaje;

            setTimeout(() => {
                msgAlerta.style.display = "none";
            }, time);
       }

       if (tipo === "error") {

            msgAlerta.classList.remove("hidden");
            msgAlerta.style.display = "block";
            msgAlerta.classList.add("alert-danger");

            msgAlertaTextoEncabezado.innerText = "Error!";
            msgAlertaTexto.innerText = mensaje;

            setTimeout(() => {
                msgAlerta.style.display = "none";
            }, time);
       }

       if (tipo === "warning") {

            msgAlerta.classList.remove("hidden");
            msgAlerta.style.display = "block";
            msgAlerta.classList.add("alert-warning");

            msgAlertaTextoEncabezado.innerText = "Advertencia!";
            msgAlertaTexto.innerText = mensaje;

            setTimeout(() => {
                msgAlerta.style.display = "none";
            }, time);
       }
    }

    fnValidarFromatoCorreo(correo) {
        var validRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (correo.match(validRegex)) {return true;}         
        return false;
    }

    mtdActivarLoad(boton,texto = "cargando... ") {
       boton.innerHTML ='<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + texto ;
       boton.disabled = true;
    }

    mtdDesactivarLoad(boton,texto = "Cargar") {
        boton.innerHTML = texto;
        boton.disabled = false;
    }

    toFristUpperCase(frase){

        let splitFrase = frase.toLowerCase().split(" ");

        let upperSlitFrase = splitFrase.map(palabra => {return palabra[0].toUpperCase() + palabra.slice(1);})

        return upperSlitFrase.join(" ");
    }

    fnObternerParametro(parametro) {
        parametro = parametro.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + parametro + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }
    
    
    fnNumberSetValue(componet, value) {
        componet.option('value', value * 1);
    }
    fnNumberGetValue(componet) {
        return componet.option('value');
    }
    fnSelectGetDescription(componet, value) {
        return componet.getDataSource().items().find(data => data.id == value);
    }
    fnComponetInstanceGetValue(componet) {
        return componet.option('value');
    }
    fnComponetInstanceSetValue(componet, value) {
        componet.option('value', value);
    }
    fnComponetInstanceVisible(componet, value) {
        componet.option('visible', value);
    }
    fnComponetInstanceGetTotalSummaryValue(componet, name) {
        return componet.getTotalSummaryValue(name);
    }
    fnComponetInstanceGetText(componet) {
        return componet.option('text');
    }
    fnComponetInstanceDisabled(componet, disabled = false) {
        componet.option('disabled', disabled);
    }
    fnComponetInstanceReadOnly(componet, disabled = false) {
        componet.option('readOnly', disabled);
    }
    fnComponetInstanceData(componet, data) {
        componet.option({ dataSource: data });
    }
    fnNumberDisabled(componet, disabled = false) {
        componet.option('disabled', disabled);
    }

    fnFechaFormatear(fecha) {
        var d = new Date(fecha);

        var dia = d.getDate();
        var mes = (d.getMonth() + 1);
        var anno = d.getFullYear();

        if (mes < 10) {
            mes = "0" + mes;
        }
        if (dia < 10) {
            dia = "0" + dia;
        }

        return anno + "-" + mes + "-" + dia;
    }

    
    fnPadStart( number, width = 4){

        width -= number.toString().length;
        if ( width > 0 )
        {
            return new Array( width + (/\./.test( number ) ? 2 : 1) ).join( '0' ) + number;
        }
        return number + ""; 
    }

    fnAbsolutePath(pagina) {
        var loc = window.location;
        var pathName = loc.pathname;
        pathName = loc.pathname.substring(loc.pathname.lastIndexOf('/') + 1, pathName.length);
        return pagina === pathName ? true : false;
    }

    mtdOpionesGrid(grdDatosInstancia, cargar = true, adaptarColumnas = true) {

        grdDatosInstancia.option({
            //filtro en cada columna
            headerFilter: {
                visible: true
            },
            filterRow: {
                visible: true,
            },
            //Se activa al inicio cuando estan cargando los datos  
            loadPanel: {
                enabled: cargar,
                text: "Cargando..."
            },
            //Oculta columnas dependiendo el tamaño
            columnHidingEnabled: adaptarColumnas,
            allowColumnReordering: true,
            allowColumnResizing: true,
            columnResizingMode: "widget",
            columnAutoWidth: true,
            hoverStateEnabled: true,
            //Dibuja la grid en la pagina
            showBorders: true,
            showColumnLines: true,
            showRowLines: true,
            noDataText: "No hay datos para mostrar",
            //Permite agrupar columnas
            grouping: {
                contextMenuEnabled: true,
                allowCollapsing: true,
                autoExpandAll: false,
                expandMode: "rowClick",
                texts: {
                    groupByThisColumn: "Agrupar por esta columna",
                    groupContinuedMessage: "Continuada desde la pagina anterior",
                    groupContinuesMessage: "Continua en la siguiente pagina",
                    ungroup: "Desagrupar",
                    ungroupAll: "Desagrupar todo"
                }
            },
            //Activa el panel de busqueda
            searchPanel: {
                placeholder: "Buscar...",
                visible: true,
                highlightSearchText: true
            },
            //Activa el panel de agrupacion de colomna
            groupPanel: {
                emptyPanelText: "Arrastre una columna para agrupar",
                visible: true
            },
            //Activa las opciones del paginador
            pager: {
                allowedPageSizes: [10, 30, 50, 100, 'all'],
                showNavigationButtons: true,
                showPageSizeSelector: true,
                visible: true,
                displayMode: "adaptive"
            },
            //Define el numero de registros por paginas
            paging: {
                pageSize: 10
            },
            //Permite seleccionar las columnas que aparecen en la Grid
            columnChooser: {
                emptyPanelText: "Arrastre una columna para ocultarla",
                enabled: true,
                title: "Columnas"
            },
            sorting: {
                ascendingText: "Ordenar Ascendente",
                clearText: "Quitar orden",
                descendingText: "Ordenar descendente",
                mode: "single",
                showSortIndexes: true
            },
            //Tipo de seleccion en la Grid
            selection: {
                mode: "single"
            }
        });
    }

    mtdOpionesGridSeguimiento(grdDatosInstancia, cargar = true, adaptarColumnas = true) {

        grdDatosInstancia.option({
            //filtro en cada columna
            headerFilter: {
                visible: true
            },
            filterRow: {
                visible: true,
            },
            //Se activa al inicio cuando estan cargando los datos  
            loadPanel: {
                enabled: cargar,
                text: "Cargando..."
            },
            //Oculta columnas dependiendo el tamaño
            columnHidingEnabled: adaptarColumnas,
            allowColumnReordering: true,
            allowColumnResizing: true,
            columnResizingMode: "widget",
            columnAutoWidth: true,
            hoverStateEnabled: true,
            //Dibuja la grid en la pagina
            showBorders: true,
            showColumnLines: true,
            showRowLines: true,
            noDataText: "No hay datos para mostrar",
            //Permite agrupar columnas
            grouping: {
                contextMenuEnabled: true,
                allowCollapsing: true,
                autoExpandAll: false,
                expandMode: "rowClick",
                texts: {
                    groupByThisColumn: "Agrupar por esta columna",
                    groupContinuedMessage: "Continuada desde la pagina anterior",
                    groupContinuesMessage: "Continua en la siguiente pagina",
                    ungroup: "Desagrupar",
                    ungroupAll: "Desagrupar todo"
                }
            },
            //Activa el panel de busqueda
            searchPanel: {
                placeholder: "Buscar...",
                visible: true,
                highlightSearchText: true
            },
            //Activa el panel de agrupacion de colomna
            groupPanel: {
                emptyPanelText: "Arrastre una columna para agrupar",
                visible: true
            },
            //Activa las opciones del paginador
            pager: {
                allowedPageSizes: [10, 30, 50, 100, 'all'],
                showNavigationButtons: true,
                showPageSizeSelector: true,
                visible: true,
                displayMode: "adaptive"
            },
            //Define el numero de registros por paginas
            paging: {
                pageSize: 10
            },
            //Permite seleccionar las columnas que aparecen en la Grid
            columnChooser: {
                emptyPanelText: "Arrastre una columna para ocultarla",
                enabled: true,
                title: "Columnas"
            },
            sorting: {
                ascendingText: "Ordenar Ascendente",
                clearText: "Quitar orden",
                descendingText: "Ordenar descendente",
                mode: "single",
                showSortIndexes: true
            },
            //Tipo de seleccion en la Grid
            selection: {
                mode: "multiple",
                selectAllMode: "allPages",
                showCheckBoxesMode: "always"
            },
            columnFixing: {
                enabled: true,
            }
        });
    }

    mtdOpionesGridSinPaginacion(grdDatosInstancia, adaptarColumnas = true) {

        grdDatosInstancia.option({
            //filtro en cada columna
            headerFilter: {
                visible: true
            },
            filterRow: {
                visible: true,
            },
            //Se activa al inicio cuando estan cargando los datos  
            loadPanel: {
                enabled: true,
                text: "Cargando..."
            },
            //Oculta columnas dependiendo el tamaño
            columnHidingEnabled: adaptarColumnas,
            allowColumnResizing: true,
            columnResizingMode: "widget",
            columnAutoWidth: true,
            hoverStateEnabled: true,
            //Dibuja la grid en la pagina
            showBorders: true,
            showColumnLines: true,
            showRowLines: true,
            noDataText: "No hay datos para mostrar",
            //Permite agrupar columnas
            grouping: {
                contextMenuEnabled: true,
                allowCollapsing: true,
                autoExpandAll: false,
                expandMode: "rowClick",
                texts: {
                    groupByThisColumn: "Agrupar por esta columna",
                    groupContinuedMessage: "Continuada desde la pagina anterior",
                    groupContinuesMessage: "Continua en la siguiente pagina",
                    ungroup: "Desagrupar",
                    ungroupAll: "Desagrupar todo"
                }
            },
            //Activa el panel de busqueda
            searchPanel: {
                placeholder: "Buscar...",
                visible: true,
                highlightSearchText: true
            },
            //Activa el panel de agrupacion de colomna
            groupPanel: {
                emptyPanelText: "Arrastre una columna para agrupar",
                visible: true
            },
            //Permite seleccionar las columnas que aparecen en la Grid
            columnChooser: {
                emptyPanelText: "Arrastre una columna para ocultarla",
                enabled: true,
                title: "Columnas"
            },
            sorting: {
                ascendingText: "Ordenar Ascendente",
                clearText: "Quitar orden",
                descendingText: "Ordenar descendente",
                mode: "single",
                showSortIndexes: true
            },
            //Tipo de seleccion en la Grid
            selection: {
                mode: "single"
            }
        });
    }
    
    mtdFormCrear(action, metodo = "POST", target_blank = false) {
        var form = $(document.createElement('form'));
        form.attr("action", action);
        form.attr("method", metodo);
        form.css("display", "none");

        if (target_blank) {
            form.attr("target", "_blank");
        }

        return form;
    }

    mtdFormCrearInput(form, nombre, valor) {
        var input = $("<input>")
            .attr("type", "text")
            .attr("name", nombre)
            .val(valor);
        form.append($(input));
    }

    mtdFormEjecutar(form) {
        form.appendTo(document.body);
        form.submit();
    }

    fnValidarPermiso(EstadoPermiso) {
        if (!EstadoPermiso) {
            clsGenerales_.mtdMostrarMensaje("No tienes permisos para utilizar esta opcion", "Advertencia!", "warning");
        }
        return EstadoPermiso;
    }

    fnFechaActual() {
        var d = new Date();

        var dia = d.getDate();
        var mes = (d.getMonth() + 1);
        var anno = d.getFullYear();

        if (mes < 10) {
            mes = "0" + mes;
        }
        if (dia < 10) {
            dia = "0" + dia;
        }

        return anno + "-" + mes + "-" + dia;
    }

    fnHoraActualMinutos() {
        var d = new Date();
        var hora = d.getHours();
        var minutos = d.getMinutes();
        return hora * 60 + minutos;
    }

    fnFechaHoraActual() {
        var d = new Date();

        var dia = d.getDate();
        var mes = (d.getMonth() + 1);
        var anno = d.getFullYear();

        var hora = d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();

        if (mes < 10) {
            mes = "0" + mes;
        }
        if (dia < 10) {
            dia = "0" + dia;
        }

        return anno + "-" + mes + "-" + dia + ' ' + hora;
    }
    fnFechaFormatear(fecha) {
        var d = new Date(fecha);

        var dia = d.getDate();
        var mes = (d.getMonth() + 1);
        var anno = d.getFullYear();

        if (mes < 10) {
            mes = "0" + mes;
        }
        if (dia < 10) {
            dia = "0" + dia;
        }

        return anno + "-" + mes + "-" + dia;
    }
    mtdGridExportar(grdDatosInstancia, nombreArchivo, nombreHoja = "") {

        var workbook = new ExcelJS.Workbook();
        var worksheet = workbook.addWorksheet(nombreHoja);

        DevExpress.excelExporter.exportDataGrid({
            component: grdDatosInstancia,
            worksheet: worksheet,
            autoFilterEnabled: true
        }).then(function() {
            workbook.xlsx.writeBuffer().then(function(buffer) {
                saveAs(new Blob([buffer], { type: 'application/octet-stream' }), nombreArchivo + '.xlsx');
            });
        });
    }

    mtdOpionesGridRol(grdDatosInstancia, adaptarColumnas = true) {

        grdDatosInstancia.option({

            //Se activa al inicio cuando estan cargando los datos  
            loadPanel: {
                text: "Cargando..."
            },
            keyExpr: "id",
            editing: {
                mode: "cell",
                allowUpdating: true
            },
            //Oculta columnas dependiendo el tamaño
            columnHidingEnabled: adaptarColumnas,
            //Dibuja la grid en la pagina
            showBorders: true,
            showColumnLines: true,
            showRowLines: true,
            columnAutoWidth: true,
            //Permite agrupar columnas
            grouping: {
                contextMenuEnabled: true,
                allowCollapsing: true,
                autoExpandAll: false,
                expandMode: "rowClick",
                texts: {
                    groupByThisColumn: "Agrupar por esta columna",
                    groupContinuedMessage: "Continuada desde la pagina anterior",
                    groupContinuesMessage: "Continua en la siguiente pagina",
                    ungroup: "Desagrupar",
                    ungroupAll: "Desagrupar todo"
                }
            },
            //Activa el panel de busqueda
            searchPanel: {
                placeholder: "Buscar...",
                visible: true,
                highlightSearchText: true
            },
            paging: {
                pageSize: 500
            },
            sorting: {
                ascendingText: "Ordenar Ascendente",
                clearText: "Quitar orden",
                descendingText: "Ordenar descendente",
                mode: "single",
                showSortIndexes: true
            },
            //Tipo de seleccion en la Grid
            selection: {
                allowSelectAll: false,
                mode: "single"
            }
        });
    }
    mtdOpionesGridRolAsignacion(grdDatosInstancia, adaptarColumnas = true) {

        grdDatosInstancia.option({

            //Se activa al inicio cuando estan cargando los datos  
            loadPanel: {
                text: "Cargando..."
            },
            keyExpr: "id",
            editing: {
                mode: "cell",
                allowUpdating: true
            },
            export: {
                allowExportSelectedData: true,
            },
            //Oculta columnas dependiendo el tamaño
            columnHidingEnabled: adaptarColumnas,
            //Dibuja la grid en la pagina
            showBorders: true,
            showColumnLines: true,
            showRowLines: true,
            columnAutoWidth: true,
            //Permite agrupar columnas
            grouping: {
                contextMenuEnabled: true,
                allowCollapsing: false,
                autoExpandAll: true,
                expandMode: "rowClick",
                texts: {
                    groupByThisColumn: "Agrupar por esta columna",
                    groupContinuedMessage: "Continuada desde la pagina anterior",
                    groupContinuesMessage: "Continua en la siguiente pagina",
                    ungroup: "Desagrupar",
                    ungroupAll: "Desagrupar todo"
                }
            },
            //Activa el panel de busqueda
            searchPanel: {
                placeholder: "Buscar...",
                visible: true,
                highlightSearchText: true
            },
            paging: {
                pageSize: 500
            },
            sorting: {
                ascendingText: "Ordenar Ascendente",
                clearText: "Quitar orden",
                descendingText: "Ordenar descendente",
                mode: "single",
                showSortIndexes: true
            },
            //Tipo de seleccion en la Grid
            selection: {
                mode: "multiple",
                selectAllMode: "allPages",
                showCheckBoxesMode: "always"
            }
        });
    }

    mtdPivotExportar(pvtDatosInstancia, nombreArchivo, nombreHoja = "") {

        var workbook = new ExcelJS.Workbook();
        var worksheet = workbook.addWorksheet(nombreHoja);

        DevExpress.excelExporter.exportPivotGrid({
            component: pvtDatosInstancia,
            worksheet: worksheet,
            autoFilterEnabled: true
        }).then(function() {
            workbook.xlsx.writeBuffer().then(function(buffer) {
                saveAs(new Blob([buffer], { type: 'application/octet-stream' }), nombreArchivo + '.xlsx');
            });
        });
    }

    fnNumberCurrency(componet, decimal = false) {

        if (decimal) {
            return $(componet).dxNumberBox({
                format: '$ #,##0.##',
                showSpinButtons: false,
                min: 0,
                valueChangeEvent: "keyup"
            }).dxNumberBox('instance');
        } else {
            return $(componet).dxNumberBox({
                format: '$ #,##0',
                showSpinButtons: false,
                min: 0,
                valueChangeEvent: "keyup"
            }).dxNumberBox('instance');
        }
    }

    fnNumberPercent(componet, decimal = false) {

        if (decimal) {
            return $(componet).dxNumberBox({
                format: '#0.## %',
                showSpinButtons: false,
                min: 0,
                max: 100,
                valueChangeEvent: "keyup"
            }).dxNumberBox('instance');
        } else {
            return $(componet).dxNumberBox({
                format: '#0 %',
                showSpinButtons: false,
                min: 0,
                max: 100,
                valueChangeEvent: "keyup"
            }).dxNumberBox('instance');
        }
    }
    
    fnNumber(componet, decimal = 0) {

        if (decimal == 4) {
            return $(componet).dxNumberBox({
                format: '#,##0.####',
                showSpinButtons: false,
                min: 0,
                valueChangeEvent: "keyup"
            }).dxNumberBox('instance');
        }
        if (decimal == 2) {
            return $(componet).dxNumberBox({
                format: '#,##0.##',
                showSpinButtons: false,
                min: 0,
                valueChangeEvent: "keyup"
            }).dxNumberBox('instance');
        }
        if (decimal == 0) {
            return $(componet).dxNumberBox({
                format: '#,##0',
                showSpinButtons: false,
                min: 0,
                valueChangeEvent: "keyup"
            }).dxNumberBox('instance');
        }
    }

    fnFormatoMoneda(number) {
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', minimumFractionDigits: 2 }).format(number);
    }

    fnFormatoMiles(number) {
        return new Intl.NumberFormat('en-US', { minimumFractionDigits: 0 }).format(number);
    }

    /**
     * Muestra un overlay de carga en la página.
     * 
     */
    mtdActivarLoadPagina() {
    document.getElementById("overlay").style.display = "flex";
    }

    /**
     * Oculta el overlay de carga en la página.
     * 
     */
    mtdDesactivarLoadPagina() {
        document.getElementById("overlay").style.display = "none";
    }

}