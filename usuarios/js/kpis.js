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
    const kpi12 = document.getElementById("kpi12");
    const kpi13 = document.getElementById("kpi13");

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
    kpi12.addEventListener('click', btnKpiClic);
    kpi13.addEventListener('click', btnKpiClic);


    function btnKpiClic(e) {
        e.preventDefault();

        clsGenerales_.mtdActivarLoadPagina();
        let txtOpcion = "consultar_kpi_ventas";

        if (this.id == "kpi1") { txtOpcion = "consultar_kpi_1_2_ventas"; };
        if (this.id == "kpi2") { txtOpcion = "consultar_kpi_1_2_ventas"; };
        if (this.id == "kpi3") { txtOpcion = "consultar_kpi_3_tiempo_promedio_cierre_ventas"; };
        if (this.id == "kpi4") { txtOpcion = "consultar_kpi_4_cumplimiento_cuota_comercial"; };
        if (this.id == "kpi5") { txtOpcion = "consultar_kpi_5_tasa_conversión_prospecto_cliente"; };
        if (this.id == "kpi6") { txtOpcion = "consultar_kpi_6_ejecucion_demostraciones"; };
        if (this.id == "kpi7") { txtOpcion = "consultar_kpi_7_numero_llamadas_enviadas_ejecutivo_prospeccion"; };
        if (this.id == "kpi8") { txtOpcion = "consultar_kpi_8_clientes_efectivos_por_evento"; };
        if (this.id == "kpi9") { txtOpcion = "consultar_kpi_9_nuevos_subdistribuidores"; };
        if (this.id == "kpi10") { txtOpcion = "consultar_kpi_10_captacion_clientes_instituciones"; };
        if (this.id == "kpi11") { txtOpcion = "consultar_kpi_11_numero_visitas_realizadas"; };

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

            if (this.id == "kpi1") {

                divDescripcionPki.innerHTML = "Cantidad: Número de facturas emitidas.";

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
                                caption: 'Vendedor',
                                dataField: 'vendedor',
                                area: 'row',
                                sortOrder: 'asc'
                            },{
                                width: 150,
                                caption: 'Cliente',
                                dataField: 'cliente',
                                area: 'row',
                                sortOrder: 'asc'
                            },{
                                caption: 'Factura',
                                dataField: 'factura',
                                area: 'row'
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
                if (this.id == "kpi2") {     

                    divDescripcionPki.innerHTML = "Cantidad: Número de facturas emitidas. Total: Suma del valor total de las facturas. Prom: Promedio del valor total de las facturas.";

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
                                if (!args.seriesName.includes("Cantidad")) {
                                   valueText = new Intl.NumberFormat('en-EN', { style: 'currency', currency: 'USD' }).format(args.originalValue); 
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
                            caption: 'Vendedor',
                            dataField: 'vendedor',
                            area: 'row',
                            sortOrder: 'asc'
                            },{
                            width: 150,
                            caption: 'Cliente',
                            dataField: 'cliente',
                            area: 'row',
                            sortOrder: 'asc'
                            },{
                            caption: 'Factura',
                            dataField: 'factura',
                            area: 'row'
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
                if (this.id == "kpi3") {

                    divDescripcionPki.innerHTML = "Cantidad: Número de facturas emitidas. Duracion: Suma del número de días que tardó en cerrarse las ventas. Prom: Promedio del número de días que tardó en cerrarse una venta.";

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
                                caption: 'Vendedor',
                                dataField: 'vendedor',
                                area: 'row',
                                sortOrder: 'asc'
                            },{
                                width: 150,
                                caption: 'Cliente',
                                dataField: 'cliente',
                                area: 'row',
                                sortOrder: 'asc'
                            },{
                                caption: 'Factura',
                                dataField: 'factura',
                                area: 'row'
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
                                dataField: 'duracion_cierre_dias',
                                dataType: 'number',
                                summaryType: 'sum',
                                area: 'data'
                            },{
                                caption: 'Prom',
                                dataField: 'duracion_cierre_dias',
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
                if (this.id == "kpi4") {    

                    divDescripcionPki.innerHTML = "Ventas: Número de ventas realizadas. Meta: Número de ventas planificadas. Tasa: Porcentaje de cumplimiento de la meta.";

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
                                caption: 'Vendedor',
                                dataField: 'vendedor',
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
                                dataField: "ventas",
                                dataType: 'number',
                                caption: 'Ventas',
                                area: 'data'
                            },{
                                caption: "Meta",
                                dataField: "meta_ventas",
                                dataType: 'number',
                                area: "data",
                                summaryType: "sum"
                            },{                                
                                caption: "Tasa",
                                dataType: 'number',
                                area: 'data',
                                format: '#0.00\'%\'',
                                calculateSummaryValue: function(summaryCell) {
                                    
                                    if (summaryCell.value("Meta") === undefined) {
                                        return null;
                                    }

                                    var value = 0;
                                    value = summaryCell.value("Meta") > 0 ? Number((summaryCell.value("Ventas") / summaryCell.value("Meta"))*100) : 0;
                                    return value;
                                }
                            }],
                            store: datosKpi
                        }
                    });

                }
                if (this.id == "kpi5") {

                    divDescripcionPki.innerHTML = "Clientes: Número de clientes adquiridos. Prospectos: Número de prospectos atendidos. Tasa: Porcentaje de conversión de prospectos a clientes.";
                   
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
                                caption: 'Vendedor',
                                dataField: 'vendedor',
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
                if (this.id == "kpi6") {

                    divDescripcionPki.innerHTML = "Demostraciones: Número de demostraciones realizadas. Meta: Número de demostraciones planificadas. Tasa: Porcentaje de cumplimiento de la meta.";

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
                                caption: 'Vendedor',
                                dataField: 'vendedor',
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
                                dataField: "demostraciones",
                                dataType: 'number',
                                caption: 'Demostraciones',
                                area: 'data'
                            },{
                                caption: "Meta",
                                dataField: "meta_demostraciones",
                                dataType: 'number',
                                area: "data",
                                summaryType: "sum"
                            },{                                
                                caption: "Tasa",
                                dataType: 'number',
                                area: 'data',                                
                                format: '#0.00\'%\'',
                                calculateSummaryValue: function(summaryCell) {
                                    
                                    if (summaryCell.value("Demostraciones") === undefined) {
                                        return undefined;
                                    }

                                    var value = 0;
                                    value = summaryCell.value("Demostraciones") > 0 ? Number((summaryCell.value("Demostraciones") / summaryCell.value("Meta"))*100): 0;
                                    return value;Meta
                                }
                            }],
                            store: datosKpi
                        }
                    });

                }
                if (this.id == "kpi7") {

                    divDescripcionPki.innerHTML = "Cantidad: Número de llamadas realizadas.";

                    grdDatosChart.option({
                        commonSeriesSettings: {
                            type: 'spline',
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
                                caption: 'Responsable',
                                dataField: 'responsable',
                                area: 'row',
                                sortOrder: 'asc'
                            },{
                                width: 150,
                                caption: 'Prospecto',
                                dataField: 'prospecto',
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
                if (this.id == "kpi8") {

                    divDescripcionPki.innerHTML = "Ganados: Número de clientes adquiridos. Generados: Número de clientes atendidos. Tasa: Porcentaje de conversión a clientes.";

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
                                caption: 'Vendedor',
                                dataField: 'vendedor',
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
                if (this.id == "kpi9") {

                    divDescripcionPki.innerHTML = "Nuevos: Número de clientes nuevos. Actuales: Número de clientes actuales. Tasa: Porcentaje de clientes nuevos sobre el total de clientes.";

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
                                caption: 'Vendedor',
                                dataField: 'vendedor',
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
                if (this.id == "kpi10") {

                    divDescripcionPki.innerHTML = "Nuevos: Número de clientes nuevos. Actuales: Número de clientes actuales. Tasa: Porcentaje de clientes nuevos sobre el total de clientes.";

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
                                caption: 'Vendedor',
                                dataField: 'vendedor',
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
                if (this.id == "kpi11") {

                    divDescripcionPki.innerHTML = "Ejecutada: Número de visitas ejecutadas. Programada: Número de visitas programadas. Tasa: Porcentaje de cumplimiento de la meta.";

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
                                caption: 'Responsable',
                                dataField: 'responsable',
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
                                dataField: "visita_ejecutada",
                                dataType: 'number',
                                caption: 'Ejecutada',
                                area: 'data'
                            },{
                                caption: "Planeada",
                                dataField: "visita_planeada",
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
                if (this.id == "kpi12") {

                    grdDatosChart.option({
                        tooltip: {
                            enabled: true,
                            customizeTooltip(args) {                               
                                let valueText = args.originalValue;  
                                if (!args.seriesName.includes("Cantidad")) {
                                   valueText = new Intl.NumberFormat('en-EN', { style: 'currency', currency: 'USD' }).format(args.originalValue); 
                                } 
                                return {html: `${args.seriesName}<div class='currency'>${valueText}</div>` };
                            },
                        }
                    });

                    grdDatos.option({
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
                                caption: 'Vendedor',
                                dataField: 'vendedor',
                                area: 'row',
                                sortOrder: 'asc'
                            },{
                                width: 150,
                                caption: 'Cliente',
                                dataField: 'cliente',
                                area: 'row',
                                sortOrder: 'asc'
                            },{
                                caption: 'Factura',
                                dataField: 'factura',
                                area: 'row'
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
                            },{
                                caption: 'Total',
                                dataField: 'total',
                                dataType: 'number',
                                summaryType: 'sum',
                                format: 'currency',
                                area: 'data'
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
                if (this.id == "kpi13") {

                    grdDatosChart.option({
                        tooltip: {
                            enabled: true,
                            customizeTooltip(args) {                               
                                let valueText = args.originalValue;  
                                if (!args.seriesName.includes("Cantidad")) {
                                   valueText = new Intl.NumberFormat('en-EN', { style: 'currency', currency: 'USD' }).format(args.originalValue); 
                                } 
                                return {html: `${args.seriesName}<div class='currency'>${valueText}</div>` };
                            },
                        }
                    });

                    grdDatos.option({
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
                                caption: 'Vendedor',
                                dataField: 'vendedor',
                                area: 'row',
                                sortOrder: 'asc'
                            },{
                                width: 150,
                                caption: 'Cliente',
                                dataField: 'cliente',
                                area: 'row',
                                sortOrder: 'asc'
                            },{
                                caption: 'Factura',
                                dataField: 'factura',
                                area: 'row'
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
                            },{
                                caption: 'Total',
                                dataField: 'total',
                                dataType: 'number',
                                summaryType: 'sum',
                                format: 'currency',
                                area: 'data'
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

