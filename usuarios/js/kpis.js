import { clsGenerales } from './_generales.js';
document.addEventListener('DOMContentLoaded', () => {

    DevExpress.localization.locale('es');
    const clsGenerales_ = new clsGenerales();

    const divEncabezadoPki = document.getElementById("divEncabezadoPki");
    const divDescripcionPki = document.getElementById("divDescripcionPki");
    const kpi1 = document.getElementById("kpi1");
    const kpi2 = document.getElementById("kpi2");
    const kpi3 = document.getElementById("kpi3");
    const kpi4 = document.getElementById("kpi4");
    const kpi5 = document.getElementById("kpi5");
    const kpi6 = document.getElementById("kpi6");
    const kpi7 = document.getElementById("kpi7");
    const kpi8 = document.getElementById("kpi8");
    const kpi9 = document.getElementById("kpi9");
    const kpi10 = document.getElementById("kpi10");
    const kpi11 = document.getElementById("kpi11");

    const grdDatos = $('#grdDatos').dxPivotGrid({}).dxPivotGrid('instance');
    const grdDatosChart = $('#grdDatosChart').dxChart({}).dxChart('instance');
    
    grdDatos.bindChart(grdDatosChart, { dataFieldsDisplayMode: 'splitPanes', alternateDataFields: false}); 

    kpi1.addEventListener('click', btnKpiClic);
    kpi2.addEventListener('click', btnKpiClic);
    kpi3.addEventListener('click', btnKpiClic);
    kpi4.addEventListener('click', btnKpiClic);
    kpi5.addEventListener('click', btnKpiClic);
    kpi6.addEventListener('click', btnKpiClic);
    kpi7.addEventListener('click', btnKpiClic);
    kpi8.addEventListener('click', btnKpiClic);
    kpi9.addEventListener('click', btnKpiClic);
    kpi10.addEventListener('click', btnKpiClic);
    kpi11.addEventListener('click', btnKpiClic);

    const modalDetalle = $("#modalDetalle").dxPopup({
        title: "Detalle del KPI",
        visible: false,
        showCloseButton: true,
        width: "80%",
        height: "80%",
        toolbarItems: [
            {
                widget: "dxButton",
                toolbar: "bottom",
                location: "center",
                options: {
                    text: "Cerrar",
                    stylingMode: "outlined",
                    type: 'danger',
                    onClick: function () {
                        modalDetalle.hide();
                    }
                }
            }
        ]
    }).dxPopup("instance");    


    function btnKpiClic(e) {
        e.preventDefault();

        clsGenerales_.mtdActivarLoadPagina();
        let txtOpcion = "consultar_kpi_ventas";
        let idbtn = this.id;

        if (idbtn == "kpi1") { txtOpcion = "consultar_kpi_1_2_ventas"; };
        if (idbtn == "kpi2") { txtOpcion = "consultar_kpi_1_2_ventas"; };
        if (idbtn == "kpi3") { txtOpcion = "consultar_kpi_3_tiempo_promedio_cierre_ventas"; };
        if (idbtn == "kpi4") { txtOpcion = "consultar_kpi_4_cumplimiento_cuota_comercial"; };
        if (idbtn == "kpi5") { txtOpcion = "consultar_kpi_5_tasa_conversión_prospecto_cliente"; };
        if (idbtn == "kpi6") { txtOpcion = "consultar_kpi_6_ejecucion_demostraciones"; };
        if (idbtn == "kpi7") { txtOpcion = "consultar_kpi_7_numero_llamadas_enviadas_ejecutivo_prospeccion"; };
        if (idbtn == "kpi8") { txtOpcion = "consultar_kpi_8_clientes_efectivos_por_evento"; };
        if (idbtn == "kpi9") { txtOpcion = "consultar_kpi_9_nuevos_subdistribuidores"; };
        if (idbtn == "kpi10") { txtOpcion = "consultar_kpi_10_captacion_clientes_instituciones"; };
        if (idbtn == "kpi11") { txtOpcion = "consultar_kpi_11_numero_visitas_realizadas"; };

        $.ajax({
            url: "ajax/ajax-kpis.php",
            type: "POST",
            crossDomain: true,
            dataType: 'json',
            data: {
                e_datos: JSON.stringify([{}]),
                opcion: txtOpcion
            },
            error: function() {
                clsGenerales_.mtdDesactivarLoadPagina();
                clsGenerales_.mtdMostrarMensaje("No se pudo completar la solicitud", "error");
            }
        }).done((respuesta) => {

            
            let datosKpi = [];
            grdDatos.option({dataSource: {store: datosKpi}});
            clsGenerales_.mtdDesactivarLoadPagina();
            divEncabezadoPki.innerText = this.innerText;
            divDescripcionPki.innerHTML = "";
            if (respuesta["estado"] === 'ok') {               
                
                datosKpi = respuesta["datos"]; // respuesta["datos"]; dataRespuestaKPI[0]["datos"];
            }
            if (respuesta["estado"] === 'ko') {
                clsGenerales_.mtdMostrarMensaje(respuesta["mensaje"], "error");
            }

            if (idbtn == "kpi1") {

                divDescripcionPki.innerHTML = "<b>Cantidad:</b> Número de facturas emitidas.";

                    grdDatosChart.option({
                        commonSeriesSettings: {
                            type: 'bar',
                            label: {
                                visible: true,
                                customizeText(e) {
                                    if (e.seriesName.includes("Cantidad")) {
                                        return new Intl.NumberFormat('es-CO').format(e.value);
                                    }
                                }
                            },
                        },
                        tooltip: {
                            enabled: true,
                            customizeTooltip(args) {                               
                                const valueText = args.originalValue;                        
                                return {html: `${args.seriesName}<div class='currency'>${valueText}</div>` };
                            },
                        },
                    });  
                    
                    grdDatos.option({
                        allowSortingBySummary: true,
                        allowFiltering: true,
                        allowSorting: true,
                        showBorders: true,
                        showColumnGrandTotals: true,
                        showRowGrandTotals: true,
                        showRowTotals: true,
                        showColumnTotals: false,
                        fieldPanel: {
                            showColumnFields: true,
                            showDataFields: true,
                            showFilterFields: true,
                            showRowFields: true,
                            allowFieldDragging: true,
                            visible: true,
                        },                        
                        fieldChooser: {
                            enabled: true,
                            allowSearch: true
                        },
                        headerFilter: {
                            search: {
                                enabled: true,
                            },
                            showRelevantValues: true,
                            width: 300,
                            height: 400,
                        },
                        export: {
                            enabled: true,
                        },
                        dataSource: {
                            fields: [{
                                dataField: 'id',
                                visible: false
                            },{
                                width: 150,
                                caption: 'Sucursal',
                                dataField: 'sucursal',
                                area: 'row',
                                sortOrder: 'asc'
                            },{
                                width: 150,
                                caption: 'Asesor',
                                dataField: 'asesor',
                                area: 'row',
                                sortOrder: 'asc'
                            },{
                                caption: 'Fecha',
                                dataField: 'fecha',
                                dataType: 'date',
                                area: 'column',
                                sortOrder: 'desc'
                            },{
                                summaryType: 'count',
                                caption: 'Cantidad',
                                area: 'data',
                                sortOrder: 'desc'
                            }],
                            store: datosKpi
                        }      
                    });
            }
            if (idbtn == "kpi2") {     

                divDescripcionPki.innerHTML = "<b>Cantidad:</b> Número de facturas emitidas. <b>Total:</b> Suma del valor total de las facturas. <b>Prom:</b> Promedio del valor total de las facturas.";

                grdDatosChart.option({   
                    commonSeriesSettings: {
                        type: 'bar',
                        label: {
                            visible: true,
                            customizeText(e) {
                                // Si la serie es de cantidad, mostrar como entero
                                if (e.seriesName.includes("Cantidad")) {
                                    return new Intl.NumberFormat('es-CO').format(e.value);
                                }
                                if (e.seriesName.includes("Total") || e.seriesName.includes("Prom")) {
                                    
                                    // Si es monetario, mostrar como moneda
                                    return new Intl.NumberFormat('es-CO', { 
                                        style: 'currency', 
                                        currency: 'COP', 
                                        minimumFractionDigits: 2
                                    }).format(e.value);
                                }
                            }
                        },
                    },                    
                    tooltip: {
                        enabled: true,
                        customizeTooltip(args) { 
                            let valueText = args.originalValue;  
                            if (!args.seriesName.includes("Cantidad")) {
                                valueText = new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP' }).format(args.originalValue); 
                            }                     
                            return {html: `${args.seriesName}<div class='currency'>${valueText}</div>` };
                        },
                    }
                });

                grdDatos.option({
                    allowSortingBySummary: true,
                    allowFiltering: true,
                    allowSorting: true,
                    showBorders: true,
                    showColumnGrandTotals: true,
                    showRowGrandTotals: true,
                    showRowTotals: true,
                    showColumnTotals: false,
                    fieldPanel: {
                        showColumnFields: true,
                        showDataFields: true,
                        showFilterFields: true,
                        showRowFields: true,
                        allowFieldDragging: true,
                        visible: true,
                    },
                    fieldChooser: {
                        enabled: true,
                        allowSearch: true
                    },
                    headerFilter: {
                        search: {
                            enabled: true,
                        },
                        showRelevantValues: true,
                        width: 300,
                        height: 400,
                    },
                    dataSource: {
                        fields: [{
                        dataField: 'id',
                        visible: false
                        },{
                        width: 150,
                        caption: 'Sucursal',
                        dataField: 'sucursal',
                        area: 'row',
                        sortOrder: 'asc'
                        },{
                        width: 150,
                        caption: 'Asesor',
                        dataField: 'asesor',
                        area: 'row',
                        sortOrder: 'asc'
                        },{
                        caption: 'Fecha',
                        dataField: 'fecha',
                        dataType: 'date',
                        area: 'column',
                        sortOrder: 'desc'
                        },{
                            summaryType: 'count',
                            caption: 'Cantidad',
                            area: 'data',
                            sortOrder: 'desc'
                        },{
                            caption: 'Total',
                            dataField: 'total',
                            dataType: 'number',
                            summaryType: 'sum',
                            format: {
                                formatter: function (value) {
                                    if (value == null) return "";
                                    return "$ " + value.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                }   
                            },
                            area: 'data'
                        }, {
                            caption: 'Prom',
                            dataField: 'total',
                            dataType: 'number',
                            format: {
                                formatter: function (value) {
                                    if (value == null) return "";
                                    return "$ " + value.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                }    
                            },
                            area: 'data',
                            summaryType: "custom",
                            calculateCustomSummary: function(options) {
                                switch(options.summaryProcess) {
                                    case "start":
                                        options.totalValue = 0;
                                        options.nValues = 0;
                                    break;
                                    case "calculate":
                                        if (!isNaN(options.value)) {
                                            options.nValues++;
                                            options.totalValue += options.value;
                                        }
                                    break;
                                    case "finalize":
                                        options.totalValue = options.nValues ? options.totalValue / options.nValues : 0;
                                    break;
                                }
                            }
                        }],
                        store: datosKpi
                    }
                });

            }
            if (idbtn == "kpi3") {

                divDescripcionPki.innerHTML = "<b>Cantidad:</b> Número de facturas emitidas. <b>Duracion:</b> Suma del número de días que tardó en cerrarse las ventas. <b>Prom:</b> Promedio del número de días que tardó en cerrarse una venta.";

                grdDatosChart.option({
                    commonSeriesSettings: {
                        type: 'bar',
                        label: {
                            visible: true,
                            customizeText(e) {
                                // Si la serie es de cantidad, mostrar como entero
                                if (e.seriesName.includes("Cantidad") || e.seriesName.includes("Duracion")) {
                                    return new Intl.NumberFormat('es-CO').format(e.value);
                                }
                                if (e.seriesName.includes("Prom")) {
                                    
                                    // Si es monetario, mostrar como moneda
                                    return new Intl.NumberFormat('es-CO', { 
                                        minimumFractionDigits: 2
                                    }).format(e.value);
                                }
                            }
                        },
                    },
                    tooltip: {
                        enabled: true,
                        customizeTooltip(args) {                               
                            let valueText = args.originalValue;
                            return {html: `${args.seriesName}<div class='currency'>${valueText}</div>` };
                        },
                    }
                });

                grdDatos.option({
                    allowSortingBySummary: true,
                    allowFiltering: true,
                    allowSorting: true,
                    showBorders: true,
                    showColumnGrandTotals: true,
                    showRowGrandTotals: true,
                    showRowTotals: true,
                    showColumnTotals: false,
                    fieldPanel: {
                        showColumnFields: true,
                        showDataFields: true,
                        showFilterFields: true,
                        showRowFields: true,
                        allowFieldDragging: true,
                        visible: true,
                    },                        
                    fieldChooser: {
                        enabled: true,
                        allowSearch: true
                    },
                    headerFilter: {
                        search: {
                            enabled: true,
                        },
                        showRelevantValues: true,
                        width: 300,
                        height: 400,
                    },
                    export: {
                        enabled: true,
                    },
                    dataSource: {
                        fields: [{
                        dataField: 'id',
                        visible: false
                        },{
                            width: 150,
                            caption: 'Sucursal',
                            dataField: 'sucursal',
                            area: 'row',
                            sortOrder: 'asc'
                        },{
                            width: 150,
                            caption: 'Asesor',
                            dataField: 'asesor',
                            area: 'row',
                            sortOrder: 'asc'
                        },{
                            caption: 'Fecha',
                            dataField: 'fecha',
                            dataType: 'date',
                            area: 'column',
                            sortOrder: 'desc'
                        },{
                            summaryType: 'count',
                            caption: 'Cantidad',
                            area: 'data',
                            sortOrder: 'desc'
                        },{
                            caption: 'Duracion',
                            dataField: 'dias',
                            dataType: 'number',
                            summaryType: 'sum',
                            area: 'data'
                        },{
                            caption: 'Prom',
                            dataField: 'dias',
                            dataType: 'number',
                            area: 'data',
                            summaryType: "custom",
                            calculateCustomSummary: function(options) {
                                switch(options.summaryProcess) {
                                    case "start":
                                        options.totalValue = 0;
                                        options.nValues = 0;
                                    break;
                                    case "calculate":
                                        if (!isNaN(options.value)) {
                                            options.nValues++;
                                            options.totalValue += options.value;
                                        }
                                    break;
                                    case "finalize":
                                        options.totalValue = options.nValues ? Number(options.totalValue / options.nValues).toFixed(2) : 0;
                                    break;
                                }
                            }
                        }],
                        store: datosKpi
                    },onCellPrepared: function(e) {
                        if (e.area === "row" && e.cellElement && e.cell.text) {
                            const valor = e.cell.text;
                            if (e.cell.path?.length == 4) {
                                e.cellElement.empty();
                                $("<a>")
                                    .attr("href", `facturas.php?busqueda=${valor}`)
                                    .attr("target", "_blank")
                                    .text(valor)
                                    .appendTo(e.cellElement);
                            }else {
                                e.cellElement;
                            }
                        }
                    }
                });

            }
            if (idbtn == "kpi4") {    

                divDescripcionPki.innerHTML = "<b>Ejecutada:</b> Suma del valor total de ventas realizadas. <b>Planeada:</b> Valor total de ventas planificadas. <b>Tasa:</b> Porcentaje de cumplimiento de la meta.";

                grdDatosChart.option({
                    commonSeriesSettings: {
                        type: 'bar',
                        label: {
                            visible: true,
                            customizeText(e) {
                                if(e.seriesName.includes("Planeada") || e.seriesName.includes("Ejecutada")) {
                                    // Si es monetario, mostrar como moneda
                                    return new Intl.NumberFormat('es-CO', { 
                                        style: 'currency', 
                                        currency: 'COP', 
                                        minimumFractionDigits: 2
                                    }).format(e.value);
                                }
                            }
                        },
                    },
                    tooltip: {
                        enabled: true,
                        customizeTooltip(args) {                                  
                            let valueText = args.originalValue;  
                            if (args.seriesName.includes("Tasa")) {
                            valueText = new Intl.NumberFormat('es-ES', {  
                                    style: 'percent',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2 
                                }).format(args.originalValue /100); 
                            } 
                            if (args.seriesName.includes("Ejecutada") || args.seriesName.includes("Planeada")) {
                                valueText = new Intl.NumberFormat('en-EN', { style: 'currency', currency: 'USD' }).format(args.originalValue); 
                            }                                      
                            return {html: `${args.seriesName}<div class='currency'>${valueText}</div>` };
                        },
                    }
                });

                grdDatos.bindChart(grdDatosChart, {
                    dataFieldsDisplayMode: 'splitPanes', alternateDataFields: false,
                    customizeSeries: function(seriesName, seriesOptions) {

                        if (seriesName.includes("Tasa")) {
                            seriesOptions.label = seriesOptions.label || {};
                            seriesOptions.label.visible = true;
                            seriesOptions.label.customizeText = function (arg) {
                                return new Intl.NumberFormat('es-ES', {
                                style: 'percent',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                                }).format(arg.value / 100);
                            };
                        }
                        return seriesOptions;
                    }
                });

                grdDatos.option({
                    allowSortingBySummary: true,
                    allowFiltering: true,
                    allowSorting: true,
                    showBorders: true,
                    showColumnGrandTotals: true,
                    showRowGrandTotals: true,
                    showRowTotals: true,
                    showColumnTotals: false,
                    fieldPanel: {
                        showColumnFields: true,
                        showDataFields: true,
                        showFilterFields: true,
                        showRowFields: true,
                        allowFieldDragging: true,
                        visible: true,
                    },                        
                    fieldChooser: {
                        enabled: true,
                        allowSearch: true
                    },
                    headerFilter: {
                        search: {
                            enabled: true,
                        },
                        showRelevantValues: true,
                        width: 300,
                        height: 400,
                    },
                    export: {
                        enabled: true,
                    },
                    dataSource: {
                        fields: [{
                        dataField: 'id',
                        visible: false
                        },{
                            width: 150,
                            caption: 'Sucursal',
                            dataField: 'sucursal',
                            area: 'row',
                            sortOrder: 'asc'
                        },{
                            width: 150,
                            caption: 'Asesor',
                            dataField: 'asesor',
                            area: 'row',
                            sortOrder: 'asc'
                        },{
                            caption: 'Fecha',
                            dataField: 'fecha',
                            dataType: 'date',
                            area: 'column',
                            sortOrder: 'desc'
                        },{
                            summaryType: 'sum',
                            dataField: "ejecutada",
                            dataType: 'number',
                            caption: 'Ejecutada',
                            area: 'data',
                            format: {
                                formatter: function (value) {
                                    if (value == null) return "";
                                    return "$ " + value.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                }   
                            },
                        },{
                            caption: "Planeada",
                            dataField: "planeada",
                            dataType: 'number',
                            area: "data",
                            summaryType: "sum",
                            format: {
                                formatter: function (value) {
                                    if (value == null) return "";
                                    return "$ " + value.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                }   
                            },
                        },{                                
                            caption: "Tasa",
                            dataType: 'number',
                            area: 'data',
                            format: '#0.00\'%\'',
                            calculateSummaryValue: function(summaryCell) {
                                
                                if (summaryCell.value("Planeada") === undefined) {
                                    return null;
                                }

                                var value = 0;
                                value = summaryCell.value("Planeada") > 0 ? Number((summaryCell.value("Ejecutada") / summaryCell.value("Planeada"))*100) : 0;
                                return value;
                            }
                        }],
                        store: datosKpi
                    }
                });

            }
            if (idbtn == "kpi5") {

                divDescripcionPki.innerHTML = "<b>Clientes:</b> Número de clientes adquiridos. <b>Prospectos:</b> Número de prospectos atendidos. <b>Tasa:</b> Porcentaje de conversión de prospectos a clientes.";
                
                grdDatosChart.option({
                    commonSeriesSettings: {
                        type: 'bar',
                        label: {
                        visible: true,
                            format: {
                                type: 'fixedPoint',
                                precision: 0,
                            },
                        },
                    },
                    tooltip: {
                        enabled: true,
                        customizeTooltip(args) {                                  
                            let valueText = args.originalValue;  
                            if (args.seriesName.includes("Tasa")) {
                            valueText = new Intl.NumberFormat('es-ES', {  
                                    style: 'percent',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2 
                                }).format(args.originalValue /100); 
                            }                   
                            return {html: `${args.seriesName}<div class='currency'>${valueText}</div>` };
                        },
                    }
                });

                grdDatos.bindChart(grdDatosChart, {
                    dataFieldsDisplayMode: 'splitPanes', alternateDataFields: false,
                    customizeSeries: function(seriesName, seriesOptions) {

                        if (seriesName.includes("Tasa")) {
                            seriesOptions.label = seriesOptions.label || {};
                            seriesOptions.label.visible = true;
                            seriesOptions.label.customizeText = function (arg) {
                                return new Intl.NumberFormat('es-ES', {
                                style: 'percent',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                                }).format(arg.value / 100);
                            };
                        }
                        return seriesOptions;
                    }
                }); 

                grdDatos.option({
                    allowSortingBySummary: true,
                    allowFiltering: true,
                    allowSorting: true,
                    showBorders: true,
                    showColumnGrandTotals: true,
                    showRowGrandTotals: true,
                    showRowTotals: true,
                    showColumnTotals: false,
                    fieldPanel: {
                        showColumnFields: true,
                        showDataFields: true,
                        showFilterFields: true,
                        showRowFields: true,
                        allowFieldDragging: true,
                        visible: true,
                    },                        
                    fieldChooser: {
                        enabled: true,
                        allowSearch: true
                    },
                    headerFilter: {
                        search: {
                            enabled: true,
                        },
                        showRelevantValues: true,
                        width: 300,
                        height: 400,
                    },
                    export: {
                        enabled: true,
                    },
                    dataSource: {
                        fields: [{
                        dataField: 'id',
                        visible: false
                        },{
                            width: 150,
                            caption: 'Sucursal',
                            dataField: 'sucursal',
                            area: 'row',
                            sortOrder: 'asc'
                        },{
                            width: 150,
                            caption: 'Asesor',
                            dataField: 'asesor',
                            area: 'row',
                            sortOrder: 'asc'
                        },{
                            caption: 'Fecha',
                            dataField: 'fecha',
                            dataType: 'date',
                            area: 'column',
                            sortOrder: 'desc'
                        },{
                            summaryType: 'sum',
                            dataField: "clientes",
                            dataType: 'number',
                            caption: 'Clientes',
                            area: 'data'
                        },{
                            caption: "Prospectos",
                            dataField: "prospectos",
                            dataType: 'number',
                            area: "data",
                            summaryType: "sum"
                        },{                                
                            caption: "Tasa",
                            dataType: 'number',
                            area: 'data',                                
                            format: '#0.00\'%\'',
                            calculateSummaryValue: function(summaryCell) {
                                
                                if (summaryCell.value("Clientes") === undefined) {
                                    return undefined;
                                }

                                var value = 0;
                                value = summaryCell.value("Clientes") > 0 ? Number((summaryCell.value("Clientes") / summaryCell.value("Prospectos"))*100): 0;
                                return value;
                            }
                        }],
                        store: datosKpi
                    }
                });

            }
            if (idbtn == "kpi6") {

                divDescripcionPki.innerHTML = "<b>Ejecutada:</b> Número de demostraciones realizadas. <b>Planeada:</b> Número de demostraciones planificadas. <b>Tasa:</b> Porcentaje de cumplimiento de la meta.";

                grdDatosChart.option({
                    commonSeriesSettings: {
                        type: 'bar',
                        label: {
                        visible: true,
                            format: {
                                type: 'fixedPoint',
                                precision: 0,
                            },
                        },
                    },
                    tooltip: {
                        enabled: true,
                        customizeTooltip(args) {                                  
                            let valueText = args.originalValue;  
                            if (args.seriesName.includes("Tasa")) {
                            valueText = new Intl.NumberFormat('es-ES', {  
                                    style: 'percent',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2 
                                }).format(args.originalValue /100); 
                            }                   
                            return {html: `${args.seriesName}<div class='currency'>${valueText}</div>` };
                        },
                    }
                });

                grdDatos.bindChart(grdDatosChart, {
                    dataFieldsDisplayMode: 'splitPanes', alternateDataFields: false,
                    customizeSeries: function(seriesName, seriesOptions) {

                        if (seriesName.includes("Tasa")) {
                            seriesOptions.label = seriesOptions.label || {};
                            seriesOptions.label.visible = true;
                            seriesOptions.label.customizeText = function (arg) {
                                return new Intl.NumberFormat('es-ES', {
                                style: 'percent',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                                }).format(arg.value / 100);
                            };
                        }
                        return seriesOptions;
                    }
                }); 

                grdDatos.option({
                    allowSortingBySummary: true,
                    allowFiltering: true,
                    allowSorting: true,
                    showBorders: true,
                    showColumnGrandTotals: true,
                    showRowGrandTotals: true,
                    showRowTotals: true,
                    showColumnTotals: false,
                    fieldPanel: {
                        showColumnFields: true,
                        showDataFields: true,
                        showFilterFields: true,
                        showRowFields: true,
                        allowFieldDragging: true,
                        visible: true,
                    },                        
                    fieldChooser: {
                        enabled: true,
                        allowSearch: true
                    },
                    headerFilter: {
                        search: {
                            enabled: true,
                        },
                        showRelevantValues: true,
                        width: 300,
                        height: 400,
                    },
                    export: {
                        enabled: true,
                    },
                    dataSource: {
                        fields: [{
                        dataField: 'id',
                        visible: false
                        },{
                            width: 150,
                            caption: 'Sucursal',
                            dataField: 'sucursal',
                            area: 'row',
                            sortOrder: 'asc'
                        },{
                            width: 150,
                            caption: 'Asesor',
                            dataField: 'asesor',
                            area: 'row',
                            sortOrder: 'asc'
                        },{
                            caption: 'Fecha',
                            dataField: 'fecha',
                            dataType: 'date',
                            area: 'column',
                            sortOrder: 'desc'
                        },{
                            summaryType: 'sum',
                            dataField: "ejecutada",
                            dataType: 'number',
                            caption: 'Ejecutada',
                            area: 'data'
                        },{
                            caption: "Planeada",
                            dataField: "planeada",
                            dataType: 'number',
                            area: "data",
                            summaryType: "sum"
                        },{                                
                            caption: "Tasa",
                            dataType: 'number',
                            area: 'data',                                
                            format: '#0.00\'%\'',
                            calculateSummaryValue: function(summaryCell) {
                                
                                if (summaryCell.value("Ejecutada") === undefined) {
                                    return undefined;
                                }

                                var value = 0;
                                value = summaryCell.value("Ejecutada") > 0 ? Number((summaryCell.value("Ejecutada") / summaryCell.value("Planeada"))*100): 0;
                                return value;Meta
                            }
                        }],
                        store: datosKpi
                    }
                });

            }
            if (idbtn == "kpi7") {

                divDescripcionPki.innerHTML = "<b>Cantidad:</b> Número de llamadas realizadas.";

                grdDatosChart.option({
                    commonSeriesSettings: {
                        type: 'bar',
                        label: {
                        visible: true,
                        format: {
                            type: 'fixedPoint',
                            precision: 0,
                        },
                        },
                    },
                    tooltip: {
                        enabled: true,
                        customizeTooltip(args) {                               
                            let valueText = args.originalValue;  
                            return {html: `${args.seriesName}<div class='currency'>${valueText}</div>` };
                        },
                    }
                });

                grdDatos.option({
                    allowSortingBySummary: true,
                    allowFiltering: true,
                    allowSorting: true,
                    showBorders: true,
                    showColumnGrandTotals: true,
                    showRowGrandTotals: true,
                    showRowTotals: true,
                    showColumnTotals: false,
                    fieldPanel: {
                        showColumnFields: true,
                        showDataFields: true,
                        showFilterFields: true,
                        showRowFields: true,
                        allowFieldDragging: true,
                        visible: true,
                    },                        
                    fieldChooser: {
                        enabled: true,
                        allowSearch: true
                    },
                    headerFilter: {
                        search: {
                            enabled: true,
                        },
                        showRelevantValues: true,
                        width: 300,
                        height: 400,
                    },
                    export: {
                        enabled: true,
                    },
                    dataSource: {
                        fields: [{
                        dataField: 'id',
                        visible: false
                        },{
                            width: 150,
                            caption: 'Sucursal',
                            dataField: 'sucursal',
                            area: 'row',
                            sortOrder: 'asc'
                        },{
                            width: 150,
                            caption: 'Asesor',
                            dataField: 'asesor',
                            area: 'row',
                            sortOrder: 'asc'
                        },{
                            caption: 'Fecha',
                            dataField: 'fecha',
                            dataType: 'date',
                            area: 'column',
                            sortOrder: 'desc'
                        },{
                            groupName: 'date',
                            groupInterval: 'month',
                            sortOrder: 'desc'
                        },{
                            summaryType: 'count',
                            caption: 'Cantidad',
                            area: 'data',
                            sortOrder: 'desc'
                        }],
                        store: datosKpi
                    },onCellPrepared: function(e) {
                        if (e.area === "row" && e.cellElement && e.cell.text) {
                            const valor = e.cell.text;
                            if (e.cell.path?.length == 4) {
                                e.cellElement.empty();
                                $("<a>")
                                    .attr("href", `clientes-seguimiento-editar.php?id=${valor}`)
                                    .attr("target", "_blank")
                                    .text(valor)
                                    .appendTo(e.cellElement);
                            }else {
                                e.cellElement;
                            }
                        }
                    }
                });

            }
            if (idbtn == "kpi8") {

                divDescripcionPki.innerHTML = "<b>Ganados:</b> Número de clientes adquiridos. <b>Generados:</b> Número de clientes atendidos. <b>Tasa:</b> Porcentaje de conversión a clientes.";

                grdDatosChart.option({
                    commonSeriesSettings: {
                        type: 'bar',
                        label: {
                        visible: true,
                            format: {
                                type: 'fixedPoint',
                                precision: 0,
                            },
                        },
                    },
                    tooltip: {
                        enabled: true,
                        customizeTooltip(args) {                                  
                            let valueText = args.originalValue;  
                            if (args.seriesName.includes("Tasa")) {
                            valueText = new Intl.NumberFormat('es-ES', {  
                                    style: 'percent',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2 
                                }).format(args.originalValue /100); 
                            }                   
                            return {html: `${args.seriesName}<div class='currency'>${valueText}</div>` };
                        },
                    }
                });

                grdDatos.bindChart(grdDatosChart, {
                    dataFieldsDisplayMode: 'splitPanes', alternateDataFields: false,
                    customizeSeries: function(seriesName, seriesOptions) {

                        if (seriesName.includes("Tasa")) {
                            seriesOptions.label = seriesOptions.label || {};
                            seriesOptions.label.visible = true;
                            seriesOptions.label.customizeText = function (arg) {
                                return new Intl.NumberFormat('es-ES', {
                                style: 'percent',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                                }).format(arg.value / 100);
                            };
                        }
                        return seriesOptions;
                    }
                }); 

                grdDatos.option({
                    allowSortingBySummary: true,
                    allowFiltering: true,
                    allowSorting: true,
                    showBorders: true,
                    showColumnGrandTotals: true,
                    showRowGrandTotals: true,
                    showRowTotals: true,
                    showColumnTotals: false,
                    fieldPanel: {
                        showColumnFields: true,
                        showDataFields: true,
                        showFilterFields: true,
                        showRowFields: true,
                        allowFieldDragging: true,
                        visible: true,
                    },                        
                    fieldChooser: {
                        enabled: true,
                        allowSearch: true
                    },
                    headerFilter: {
                        search: {
                            enabled: true,
                        },
                        showRelevantValues: true,
                        width: 300,
                        height: 400,
                    },
                    export: {
                        enabled: true,
                    },
                    dataSource: {
                        fields: [{
                        dataField: 'id',
                        visible: false
                        },{
                            width: 150,
                            caption: 'Sucursal',
                            dataField: 'sucursal',
                            area: 'row',
                            sortOrder: 'asc'
                        },{
                            width: 150,
                            caption: 'Asesor',
                            dataField: 'asesor',
                            area: 'row',
                            sortOrder: 'asc'
                        },{
                            caption: 'Fecha',
                            dataField: 'fecha',
                            dataType: 'date',
                            area: 'column',
                            sortOrder: 'desc'
                        },{
                            summaryType: 'sum',
                            dataField: "clientes",
                            dataType: 'number',
                            caption: 'Ganados',
                            area: 'data'
                        },{
                            caption: "Generados",
                            dataField: "prospectos",
                            dataType: 'number',
                            area: "data",
                            summaryType: "sum"
                        },{                                
                            caption: "Tasa",
                            dataType: 'number',
                            area: 'data',                                
                            format: '#0.00\'%\'',
                            calculateSummaryValue: function(summaryCell) {
                                
                                if (summaryCell.value("Ganados") === undefined) {
                                    return undefined;
                                }

                                var value = 0;
                                value = summaryCell.value("Ganados") > 0 ? Number((summaryCell.value("Ganados") / summaryCell.value("Generados"))*100): 0;
                                return value;
                            }
                        }],
                        store: datosKpi
                    }
                });

            }
            if (idbtn == "kpi9") {

                divDescripcionPki.innerHTML = "<b>Nuevos:</b> Número de clientes nuevos. <b>Actuales:</b> Número de clientes actuales. <b>Tasa:</b> Porcentaje de clientes nuevos sobre el total de clientes.";

                grdDatosChart.option({
                    commonSeriesSettings: {
                        type: 'bar',
                        label: {
                        visible: true,
                            format: {
                                type: 'fixedPoint',
                                precision: 0,
                            },
                        },
                    },
                    tooltip: {
                        enabled: true,
                        customizeTooltip(args) {                                  
                            let valueText = args.originalValue;  
                            if (args.seriesName.includes("Tasa")) {
                            valueText = new Intl.NumberFormat('es-ES', {  
                                    style: 'percent',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2 
                                }).format(args.originalValue /100); 
                            }                   
                            return {html: `${args.seriesName}<div class='currency'>${valueText}</div>` };
                        },
                    }
                });

                grdDatos.bindChart(grdDatosChart, {
                    dataFieldsDisplayMode: 'splitPanes', alternateDataFields: false,
                    customizeSeries: function(seriesName, seriesOptions) {

                        if (seriesName.includes("Tasa")) {
                            seriesOptions.label = seriesOptions.label || {};
                            seriesOptions.label.visible = true;
                            seriesOptions.label.customizeText = function (arg) {
                                return new Intl.NumberFormat('es-ES', {
                                style: 'percent',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                                }).format(arg.value / 100);
                            };
                        }
                        return seriesOptions;
                    }
                }); 

                grdDatos.option({
                    allowSortingBySummary: true,
                    allowFiltering: true,
                    allowSorting: true,
                    showBorders: true,
                    showColumnGrandTotals: true,
                    showRowGrandTotals: true,
                    showRowTotals: true,
                    showColumnTotals: false,
                    fieldPanel: {
                        showColumnFields: true,
                        showDataFields: true,
                        showFilterFields: true,
                        showRowFields: true,
                        allowFieldDragging: true,
                        visible: true,
                    },                        
                    fieldChooser: {
                        enabled: true,
                        allowSearch: true
                    },
                    headerFilter: {
                        search: {
                            enabled: true,
                        },
                        showRelevantValues: true,
                        width: 300,
                        height: 400,
                    },
                    export: {
                        enabled: true,
                    },
                    dataSource: {
                        fields: [{
                        dataField: 'id',
                        visible: false
                        },{
                            width: 150,
                            caption: 'Sucursal',
                            dataField: 'sucursal',
                            area: 'row',
                            sortOrder: 'asc',
                            expanded: true
                        },{
                            width: 150,
                            caption: 'Asesor',
                            dataField: 'asesor',
                            area: 'row',
                            sortOrder: 'asc',
                            expanded: true
                        },{
                            caption: 'Fecha',
                            dataField: 'fecha',
                            dataType: 'date',
                            area: 'column',
                            sortOrder: 'desc',
                            expanded: true
                        },{
                            groupName: 'date',
                            groupInterval: 'quarter',
                            expanded: true
                        },{
                            groupName: 'date',
                            groupInterval: 'month'
                        },{
                            summaryType: 'sum',
                            dataField: "nuevos",
                            dataType: 'number',
                            caption: 'Nuevos',
                            area: 'data'
                        },{
                            caption: "Actuales",
                            dataField: "actuales",
                            dataType: 'number',
                            area: "data",
                            summaryType: "sum"
                        },{                                
                            caption: "Tasa",
                            dataType: 'number',
                            area: 'data',                                
                            format: '#0.00\'%\'',
                            calculateSummaryValue: function(summaryCell) {
                                
                                if (summaryCell.value("Nuevos") === undefined) {
                                    return undefined;
                                }

                                var value = 0;
                                value = summaryCell.value("Actuales") > 0 ? Number((summaryCell.value("Nuevos") / summaryCell.value("Actuales"))*100): 0;
                                return value;
                            }
                        }],
                        store: datosKpi
                    }
                });

               

            }
            if (idbtn == "kpi10") {

                divDescripcionPki.innerHTML = "<b>Nuevos:</b> Número de clientes nuevos. <b>Actuales:</b> Número de clientes actuales. <b>Tasa:</b> Porcentaje de clientes nuevos sobre el total de clientes.";

                grdDatosChart.option({
                    commonSeriesSettings: {
                        type: 'bar',
                        label: {
                        visible: true,
                            format: {
                                type: 'fixedPoint',
                                precision: 0,
                            },
                        },
                    },
                    tooltip: {
                        enabled: true,
                        customizeTooltip(args) {                                  
                            let valueText = args.originalValue;  
                            if (args.seriesName.includes("Tasa")) {
                            valueText = new Intl.NumberFormat('es-ES', {  
                                    style: 'percent',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2 
                                }).format(args.originalValue /100); 
                            }                   
                            return {html: `${args.seriesName}<div class='currency'>${valueText}</div>` };
                        },
                    }
                });

                grdDatos.bindChart(grdDatosChart, {
                    dataFieldsDisplayMode: 'splitPanes', alternateDataFields: false,
                    customizeSeries: function(seriesName, seriesOptions) {

                        if (seriesName.includes("Tasa")) {
                            seriesOptions.label = seriesOptions.label || {};
                            seriesOptions.label.visible = true;
                            seriesOptions.label.customizeText = function (arg) {
                                return new Intl.NumberFormat('es-ES', {
                                style: 'percent',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                                }).format(arg.value / 100);
                            };
                        }
                        return seriesOptions;
                    }
                });

                grdDatos.option({
                    allowSortingBySummary: true,
                    allowFiltering: true,
                    allowSorting: true,
                    showBorders: true,
                    showColumnGrandTotals: true,
                    showRowGrandTotals: true,
                    showRowTotals: true,
                    showColumnTotals: false,
                    fieldPanel: {
                        showColumnFields: true,
                        showDataFields: true,
                        showFilterFields: true,
                        showRowFields: true,
                        allowFieldDragging: true,
                        visible: true,
                    },                        
                    fieldChooser: {
                        enabled: true,
                        allowSearch: true
                    },
                    headerFilter: {
                        search: {
                            enabled: true,
                        },
                        showRelevantValues: true,
                        width: 300,
                        height: 400,
                    },
                    export: {
                        enabled: true,
                    },
                    dataSource: {
                        fields: [{
                        dataField: 'id',
                        visible: false
                        },{
                            width: 150,
                            caption: 'Sucursal',
                            dataField: 'sucursal',
                            area: 'row',
                            sortOrder: 'asc',
                            expanded: true
                        },{
                            width: 150,
                            caption: 'Asesor',
                            dataField: 'asesor',
                            area: 'row',
                            sortOrder: 'asc',
                            expanded: true
                        },{
                            caption: 'Fecha',
                            dataField: 'fecha',
                            dataType: 'date',
                            area: 'column',
                            sortOrder: 'desc',
                            expanded: true
                        },{
                            groupName: 'date',
                            groupInterval: 'quarter',
                            expanded: true
                        },{
                            groupName: 'date',
                            groupInterval: 'month'
                        },{
                            summaryType: 'sum',
                            dataField: "nuevos",
                            dataType: 'number',
                            caption: 'Nuevos',
                            area: 'data'
                        },{
                            caption: "Actuales",
                            dataField: "actuales",
                            dataType: 'number',
                            area: "data",
                            summaryType: "sum"
                        },{                                
                            caption: "Tasa",
                            dataType: 'number',
                            area: 'data',                                
                            format: '#0.00\'%\'',
                            calculateSummaryValue: function(summaryCell) {
                                
                                if (summaryCell.value("Nuevos") === undefined) {
                                    return undefined;
                                }

                                var value = 0;
                                value = summaryCell.value("Actuales") > 0 ? Number((summaryCell.value("Nuevos") / summaryCell.value("Actuales"))*100): 0;
                                return value;
                            }
                        }],
                        store: datosKpi
                    },onOptionChanged: function(e) {
                        if(e.name === "fecha") {
                            // handle the property change here
                        }
                    }
                });

            }
            if (idbtn == "kpi11") {

                divDescripcionPki.innerHTML = "<b>Ejecutada:</b> Número de visitas ejecutadas. <b>Planeada:</b> Número de visitas Planificadas. <b>Tasa:</b> Porcentaje de cumplimiento de la meta.";

                grdDatosChart.option({
                    commonSeriesSettings: {
                        type: 'bar',
                        label: {
                        visible: true,
                            format: {
                                type: 'fixedPoint',
                                precision: 0,
                            },
                        },
                    },
                    tooltip: {
                        enabled: true,
                        customizeTooltip(args) {                                  
                            let valueText = args.originalValue;  
                            if (args.seriesName.includes("Tasa")) {
                            valueText = new Intl.NumberFormat('es-ES', {  
                                    style: 'percent',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2 
                                }).format(args.originalValue /100); 
                            }                   
                            return {html: `${args.seriesName}<div class='currency'>${valueText}</div>` };
                        },
                    }
                });

                grdDatos.bindChart(grdDatosChart, {
                    dataFieldsDisplayMode: 'splitPanes', alternateDataFields: false,
                    customizeSeries: function(seriesName, seriesOptions) {

                        if (seriesName.includes("Tasa")) {
                            seriesOptions.label = seriesOptions.label || {};
                            seriesOptions.label.visible = true;
                            seriesOptions.label.customizeText = function (arg) {
                                return new Intl.NumberFormat('es-ES', {
                                style: 'percent',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                                }).format(arg.value / 100);
                            };
                        }
                        return seriesOptions;
                    }
                });

                grdDatos.option({
                    allowSortingBySummary: true,
                    allowFiltering: true,
                    allowSorting: true,
                    showBorders: true,
                    showColumnGrandTotals: true,
                    showRowGrandTotals: true,
                    showRowTotals: true,
                    showColumnTotals: false,
                    fieldPanel: {
                        showColumnFields: true,
                        showDataFields: true,
                        showFilterFields: true,
                        showRowFields: true,
                        allowFieldDragging: true,
                        visible: true,
                    },                        
                    fieldChooser: {
                        enabled: true,
                        allowSearch: true
                    },
                    headerFilter: {
                        search: {
                            enabled: true,
                        },
                        showRelevantValues: true,
                        width: 300,
                        height: 400,
                    },
                    export: {
                        enabled: true,
                    },
                    dataSource: {
                        fields: [{
                        dataField: 'id',
                        visible: false
                        },{
                            width: 150,
                            caption: 'Sucursal',
                            dataField: 'sucursal',
                            area: 'row',
                            sortOrder: 'asc'
                        },{
                            width: 150,
                            caption: 'Asesor',
                            dataField: 'asesor',
                            area: 'row',
                            sortOrder: 'asc'
                        },{
                            caption: 'Fecha',
                            dataField: 'fecha',
                            dataType: 'date',
                            area: 'column',
                            sortOrder: 'desc'
                        },{
                            summaryType: 'sum',
                            dataField: "ejecutada",
                            dataType: 'number',
                            caption: 'Ejecutada',
                            area: 'data'
                        },{
                            caption: "Planeada",
                            dataField: "planeada",
                            dataType: 'number',
                            area: "data",
                            summaryType: "sum"
                        },{                                
                            caption: "Tasa",
                            dataType: 'number',
                            area: 'data',                                
                            format: '#0.00\'%\'',
                            calculateSummaryValue: function(summaryCell) {
                                
                                if (summaryCell.value("Ejecutada") === undefined) {
                                    return undefined;
                                }

                                var value = 0;
                                value = summaryCell.value("Planeada") > 0 ? Number((summaryCell.value("Ejecutada") / summaryCell.value("Planeada"))*100): 0;
                                return value;
                            }
                        }],
                        store: datosKpi
                    }
                });

            }

            const currentYear = new Date().getFullYear();

                // 2. Buscar el campo que tiene el área de YEAR
                
            const ds = grdDatos.getDataSource();
            const fields = ds.fields();
            const yearFieldIndex = fields.findIndex(f => f.caption === "Fecha");

            // 3. Aplicar el filtro en ese campo
            if (yearFieldIndex >= 0) {
                fields[yearFieldIndex].filterValues = [[currentYear]];;
                fields[yearFieldIndex].filterType = "include";
                ds.reload().done(() => {
                    // 4. Expandir automáticamente la columna del año actual
                    ds.expandHeaderItem("column", [currentYear]);
                });
            }   
            
            grdDatos.option({
                onCellClick: function(e) {
                    if(e.area === "data") {

                        if(!e.cell.value){
                            clsGenerales_.mtdMostrarMensaje("Seleccione una celda con valor", "warning");
                            return;
                        }

                        let datosDetalleKpi = [];
                        modalDetalle.option({ title: "Detalle del KPI - " + divEncabezadoPki.innerText });

                        modalDetalle.option({
                            contentTemplate: function(container) {  
                                $("<div id='gridPopup'>")
                                .appendTo(container)
                                .dxDataGrid({
                                    dataSource: datosDetalleKpi,
                                    keyExpr: "id",
                                    showBorders: true,
                                    showColumnLines: true,
                                    showRowLines: true,
                                    noDataText: "No hay datos para mostrar"
                                });
                            }
                        });

                        if(idbtn == "kpi1" || idbtn == "kpi2" || idbtn == "kpi4" ) {

                            let condicionSucusalAsesor = " sp.sucp_nombre =  '" + e.cell.rowPath[0] + "' ";

                            if(e.cell.rowPath.length == 2) {
                                condicionSucusalAsesor += " AND us.usr_nombre =  '" + e.cell.rowPath[1] + "' ";
                            }

                            let anno = e.cell.columnPath[0];
                            let condicionFecha = " AND YEAR(fac.factura_fecha_creacion) = '" + anno + "' ";
                            if(e.cell.columnPath.length == 2) {
                                const quarter = e.cell.columnPath[1];

                                let month = "";
                                if(quarter == "1") { month = anno+"-01','"+anno+"-02','"+anno+"-03'"; }
                                if(quarter == "2") { month = anno+"-04','"+anno+"-05','"+anno+"-06'"; }
                                if(quarter == "3") { month = anno+"-07','"+anno+"-08','"+anno+"-09'"; }
                                if(quarter == "4") { month = anno+"-10','"+anno+"-11','"+anno+"-12'"; }

                                condicionFecha = "  AND DATE_FORMAT(fac.factura_fecha_creacion, '%Y-%m') IN ('" + month + ") ";
                            }
                            if(e.cell.columnPath.length == 3) {
                                const month = String(e.cell.columnPath[2]).padStart(2, '0');
                                condicionFecha = " AND DATE_FORMAT(fac.factura_fecha_creacion, '%Y-%m') = '" + e.cell.columnPath[0] + "-" + month + "' ";
                            }

                            clsGenerales_.mtdActivarLoadPagina();

                            let opcionKpi = "consultar_detalleKpi_1_2_ventas";
                            if(idbtn == "kpi4") { opcionKpi = "consultar_detalleKpi_4_cumplimiento_cuota_comercial"; }

                            $.ajax({
                                url: "ajax/ajax-kpis.php",
                                type: "POST",
                                crossDomain: true,
                                dataType: 'json',
                                data: {
                                    e_datos: JSON.stringify([{}]),
                                    opcion: opcionKpi,
                                    condicion: condicionSucusalAsesor + condicionFecha
                                },
                                error: function() {
                                    clsGenerales_.mtdDesactivarLoadPagina();
                                    clsGenerales_.mtdMostrarMensaje("No se pudo completar la solicitud", "error");
                                }
                            }).done((respuesta) => {
                                clsGenerales_.mtdDesactivarLoadPagina();

                                if (respuesta["estado"] === 'ok') {  
                                    datosDetalleKpi = respuesta["datos"]; 
                                }
                                if (respuesta["estado"] === 'ko') {
                                    clsGenerales_.mtdMostrarMensaje(respuesta["mensaje"], "error");
                                }
                                modalDetalle.option({
                                    contentTemplate: function(container) {
                                        $("<div id='gridPopup'>")
                                        .appendTo(container)
                                        .dxDataGrid({
                                            dataSource: datosDetalleKpi,
                                            keyExpr: "id",
                                            showBorders: true,
                                            showColumnLines: true,
                                            columnAutoWidth: true,
                                            showRowLines: true,
                                            noDataText: "No hay datos para mostrar",
                                            paging: { pageSize: 10 },
                                            pager: {
                                                showPageSizeSelector: true,
                                                allowedPageSizes: [10, 30, 50],
                                                showInfo: true
                                            },
                                            columns: [
                                                { dataField: "id", visible: false },{ dataField: "factura",
                                                    cellTemplate: function (container, options) {
                                                        $("<a>")
                                                            .text(options.data.factura)
                                                            .attr("href", "facturas.php?busqueda=" + options.data.factura) // 🔑 URL dinámica
                                                            .attr("target", "_blank") // abrir en nueva pestaña
                                                            .appendTo(container);
                                                    }   
                                                }, "fecha","sucursal", "asesor", "identificacion", "cliente", 
                                                {
                                                    dataField: 'valor',
                                                    dataType: 'number',
                                                    valueFormat: { type: "fixedPoint", precision: 2 },
                                                    displayFormat: "{0}",
                                                    customizeText: function(e) {
                                                        return new Intl.NumberFormat("es-CO", { 
                                                            style: "currency", 
                                                            currency: "COP", 
                                                            minimumFractionDigits: 2 
                                                        }).format(e.value);
                                                    },
                                                    alignment: 'right',
                                                }
                                            ],
                                            summary: {
                                                totalItems: [{
                                                    column: "fecha",
                                                    summaryType: "count",
                                                    displayFormat: "{0}"
                                                }, {
                                                    column: "valor",
                                                    summaryType: "sum",
                                                    valueFormat: { type: "fixedPoint", precision: 2 },
                                                    displayFormat: "{0}",
                                                    customizeText: function(e) {
                                                        return new Intl.NumberFormat("es-CO", { 
                                                            style: "currency", 
                                                            currency: "COP", 
                                                            minimumFractionDigits: 2 
                                                        }).format(e.value);
                                                    }
                                                }],
                                                groupItems: [{
                                                    column: "fecha",
                                                    summaryType: "count",
                                                    displayFormat: "{0}",
                                                }]
                                            },
                                        });
                                    }
                                })
                            }); 
                        }

                        if(idbtn == "kpi3") {

                            let condicionSucusalAsesor = " sp.sucp_nombre =  '" + e.cell.rowPath[0] + "' ";

                            if(e.cell.rowPath.length == 2) {
                                condicionSucusalAsesor += " AND u.usr_nombre =  '" + e.cell.rowPath[1] + "' ";
                            }

                            let anno = e.cell.columnPath[0];
                            let condicionFecha = " AND YEAR(ct.tik_fecha_creacion) = '" + anno + "' ";
                            if(e.cell.columnPath.length == 2) {
                                const quarter = e.cell.columnPath[1];

                                let month = "";
                                if(quarter == "1") { month = anno+"-01','"+anno+"-02','"+anno+"-03'"; }
                                if(quarter == "2") { month = anno+"-04','"+anno+"-05','"+anno+"-06'"; }
                                if(quarter == "3") { month = anno+"-07','"+anno+"-08','"+anno+"-09'"; }
                                if(quarter == "4") { month = anno+"-10','"+anno+"-11','"+anno+"-12'"; }

                                condicionFecha = "  AND DATE_FORMAT(ct.tik_fecha_creacion, '%Y-%m') IN ('" + month + ") ";
                            }
                            if(e.cell.columnPath.length == 3) {
                                const month = String(e.cell.columnPath[2]).padStart(2, '0');
                                condicionFecha = " AND DATE_FORMAT(ct.tik_fecha_creacion, '%Y-%m') = '" + e.cell.columnPath[0] + "-" + month + "' ";
                            }

                            clsGenerales_.mtdActivarLoadPagina();

                            let opcionKpi = "consultar_detalleKpi_3_tiempo_promedio_cierre_ventas";

                            $.ajax({
                                url: "ajax/ajax-kpis.php",
                                type: "POST",
                                crossDomain: true,
                                dataType: 'json',
                                data: {
                                    e_datos: JSON.stringify([{}]),
                                    opcion: opcionKpi,
                                    condicion: condicionSucusalAsesor + condicionFecha
                                },
                                error: function() {
                                    clsGenerales_.mtdDesactivarLoadPagina();
                                    clsGenerales_.mtdMostrarMensaje("No se pudo completar la solicitud", "error");
                                }
                            }).done((respuesta) => {
                                clsGenerales_.mtdDesactivarLoadPagina();

                                if (respuesta["estado"] === 'ok') {  
                                    datosDetalleKpi = respuesta["datos"]; 
                                }
                                if (respuesta["estado"] === 'ko') {
                                    clsGenerales_.mtdMostrarMensaje(respuesta["mensaje"], "error");
                                }
                                modalDetalle.option({
                                    contentTemplate: function(container) {
                                        $("<div id='gridPopup'>")
                                        .appendTo(container)
                                        .dxDataGrid({
                                            dataSource: datosDetalleKpi,
                                            keyExpr: "id",
                                            showBorders: true,
                                            showColumnLines: true,
                                            columnAutoWidth: true,
                                            showRowLines: true,
                                            noDataText: "No hay datos para mostrar",
                                            paging: { pageSize: 10 },
                                            pager: {
                                                showPageSizeSelector: true,
                                                allowedPageSizes: [10, 30, 50],
                                                showInfo: true
                                            },
                                            columns: [
                                                { dataField: "id", visible: false },{ dataField: "ticket",
                                                    cellTemplate: function (container, options) {
                                                        $("<a>")
                                                            .text(options.data.ticket)
                                                            .attr("href", "clientes-tikets.php?busqueda=" + options.data.ticket) // 🔑 URL dinámica
                                                            .attr("target", "_blank") // abrir en nueva pestaña
                                                            .appendTo(container);
                                                    }   
                                                },{ dataField: "cotizacion",
                                                    cellTemplate: function (container, options) {
                                                        $("<a>")
                                                            .text(options.data.cotizacion)
                                                            .attr("href", "cotizaciones.php?q=" + options.data.cotizacion + "&buscar=Buscar") // 🔑 URL dinámica
                                                            .attr("target", "_blank") // abrir en nueva pestaña
                                                            .appendTo(container);
                                                    }   
                                                },{ dataField: "pedido",
                                                    cellTemplate: function (container, options) {
                                                        $("<a>")
                                                            .text(options.data.pedido)
                                                            .attr("href", "pedidos.php?busqueda=" + options.data.pedido) // 🔑 URL dinámica
                                                            .attr("target", "_blank") // abrir en nueva pestaña
                                                            .appendTo(container);
                                                    }   
                                                },{ dataField: "remision",
                                                    cellTemplate: function (container, options) {
                                                        $("<a>")
                                                            .text(options.data.ticket)
                                                            .attr("href", "remisionbdg.php?busqueda=" + options.data.remision) // 🔑 URL dinámica
                                                            .attr("target", "_blank") // abrir en nueva pestaña
                                                            .appendTo(container);
                                                    }   
                                                },{ dataField: "factura",
                                                    cellTemplate: function (container, options) {
                                                        $("<a>")
                                                            .text(options.data.factura)
                                                            .attr("href", "facturas.php?busqueda=" + options.data.factura) // 🔑 URL dinámica
                                                            .attr("target", "_blank") // abrir en nueva pestaña
                                                            .appendTo(container);
                                                    }   
                                                }, "fecha_inicial","fecha_cierre","dias","sucursal", "asesor", "identificacion", "cliente",
                                            ],
                                            summary: {
                                                totalItems: [{
                                                    column: "fecha_inicial",
                                                    summaryType: "count",
                                                    displayFormat: "{0}"
                                                }],
                                                groupItems: [{
                                                    column: "fecha_inicial",
                                                    summaryType: "count",
                                                    displayFormat: "{0}",
                                                }]
                                            },
                                        });
                                    }
                                })
                            }); 
                        }

                        if(idbtn == "kpi5") {

                            clsGenerales_.mtdActivarLoadPagina();

                            let opcionKpi = "consultar_detalleKpi_5_tasa_conversión_prospecto_cliente_clientes";
                            let fieldOpcion = "cliente";
                            let condicionSucusalAsesor = " s.sucp_nombre =  '" + e.cell.rowPath[0] + "' ";

                            if(e.cell.rowPath.length == 2) {
                                condicionSucusalAsesor += " AND u.usr_nombre =  '" + e.cell.rowPath[1] + "' ";
                            }

                            let anno = e.cell.columnPath[0];
                            let condicionFecha = " AND YEAR(c.cli_fecha_ingreso) = '" + anno + "' ";
                            if(e.cell.columnPath.length == 2) {
                                const quarter = e.cell.columnPath[1];

                                let month = "";
                                if(quarter == "1") { month = anno+"-01','"+anno+"-02','"+anno+"-03'"; }
                                if(quarter == "2") { month = anno+"-04','"+anno+"-05','"+anno+"-06'"; }
                                if(quarter == "3") { month = anno+"-07','"+anno+"-08','"+anno+"-09'"; }
                                if(quarter == "4") { month = anno+"-10','"+anno+"-11','"+anno+"-12'"; }

                                condicionFecha = "  AND DATE_FORMAT(c.cli_fecha_ingreso, '%Y-%m') IN ('" + month + ") ";
                            }
                            if(e.cell.columnPath.length == 3) {
                                let month = String(e.cell.columnPath[2]).padStart(2, '0');
                                condicionFecha = " AND DATE_FORMAT(c.cli_fecha_ingreso, '%Y-%m') = '" + e.cell.columnPath[0] + "-" + month + "' ";
                            }

                            
                            if(e.cell.dataIndex == 1) { 
                                fieldOpcion = "prospecto";
                                opcionKpi = "consultar_detalleKpi_5_tasa_conversión_prospecto_cliente_prospectos"; 

                                condicionSucusalAsesor = " s.sucp_nombre =  '" + e.cell.rowPath[0] + "' ";

                                if(e.cell.rowPath.length == 2) {
                                    condicionSucusalAsesor += " AND u.usr_nombre =  '" + e.cell.rowPath[1] + "' ";
                                }

                                anno = e.cell.columnPath[0];
                                condicionFecha = " AND YEAR(c.cli_fecha_registro) = '" + anno + "' ";
                                if(e.cell.columnPath.length == 2) {
                                    const quarter = e.cell.columnPath[1];

                                    let month = "";
                                    if(quarter == "1") { month = anno+"-01','"+anno+"-02','"+anno+"-03'"; }
                                    if(quarter == "2") { month = anno+"-04','"+anno+"-05','"+anno+"-06'"; }
                                    if(quarter == "3") { month = anno+"-07','"+anno+"-08','"+anno+"-09'"; }
                                    if(quarter == "4") { month = anno+"-10','"+anno+"-11','"+anno+"-12'"; }

                                    condicionFecha = "  AND DATE_FORMAT(c.cli_fecha_registro, '%Y-%m') IN ('" + month + ") ";
                                }
                                if(e.cell.columnPath.length == 3) {
                                    let month = String(e.cell.columnPath[2]).padStart(2, '0');
                                    condicionFecha = " AND DATE_FORMAT(c.cli_fecha_registro, '%Y-%m') = '" + e.cell.columnPath[0] + "-" + month + "' ";
                                }                            
                            }

                            $.ajax({
                                url: "ajax/ajax-kpis.php",
                                type: "POST",
                                crossDomain: true,
                                dataType: 'json',
                                data: {
                                    e_datos: JSON.stringify([{}]),
                                    opcion: opcionKpi,
                                    condicion: condicionSucusalAsesor + condicionFecha
                                },
                                error: function() {
                                    clsGenerales_.mtdDesactivarLoadPagina();
                                    clsGenerales_.mtdMostrarMensaje("No se pudo completar la solicitud", "error");
                                }
                            }).done((respuesta) => {
                                clsGenerales_.mtdDesactivarLoadPagina();

                                if (respuesta["estado"] === 'ok') {  
                                    datosDetalleKpi = respuesta["datos"]; 
                                }
                                if (respuesta["estado"] === 'ko') {
                                    clsGenerales_.mtdMostrarMensaje(respuesta["mensaje"], "error");
                                }
                                modalDetalle.option({
                                    contentTemplate: function(container) {
                                        $("<div id='gridPopup'>")
                                        .appendTo(container)
                                        .dxDataGrid({
                                            dataSource: datosDetalleKpi,
                                            keyExpr: "id",
                                            showBorders: true,
                                            showColumnLines: true,
                                            columnAutoWidth: true,
                                            showRowLines: true,
                                            noDataText: "No hay datos para mostrar",
                                            paging: { pageSize: 10 },
                                            pager: {
                                                showPageSizeSelector: true,
                                                allowedPageSizes: [10, 30, 50],
                                                showInfo: true
                                            },
                                            columns: [
                                               "fecha","sucursal", "asesor",{ dataField: "id",
                                                    cellTemplate: function (container, options) {
                                                        $("<a>")
                                                            .text(options.data.id)
                                                            .attr("href", "clientes-editar.php?id" + options.data.id) // 🔑 URL dinámica
                                                            .attr("target", "_blank") // abrir en nueva pestaña
                                                            .appendTo(container);
                                                    }   
                                                },  "identificacion", fieldOpcion,
                                            ],
                                            summary: {
                                                totalItems: [{
                                                    column: "fecha",
                                                    summaryType: "count",
                                                    displayFormat: "{0}"
                                                }],
                                                groupItems: [{
                                                    column: "fecha",
                                                    summaryType: "count",
                                                    displayFormat: "{0}",
                                                }]
                                            },
                                        });
                                    }
                                })
                            }); 
                        }

                        if(idbtn == "kpi6" || idbtn == "kpi11") {

                            let condicionSucusalAsesor = " sp.sucp_nombre =  '" + e.cell.rowPath[0] + "' ";

                            if(e.cell.rowPath.length == 2) {
                                condicionSucusalAsesor += " AND u.usr_nombre =  '" + e.cell.rowPath[1] + "' ";
                            }

                            let anno = e.cell.columnPath[0];
                            let condicionFecha = " AND YEAR(cs.cseg_fecha_contacto) = '" + anno + "' ";
                            if(e.cell.columnPath.length == 2) {
                                const quarter = e.cell.columnPath[1];

                                let month = "";
                                if(quarter == "1") { month = anno+"-01','"+anno+"-02','"+anno+"-03'"; }
                                if(quarter == "2") { month = anno+"-04','"+anno+"-05','"+anno+"-06'"; }
                                if(quarter == "3") { month = anno+"-07','"+anno+"-08','"+anno+"-09'"; }
                                if(quarter == "4") { month = anno+"-10','"+anno+"-11','"+anno+"-12'"; }

                                condicionFecha = "  AND DATE_FORMAT(cs.cseg_fecha_contacto, '%Y-%m') IN ('" + month + ") ";
                            }
                            if(e.cell.columnPath.length == 3) {
                                const month = String(e.cell.columnPath[2]).padStart(2, '0');
                                condicionFecha = " AND DATE_FORMAT(cs.cseg_fecha_contacto, '%Y-%m') = '" + e.cell.columnPath[0] + "-" + month + "' ";
                            }

                            clsGenerales_.mtdActivarLoadPagina();

                            let opcionKpi = "consultar_detalleKpi_6_ejecucion_demostraciones";
                            if(idbtn == "kpi11") { opcionKpi = "consultar_detalleKpi_11_numero_visitas_realizadas"; }

                            $.ajax({
                                url: "ajax/ajax-kpis.php",
                                type: "POST",
                                crossDomain: true,
                                dataType: 'json',
                                data: {
                                    e_datos: JSON.stringify([{}]),
                                    opcion: opcionKpi,
                                    condicion: condicionSucusalAsesor + condicionFecha
                                },
                                error: function() {
                                    clsGenerales_.mtdDesactivarLoadPagina();
                                    clsGenerales_.mtdMostrarMensaje("No se pudo completar la solicitud", "error");
                                }
                            }).done((respuesta) => {
                                clsGenerales_.mtdDesactivarLoadPagina();

                                if (respuesta["estado"] === 'ok') {  
                                    datosDetalleKpi = respuesta["datos"]; 
                                }
                                if (respuesta["estado"] === 'ko') {
                                    clsGenerales_.mtdMostrarMensaje(respuesta["mensaje"], "error");
                                }
                                modalDetalle.option({
                                    contentTemplate: function(container) {
                                        $("<div id='gridPopup'>")
                                        .appendTo(container)
                                        .dxDataGrid({
                                            dataSource: datosDetalleKpi,
                                            keyExpr: "id",
                                            showBorders: true,
                                            showColumnLines: true,
                                            columnAutoWidth: true,
                                            showRowLines: true,
                                            noDataText: "No hay datos para mostrar",
                                            paging: { pageSize: 10 },
                                            pager: {
                                                showPageSizeSelector: true,
                                                allowedPageSizes: [10, 30, 50],
                                                showInfo: true
                                            },
                                            columns: [
                                                { dataField: "id", visible: false },{ dataField: "seguimiento",
                                                    cellTemplate: function (container, options) {
                                                        $("<a>")
                                                            .text(options.data.seguimiento)
                                                            .attr("href", "clientes-seguimiento.php?busqueda=" + options.data.seguimiento) // 🔑 URL dinámica
                                                            .attr("target", "_blank") // abrir en nueva pestaña
                                                            .appendTo(container);
                                                    }   
                                                }, "fecha","sucursal", "asesor", "identificacion", "cliente",
                                            ],
                                            summary: {
                                                totalItems: [{
                                                    column: "fecha",
                                                    summaryType: "count",
                                                    displayFormat: "{0}"
                                                }],
                                                groupItems: [{
                                                    column: "fecha",
                                                    summaryType: "count",
                                                    displayFormat: "{0}",
                                                }]
                                            },
                                        });
                                    }
                                })
                            }); 
                        }

                        if(idbtn == "kpi7") {

                            let condicionSucusalAsesor = " sucp_nombre =  '" + e.cell.rowPath[0] + "' ";

                            if(e.cell.rowPath.length == 2) {
                                condicionSucusalAsesor += " AND usr_nombre =  '" + e.cell.rowPath[1] + "' ";
                            }

                            let anno = e.cell.columnPath[0];
                            let condicionFecha = " AND YEAR(cseg_fecha_reporte) = '" + anno + "' ";
                            if(e.cell.columnPath.length == 2) {
                                const quarter = e.cell.columnPath[1];

                                let month = "";
                                if(quarter == "1") { month = anno+"-01','"+anno+"-02','"+anno+"-03'"; }
                                if(quarter == "2") { month = anno+"-04','"+anno+"-05','"+anno+"-06'"; }
                                if(quarter == "3") { month = anno+"-07','"+anno+"-08','"+anno+"-09'"; }
                                if(quarter == "4") { month = anno+"-10','"+anno+"-11','"+anno+"-12'"; }

                                condicionFecha = "  AND DATE_FORMAT(cseg_fecha_reporte, '%Y-%m') IN ('" + month + ") ";
                            }
                            if(e.cell.columnPath.length == 3) {
                                const month = String(e.cell.columnPath[2]).padStart(2, '0');
                                condicionFecha = " AND DATE_FORMAT(cseg_fecha_reporte, '%Y-%m') = '" + e.cell.columnPath[0] + "-" + month + "' ";
                            }

                            clsGenerales_.mtdActivarLoadPagina();

                            let opcionKpi = "consultar_detalleKpi_7_numero_llamadas_enviadas_ejecutivo_prospeccion";

                            $.ajax({
                                url: "ajax/ajax-kpis.php",
                                type: "POST",
                                crossDomain: true,
                                dataType: 'json',
                                data: {
                                    e_datos: JSON.stringify([{}]),
                                    opcion: opcionKpi,
                                    condicion: condicionSucusalAsesor + condicionFecha
                                },
                                error: function() {
                                    clsGenerales_.mtdDesactivarLoadPagina();
                                    clsGenerales_.mtdMostrarMensaje("No se pudo completar la solicitud", "error");
                                }
                            }).done((respuesta) => {
                                clsGenerales_.mtdDesactivarLoadPagina();

                                if (respuesta["estado"] === 'ok') {  
                                    datosDetalleKpi = respuesta["datos"]; 
                                }
                                if (respuesta["estado"] === 'ko') {
                                    clsGenerales_.mtdMostrarMensaje(respuesta["mensaje"], "error");
                                }
                                modalDetalle.option({
                                    contentTemplate: function(container) {
                                        $("<div id='gridPopup'>")
                                        .appendTo(container)
                                        .dxDataGrid({
                                            dataSource: datosDetalleKpi,
                                            keyExpr: "id",
                                            showBorders: true,
                                            showColumnLines: true,
                                            columnAutoWidth: true,
                                            showRowLines: true,
                                            noDataText: "No hay datos para mostrar",
                                            paging: { pageSize: 10 },
                                            pager: {
                                                showPageSizeSelector: true,
                                                allowedPageSizes: [10, 30, 50],
                                                showInfo: true
                                            },
                                            columns: [
                                                { dataField: "id", visible: false },{ dataField: "seguimiento",
                                                    cellTemplate: function (container, options) {
                                                        $("<a>")
                                                            .text(options.data.seguimiento)
                                                            .attr("href", "clientes-seguimiento.php?busqueda=" + options.data.seguimiento) // 🔑 URL dinámica
                                                            .attr("target", "_blank") // abrir en nueva pestaña
                                                            .appendTo(container);
                                                    }   
                                                }, "fecha","sucursal", "asesor", "identificacion", "cliente",
                                            ],
                                            summary: {
                                                totalItems: [{
                                                    column: "fecha",
                                                    summaryType: "count",
                                                    displayFormat: "{0}"
                                                }],
                                                groupItems: [{
                                                    column: "fecha",
                                                    summaryType: "count",
                                                    displayFormat: "{0}",
                                                }]
                                            },
                                        });
                                    }
                                })
                            }); 
                        }

                        if(idbtn == "kpi8") {

                            clsGenerales_.mtdActivarLoadPagina();

                            let opcionKpi = "consultar_detalleKpi_8_clientes_efectivos_por_evento_generados";
                            let fieldOpcion = "generados";
                            let condicionSucusalAsesor = " s.sucp_nombre =  '" + e.cell.rowPath[0] + "' ";

                            if(e.cell.rowPath.length == 2) {
                                condicionSucusalAsesor += " AND u.usr_nombre =  '" + e.cell.rowPath[1] + "' ";
                            }

                            let anno = e.cell.columnPath[0];
                            let condicionFecha = " AND YEAR(c.cli_fecha_registro) = '" + anno + "' ";
                            if(e.cell.columnPath.length == 2) {
                                const quarter = e.cell.columnPath[1];

                                let month = "";
                                if(quarter == "1") { month = anno+"-01','"+anno+"-02','"+anno+"-03'"; }
                                if(quarter == "2") { month = anno+"-04','"+anno+"-05','"+anno+"-06'"; }
                                if(quarter == "3") { month = anno+"-07','"+anno+"-08','"+anno+"-09'"; }
                                if(quarter == "4") { month = anno+"-10','"+anno+"-11','"+anno+"-12'"; }

                                condicionFecha = "  AND DATE_FORMAT(c.cli_fecha_registro, '%Y-%m') IN ('" + month + ") ";
                            }
                            if(e.cell.columnPath.length == 3) {
                                let month = String(e.cell.columnPath[2]).padStart(2, '0');
                                condicionFecha = " AND DATE_FORMAT(c.cli_fecha_registro, '%Y-%m') = '" + e.cell.columnPath[0] + "-" + month + "' ";
                            }

                            
                            if(e.cell.dataIndex == 0) { 
                                fieldOpcion = "ganados";
                                opcionKpi = "consultar_detalleKpi_8_clientes_efectivos_por_evento_ganados"; 

                                condicionSucusalAsesor = " s.sucp_nombre =  '" + e.cell.rowPath[0] + "' ";

                                if(e.cell.rowPath.length == 2) {
                                    condicionSucusalAsesor += " AND u.usr_nombre =  '" + e.cell.rowPath[1] + "' ";
                                }

                                anno = e.cell.columnPath[0];
                                condicionFecha = " AND YEAR(c.cli_fecha_ingreso) = '" + anno + "' ";
                                if(e.cell.columnPath.length == 2) {
                                    const quarter = e.cell.columnPath[1];

                                    let month = "";
                                    if(quarter == "1") { month = anno+"-01','"+anno+"-02','"+anno+"-03'"; }
                                    if(quarter == "2") { month = anno+"-04','"+anno+"-05','"+anno+"-06'"; }
                                    if(quarter == "3") { month = anno+"-07','"+anno+"-08','"+anno+"-09'"; }
                                    if(quarter == "4") { month = anno+"-10','"+anno+"-11','"+anno+"-12'"; }

                                    condicionFecha = "  AND DATE_FORMAT(c.cli_fecha_ingreso, '%Y-%m') IN ('" + month + ") ";
                                }
                                if(e.cell.columnPath.length == 3) {
                                    let month = String(e.cell.columnPath[2]).padStart(2, '0');
                                    condicionFecha = " AND DATE_FORMAT(c.cli_fecha_ingreso, '%Y-%m') = '" + e.cell.columnPath[0] + "-" + month + "' ";
                                }                            
                            }

                            $.ajax({
                                url: "ajax/ajax-kpis.php",
                                type: "POST",
                                crossDomain: true,
                                dataType: 'json',
                                data: {
                                    e_datos: JSON.stringify([{}]),
                                    opcion: opcionKpi,
                                    condicion: condicionSucusalAsesor + condicionFecha
                                },
                                error: function() {
                                    clsGenerales_.mtdDesactivarLoadPagina();
                                    clsGenerales_.mtdMostrarMensaje("No se pudo completar la solicitud", "error");
                                }
                            }).done((respuesta) => {
                                clsGenerales_.mtdDesactivarLoadPagina();

                                if (respuesta["estado"] === 'ok') {  
                                    datosDetalleKpi = respuesta["datos"]; 
                                }
                                if (respuesta["estado"] === 'ko') {
                                    clsGenerales_.mtdMostrarMensaje(respuesta["mensaje"], "error");
                                }
                                modalDetalle.option({
                                    contentTemplate: function(container) {
                                        $("<div id='gridPopup'>")
                                        .appendTo(container)
                                        .dxDataGrid({
                                            dataSource: datosDetalleKpi,
                                            keyExpr: "id",
                                            showBorders: true,
                                            showColumnLines: true,
                                            columnAutoWidth: true,
                                            showRowLines: true,
                                            noDataText: "No hay datos para mostrar",
                                            paging: { pageSize: 10 },
                                            pager: {
                                                showPageSizeSelector: true,
                                                allowedPageSizes: [10, 30, 50],
                                                showInfo: true
                                            },
                                            columns: [
                                               "fecha","sucursal", "asesor",{ dataField: "id",
                                                    cellTemplate: function (container, options) {
                                                        $("<a>")
                                                            .text(options.data.id)
                                                            .attr("href", "clientes-editar.php?id=" + options.data.id) // 🔑 URL dinámica
                                                            .attr("target", "_blank") // abrir en nueva pestaña
                                                            .appendTo(container);
                                                    }   
                                                },  "evento","identificacion", fieldOpcion,
                                            ],
                                            summary: {
                                                totalItems: [{
                                                    column: "fecha",
                                                    summaryType: "count",
                                                    displayFormat: "{0}"
                                                }],
                                                groupItems: [{
                                                    column: "fecha",
                                                    summaryType: "count",
                                                    displayFormat: "{0}",
                                                }]
                                            },
                                        });
                                    }
                                })
                            }); 
                        }

                        if(idbtn == "kpi9") {

                            clsGenerales_.mtdActivarLoadPagina();

                            let opcionKpi = "consultar_detalleKpi_9_nuevos_subdistribuidores";
                            let fieldOpcion = "Actuales";
                            let condicionSucusalAsesor = " sucp_nombre =  '" + e.cell.rowPath[0] + "' ";

                            if(e.cell.rowPath.length == 2) {
                                condicionSucusalAsesor += " AND usr_nombre =  '" + e.cell.rowPath[1] + "' ";
                            }

                            let anno = e.cell.columnPath[0];
                            let condicionFecha = " AND YEAR(cli_fecha_registro) > '" + anno + "' ";
                            if(e.cell.columnPath.length == 2) {
                                const quarter = e.cell.columnPath[1];

                                let month = "";
                                if(quarter == "1") { month = anno+"-01','"+anno+"-02','"+anno+"-03'"; }
                                if(quarter == "2") { month = anno+"-04','"+anno+"-05','"+anno+"-06'"; }
                                if(quarter == "3") { month = anno+"-07','"+anno+"-08','"+anno+"-09'"; }
                                if(quarter == "4") { month = anno+"-10','"+anno+"-11','"+anno+"-12'"; }

                                condicionFecha = "  AND DATE_FORMAT(cli_fecha_registro, '%Y-%m') IN ('" + month + ") ";
                            }
                            if(e.cell.columnPath.length == 3) {
                                let month = String(e.cell.columnPath[2]).padStart(2, '0');
                                condicionFecha = " AND DATE_FORMAT(cli_fecha_registro, '%Y%m') < " + e.cell.columnPath[0] + "" + month + " ";
                            }

                            
                            if(e.cell.dataIndex == 0) { 
                                fieldOpcion = "Nuevos";

                                condicionSucusalAsesor = " sucp_nombre =  '" + e.cell.rowPath[0] + "' ";

                                if(e.cell.rowPath.length == 2) {
                                    condicionSucusalAsesor += " AND usr_nombre =  '" + e.cell.rowPath[1] + "' ";
                                }

                                anno = e.cell.columnPath[0];
                                condicionFecha = " AND YEAR(cli_fecha_registro) = '" + anno + "' ";
                                if(e.cell.columnPath.length == 2) {
                                    const quarter = e.cell.columnPath[1];

                                    let month = "";
                                    if(quarter == "1") { month = anno+"-01','"+anno+"-02','"+anno+"-03'"; }
                                    if(quarter == "2") { month = anno+"-04','"+anno+"-05','"+anno+"-06'"; }
                                    if(quarter == "3") { month = anno+"-07','"+anno+"-08','"+anno+"-09'"; }
                                    if(quarter == "4") { month = anno+"-10','"+anno+"-11','"+anno+"-12'"; }

                                    condicionFecha = "  AND DATE_FORMAT(cli_fecha_registro, '%Y-%m') IN ('" + month + ") ";
                                }
                                if(e.cell.columnPath.length == 3) {
                                    let month = String(e.cell.columnPath[2]).padStart(2, '0');
                                    condicionFecha = " AND DATE_FORMAT(cli_fecha_registro, '%Y-%m') = '" + e.cell.columnPath[0] + "-" + month + "' ";
                                }                            
                            }

                            $.ajax({
                                url: "ajax/ajax-kpis.php",
                                type: "POST",
                                crossDomain: true,
                                dataType: 'json',
                                data: {
                                    e_datos: JSON.stringify([{}]),
                                    opcion: opcionKpi,
                                    condicion: condicionSucusalAsesor + condicionFecha
                                },
                                error: function() {
                                    clsGenerales_.mtdDesactivarLoadPagina();
                                    clsGenerales_.mtdMostrarMensaje("No se pudo completar la solicitud", "error");
                                }
                            }).done((respuesta) => {
                                clsGenerales_.mtdDesactivarLoadPagina();

                                if (respuesta["estado"] === 'ok') {  
                                    datosDetalleKpi = respuesta["datos"]; 
                                }
                                if (respuesta["estado"] === 'ko') {
                                    clsGenerales_.mtdMostrarMensaje(respuesta["mensaje"], "error");
                                }
                                modalDetalle.option({
                                    contentTemplate: function(container) {
                                        $("<div id='gridPopup'>")
                                        .appendTo(container)
                                        .dxDataGrid({
                                            dataSource: datosDetalleKpi,
                                            keyExpr: "id",
                                            showBorders: true,
                                            showColumnLines: true,
                                            columnAutoWidth: true,
                                            showRowLines: true,
                                            noDataText: "No hay datos para mostrar",
                                            paging: { pageSize: 10 },
                                            pager: {
                                                showPageSizeSelector: true,
                                                allowedPageSizes: [10, 30, 50],
                                                showInfo: true
                                            },
                                            columns: [
                                               "fecha","sucursal", "asesor",{ dataField: "id",
                                                    cellTemplate: function (container, options) {
                                                        $("<a>")
                                                            .text(options.data.id)
                                                            .attr("href", "clientes-editar.php?id=" + options.data.id) // 🔑 URL dinámica
                                                            .attr("target", "_blank") // abrir en nueva pestaña
                                                            .appendTo(container);
                                                    }   
                                                },"identificacion", {
                                                    caption: fieldOpcion,
                                                    dataField: 'cliente'
                                                }
                                            ],
                                            summary: {
                                                totalItems: [{
                                                    column: "fecha",
                                                    summaryType: "count",
                                                    displayFormat: "{0}"
                                                }],
                                                groupItems: [{
                                                    column: "fecha",
                                                    summaryType: "count",
                                                    displayFormat: "{0}",
                                                }]
                                            },
                                        });
                                    }
                                })
                            }); 
                        }

                        if(idbtn == "kpi10") {

                            clsGenerales_.mtdActivarLoadPagina();

                            let opcionKpi = "consultar_detalleKpi_10_captacion_clientes_instituciones";
                            let fieldOpcion = "Actuales";
                            let condicionSucusalAsesor = " sucp_nombre =  '" + e.cell.rowPath[0] + "' ";

                            if(e.cell.rowPath.length == 2) {
                                condicionSucusalAsesor += " AND usr_nombre =  '" + e.cell.rowPath[1] + "' ";
                            }

                            let anno = e.cell.columnPath[0];
                            let condicionFecha = " AND YEAR(cli_fecha_registro) > '" + anno + "' ";
                            if(e.cell.columnPath.length == 2) {
                                const quarter = e.cell.columnPath[1];

                                let month = "";
                                if(quarter == "1") { month = anno+"-01','"+anno+"-02','"+anno+"-03'"; }
                                if(quarter == "2") { month = anno+"-04','"+anno+"-05','"+anno+"-06'"; }
                                if(quarter == "3") { month = anno+"-07','"+anno+"-08','"+anno+"-09'"; }
                                if(quarter == "4") { month = anno+"-10','"+anno+"-11','"+anno+"-12'"; }

                                condicionFecha = "  AND DATE_FORMAT(cli_fecha_registro, '%Y-%m') IN ('" + month + ") ";
                            }
                            if(e.cell.columnPath.length == 3) {
                                let month = String(e.cell.columnPath[2]).padStart(2, '0');
                                condicionFecha = " AND DATE_FORMAT(cli_fecha_registro, '%Y%m') < " + e.cell.columnPath[0] + "" + month + " ";
                            }

                            
                            if(e.cell.dataIndex == 0) { 
                                fieldOpcion = "Nuevos";

                                condicionSucusalAsesor = " sucp_nombre =  '" + e.cell.rowPath[0] + "' ";

                                if(e.cell.rowPath.length == 2) {
                                    condicionSucusalAsesor += " AND usr_nombre =  '" + e.cell.rowPath[1] + "' ";
                                }

                                anno = e.cell.columnPath[0];
                                condicionFecha = " AND YEAR(cli_fecha_registro) = '" + anno + "' ";
                                if(e.cell.columnPath.length == 2) {
                                    const quarter = e.cell.columnPath[1];

                                    let month = "";
                                    if(quarter == "1") { month = anno+"-01','"+anno+"-02','"+anno+"-03'"; }
                                    if(quarter == "2") { month = anno+"-04','"+anno+"-05','"+anno+"-06'"; }
                                    if(quarter == "3") { month = anno+"-07','"+anno+"-08','"+anno+"-09'"; }
                                    if(quarter == "4") { month = anno+"-10','"+anno+"-11','"+anno+"-12'"; }

                                    condicionFecha = "  AND DATE_FORMAT(cli_fecha_registro, '%Y-%m') IN ('" + month + ") ";
                                }
                                if(e.cell.columnPath.length == 3) {
                                    let month = String(e.cell.columnPath[2]).padStart(2, '0');
                                    condicionFecha = " AND DATE_FORMAT(cli_fecha_registro, '%Y-%m') = '" + e.cell.columnPath[0] + "-" + month + "' ";
                                }                            
                            }

                            $.ajax({
                                url: "ajax/ajax-kpis.php",
                                type: "POST",
                                crossDomain: true,
                                dataType: 'json',
                                data: {
                                    e_datos: JSON.stringify([{}]),
                                    opcion: opcionKpi,
                                    condicion: condicionSucusalAsesor + condicionFecha
                                },
                                error: function() {
                                    clsGenerales_.mtdDesactivarLoadPagina();
                                    clsGenerales_.mtdMostrarMensaje("No se pudo completar la solicitud", "error");
                                }
                            }).done((respuesta) => {
                                clsGenerales_.mtdDesactivarLoadPagina();

                                if (respuesta["estado"] === 'ok') {  
                                    datosDetalleKpi = respuesta["datos"]; 
                                }
                                if (respuesta["estado"] === 'ko') {
                                    clsGenerales_.mtdMostrarMensaje(respuesta["mensaje"], "error");
                                }
                                modalDetalle.option({
                                    contentTemplate: function(container) {
                                        $("<div id='gridPopup'>")
                                        .appendTo(container)
                                        .dxDataGrid({
                                            dataSource: datosDetalleKpi,
                                            keyExpr: "id",
                                            showBorders: true,
                                            showColumnLines: true,
                                            columnAutoWidth: true,
                                            showRowLines: true,
                                            noDataText: "No hay datos para mostrar",
                                            paging: { pageSize: 10 },
                                            pager: {
                                                showPageSizeSelector: true,
                                                allowedPageSizes: [10, 30, 50],
                                                showInfo: true
                                            },
                                            columns: [
                                               "fecha","sucursal", "asesor",{ dataField: "id",
                                                    cellTemplate: function (container, options) {
                                                        $("<a>")
                                                            .text(options.data.id)
                                                            .attr("href", "clientes-editar.php?id=" + options.data.id) // 🔑 URL dinámica
                                                            .attr("target", "_blank") // abrir en nueva pestaña
                                                            .appendTo(container);
                                                    }   
                                                },"identificacion", {
                                                    caption: fieldOpcion,
                                                    dataField: 'cliente'
                                                }
                                            ],
                                            summary: {
                                                totalItems: [{
                                                    column: "fecha",
                                                    summaryType: "count",
                                                    displayFormat: "{0}"
                                                }],
                                                groupItems: [{
                                                    column: "fecha",
                                                    summaryType: "count",
                                                    displayFormat: "{0}",
                                                }]
                                            },
                                        });
                                    }
                                })
                            }); 
                        }
                        
                        modalDetalle.show();
                    }
                }
            });  

        }); 
               
    }

    const exportHeaderOptions = {
        exportRowFieldHeaders: true,
        exportColumnFieldHeaders: true,
        exportDataFieldHeaders: true,
        exportFilterFieldHeaders: true,
    };

    grdDatos.option({                
        onExporting(e) {
            
            if (divEncabezadoPki.innerText == "SELECCIONA UN KPI") {
                clsGenerales_.mtdMostrarMensaje("No hay datos para exportar", "warning");
                return;                
            }

            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet(divEncabezadoPki.innerText);


            DevExpress.excelExporter.exportPivotGrid({
                component: e.component,
                worksheet,
                topLeftCell: { row: 4, column: 1 },
                ...exportHeaderOptions
            }).then((cellRange) => {
                // Header
                const headerRow = worksheet.getRow(2);
                headerRow.height = 30;

                const columnFromIndex = worksheet.views[0].xSplit + 1;
                const columnToIndex = columnFromIndex + 3;
                worksheet.mergeCells(2, columnFromIndex, 2, columnToIndex);

                const headerCell = headerRow.getCell(columnFromIndex);
                headerCell.value = divEncabezadoPki.innerText;
                headerCell.font = { name: 'Segoe UI Light', size: 22, bold: true };
                headerCell.alignment = { horizontal: 'left', vertical: 'middle', wrapText: true };

                // Footer
                const footerRowIndex = cellRange.to.row + 2;
                const footerCell = worksheet.getRow(footerRowIndex).getCell(cellRange.to.column);
                footerCell.value = 'Datos a la fecha ' + clsGenerales_.fnFechaHoraActual();
                footerCell.font = { color: { argb: 'BFBFBF' }, italic: true };
                footerCell.alignment = { horizontal: 'right' };
            }).then(() => {
                workbook.xlsx.writeBuffer().then((buffer) => {
                saveAs(new Blob([buffer], { type: 'application/octet-stream' }), divEncabezadoPki.innerText + '_'  + clsGenerales_.fnFechaHoraActual() +'.xlsx');
                });
            });
        }
    });
    

    grdDatosChart.option({        
        export: {
            enabled: true
        },
        title: "",
        size: {
            height: 500,
        },
        adaptiveLayout: {
            width: 450,
        },
        onExporting(e){

            if (divEncabezadoPki.innerText == "SELECCIONA UN KPI") {
                clsGenerales_.mtdMostrarMensaje("No hay datos para exportar", "warning");
                return;                
            }

            e.fileName = divEncabezadoPki.innerText + '_'  + clsGenerales_.fnFechaHoraActual();
        }
    }); 

});

