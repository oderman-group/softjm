import { clsGenerales } from './_generales.js';
document.addEventListener('DOMContentLoaded', () => {

    const clsGenerales_ = new clsGenerales();btnKpiClic

    const divEncabezadoPki = document.getElementById("divEncabezadoPki");
    const kpi1 = document.getElementById("kpi1");
    const kpi2 = document.getElementById("kpi2");
    const kpi3 = document.getElementById("kpi3");

    const grdDatos = $('#grdDatos').dxPivotGrid({}).dxPivotGrid('instance');
    const grdDatosChart = $('#grdDatosChart').dxChart({}).dxChart('instance');

    

    kpi1.addEventListener('click', btnKpiClic);
    kpi2.addEventListener('click', btnKpiClic);
    kpi3.addEventListener('click', btnKpiClic);

    function btnKpiClic(e) {
        e.preventDefault();

        clsGenerales_.mtdActivarLoadPagina();
        let txtOpcion = "consultar_kpi_ventas";

        if (this.id == "kpi1") { txtOpcion = "consultar_kpi_ventas"; };
        if (this.id == "kpi2") { txtOpcion = "consultar_kpi_ventas"; };

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
                        }  
                    });           
                    
                    grdDatos.bindChart(grdDatosChart, { dataFieldsDisplayMode: 'splitPanes', alternateDataFields: false});         
                }
                if (this.id == "kpi2") {     

                    grdDatosChart.option({                       
                        tooltip: {
                            enabled: true,
                            customizeTooltip(args) {                               
                                const valueText = new Intl.NumberFormat('en-EN', { style: 'currency', currency: 'USD' }).format(args.originalValue);                 
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
                        },
                    });

                    grdDatos.bindChart(grdDatosChart, { dataFieldsDisplayMode: 'splitPanes', alternateDataFields: false});     
                }

                if (this.id == "kpi3") {

                    grdDatosChart.option({
                        tooltip: {
                            enabled: true,
                            customizeTooltip(args) {                               
                                const valueText = new Intl.NumberFormat('en-EN', { style: 'currency', currency: 'USD' }).format(args.originalValue);                 
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
                            dataField: 'proyecto',
                            area: 'row',
                            sortOrder: 'asc'
                            },{
                            width: 150,
                            caption: 'Asesor',
                            dataField: 'asesor',
                            area: 'row',
                            sortOrder: 'asc'
                            },{
                            width: 150,
                            caption: 'Cliente',
                            dataField: 'tercero',
                            area: 'row',
                            sortOrder: 'asc'
                            },{
                            caption: 'Venta',
                            dataField: 'inmueble',
                            area: 'row'
                            },{
                            caption: 'Fecha',
                            dataField: 'fechaContrato',
                            dataType: 'date',
                            area: 'column',
                            sortOrder: 'desc'
                            },{
                            groupName: 'date',
                            groupInterval: 'month',
                            sortOrder: 'desc'
                            },{
                            caption: 'Valor',
                            dataField: 'valor_capital',
                            dataType: 'number',
                            summaryType: 'sum',
                            format: 'currency',
                            area: 'data',
                            }],
                            store: datosKpi
                        },
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

