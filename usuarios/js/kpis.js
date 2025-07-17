import { clsGenerales } from './_generales.js';
document.addEventListener('DOMContentLoaded', () => {

    const clsGenerales_ = new clsGenerales();btnKpiClic

    const divEncabezadoPki = document.getElementById("divEncabezadoPki");
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
    const kpi14 = document.getElementById("kpi14");

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
    kpi14.addEventListener('click', btnKpiClic);


    function btnKpiClic(e) {
        e.preventDefault();

        clsGenerales_.mtdActivarLoadPagina();
        let txtOpcion = "consultar_kpi_ventas";

        if (this.id == "kpi1") { txtOpcion = "consultar_kpi_1_2_ventas"; };
        if (this.id == "kpi2") { txtOpcion = "consultar_kpi_1_2_ventas"; };
        if (this.id == "kpi3") { txtOpcion = "consultar_kpi_3_tiempo_promedio_cierre_ventas"; };
        if (this.id == "kpi7") { txtOpcion = "consultar_kpi_7_numero_llamadas_enviadas_ejecutivo_prospeccion"; };

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

            clsGenerales_.mtdDesactivarLoadPagina();
            divEncabezadoPki.innerText = this.innerText;
            if (respuesta["estado"] === 'ok') {
               
                let datosKpi = [];
                grdDatos.option({dataSource: {store: datosKpi}});
                datosKpi = respuesta["datos"]; // respuesta["datos"]; dataRespuestaKPI[0]["datos"];
                if (this.id == "kpi1") {

                    grdDatosChart.option({
                        tooltip: {
                            enabled: true,
                            customizeTooltip(args) {                               
                                const valueText = args.originalValue;                        
                                return {html: `${args.seriesName}<div class='currency'>${valueText}</div>` };
                            },
                        },
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
                            }, {
                                caption: 'Prom',
                                dataField: 'total',
                                dataType: 'number',
                                format: 'currency',
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

                    grdDatosChart.option({
                        tooltip: {
                            enabled: true,
                            customizeTooltip(args) {                               
                                let valueText = args.originalValue;
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

                    grdDatosChart.option({
                        tooltip: {
                            enabled: true,
                            customizeTooltip(args) {                               
                                let valueText = args.originalValue;
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
                if (this.id == "kpi5") {

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
                if (this.id == "kpi6") {

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
                if (this.id == "kpi7") {

                    grdDatosChart.option({
                        tooltip: {
                            enabled: true,
                            customizeTooltip(args) {                               
                                let valueText = args.originalValue;  
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
                                caption: 'Ejecutivo',
                                dataField: 'ejecutivo',
                                area: 'row',
                                sortOrder: 'asc'
                            },{
                                width: 150,
                                caption: 'Cliente',
                                dataField: 'cliente',
                                area: 'row',
                                sortOrder: 'asc'
                            },{
                                caption: 'Seguimiento',
                                dataField: 'seguimiento',
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
                if (this.id == "kpi8") {

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
                if (this.id == "kpi9") {

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
                if (this.id == "kpi10") {

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
                if (this.id == "kpi11") {

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
                if (this.id == "kpi14") {

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
                

            }
            if (respuesta["estado"] === 'ko') {
                clsGenerales_.mtdMostrarMensaje(respuesta["mensaje"], "error");
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

