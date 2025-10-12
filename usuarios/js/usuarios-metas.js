import { clsGenerales } from './_generales.js';
document.addEventListener('DOMContentLoaded', () => {

    DevExpress.localization.locale('es');
    const clsGenerales_ = new clsGenerales();

    const txtIdEmpresa = document.getElementById('txtIdEmpresa');
    var idEmpresa = txtIdEmpresa.value;
    var selectId = 0;
    
    const txtValorMeta = clsGenerales_.fnNumber(document.getElementById('txtValorMeta'));
    const btnAgregar = document.getElementById('btnAgregar');
    const btnEliminar = document.getElementById('btnEliminar');

    const cmbUsuarios = $('#cmbUsuarios').dxSelectBox({
        placeholder: 'Seleccione una opcion',
        valueExpr: "id",
        displayExpr(item) {
            return item && `${item.usuario} | ${item.nombre} | ${item.email}`;
        },
        noDataText: "No hay datos que mostrar",
        searchEnabled: true,
    }).dxSelectBox('instance');

    const cmbMetas = $('#cmbMetas').dxSelectBox({
        placeholder: 'Seleccione una opcion',
        items: ["NUMERO_VENTA","NUMERO_VISITAS","VALOR_VENTAS","NUMERO_DEMOSTRACIONES"],
        noDataText: "No hay datos que mostrar",
        inputAttr: { 'aria-label': 'Simple Product' },
        searchEnabled: true,
    }).dxSelectBox('instance');


    const dtPeriodo = $('#dtPeriodo').dxDateBox({
       type: 'date',
        value: new Date(),
         displayFormat: function (date) {
        const formato = new Intl.DateTimeFormat('es-ES', {
            month: 'long',
            year: 'numeric'
        });
        const texto = formato.format(date);
        return texto.toUpperCase();
        }, 
        calendarOptions: {
            maxZoomLevel: 'year',
            minZoomLevel: 'year'
        },
        openOnFieldClick: true
    }).dxDateBox('instance');


    var grdDatos = $("#grdDatos").dxDataGrid({
        columns: [{
            dataField: 'id',
            visible: false
        }, "fecha", "tipo","usuario", "nombre",{
            dataField: 'anno',
            caption: "Año"        
        }, "mes", {
            dataField: 'periodo',
            visible: false
        },{
            dataField: 'meta',
            dataType: 'number',
            format: {
                precision: 2
            },
            alignment: 'right',
        }],
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
        onSelectionChanged: function(selectedItems) {
            if (selectedItems.selectedRowsData.length > 0) {
                selectId = selectedItems.selectedRowsData[0].id;
            }
        }
    }).dxDataGrid("instance");
    clsGenerales_.mtdOpionesGrid(grdDatos);    
    
    function btnActualizarClick() {
        clsGenerales_.mtdActivarLoadPagina();
        $.ajax({
            url: "ajax/ajax-usuarios.php",
            type: "POST",
            crossDomain: true,
            dataType: 'json',
            data: {
                e_datos: JSON.stringify([{id_empresa: idEmpresa}]),
                opcion: "consultar_usuarios_metas_id_empresa"
            },
            error: function() {
                clsGenerales_.mtdDesactivarLoadPagina();
                clsGenerales_.mtdMostrarMensaje("No se pudo completar la solicitud", "error");
            }
        }).done((respuesta) => {
            clsGenerales_.mtdDesactivarLoadPagina();    
            if (respuesta["estado"] === 'ok') {
                grdDatos.option({ dataSource: respuesta["datos"] });
                grdDatos.refresh();          
            }

        }); 
    }

    clsGenerales_.mtdActivarLoadPagina();
    $.ajax({
        url: "ajax/ajax-usuarios.php",
        type: "POST",
        crossDomain: true,
        dataType: 'json',
        data: {
            e_datos: JSON.stringify([{id_empresa: idEmpresa}]),
            opcion: "consultar_usuarios_id_empresa"
        },
        error: function() {
            clsGenerales_.mtdDesactivarLoadPagina();
            clsGenerales_.mtdMostrarMensaje("No se pudo completar la solicitud", "error");
        }
    }).done((respuesta) => {
        clsGenerales_.mtdDesactivarLoadPagina();    
        if (respuesta["estado"] === 'ok') {
            cmbUsuarios.option({ dataSource: respuesta["datos"] });
        }

        btnActualizarClick();

    });   


    btnAgregar.addEventListener('click', btnAgregarClick);
    function btnAgregarClick(e) {
        e.preventDefault();

        if (!clsGenerales_.fnComponetInstanceGetValue(cmbUsuarios)) {
            clsGenerales_.mtdMostrarMensaje("Selecciona un usuario", "error");
            cmbUsuarios.focus();
            return;
        }

        if (!clsGenerales_.fnComponetInstanceGetValue(cmbMetas)) {
            clsGenerales_.mtdMostrarMensaje("Selecciona una meta", "error");
            cmbMetas.focus();
            return;
        }

        if (!clsGenerales_.fnComponetInstanceGetValue(dtPeriodo)) {
            clsGenerales_.mtdMostrarMensaje("Selecciona un periodo", "error");
            dtPeriodo.focus();
            return;
        }

        if (clsGenerales_.fnComponetInstanceGetValue(txtValorMeta) == 0) {
            clsGenerales_.mtdMostrarMensaje("Digita el valor de la meta", "error");
            txtValorMeta.focus();
            return;
        }

        clsGenerales_.mtdActivarLoadPagina();

        var dtFecha = new Date(clsGenerales_.fnComponetInstanceGetValue(dtPeriodo));
        var anno = dtFecha.getFullYear();
        var mes = dtFecha.getMonth() + 1;

        var e_datos = [{
            id_empresa: idEmpresa,
            id_usuario: clsGenerales_.fnComponetInstanceGetValue(cmbUsuarios),
            tipo_meta: clsGenerales_.fnComponetInstanceGetValue(cmbMetas),
            valor_meta: clsGenerales_.fnComponetInstanceGetValue(txtValorMeta),
            anno: anno,
            mes: mes
        }];

        $.ajax({
            url: "ajax/ajax-usuarios.php",
            type: "POST",
            dataType: 'json',
            data: {
                e_datos: JSON.stringify(e_datos),
                opcion: "crear_usuarios_metas"
            },
            error: function() {
                clsGenerales_.mtdDesactivarLoadPagina();
                clsGenerales_.mtdMostrarMensaje("No se pudo completar la solicitud", "error");
            }
        }).done((respuesta) => {
            clsGenerales_.mtdDesactivarLoadPagina();
            if (respuesta["estado"] === 'ok') {
                clsGenerales_.mtdMostrarMensaje(respuesta["mensaje"]);
                btnActualizarClick();
            }
            if (respuesta["estado"] === 'ko') {
                clsGenerales_.mtdMostrarMensaje(respuesta["mensaje"], "error");
            }            
        });
        
    }

    btnEliminar.addEventListener('click', btnEliminarClick);
    function btnEliminarClick(e) {
        e.preventDefault();

        if (selectId === 0) {
            clsGenerales_.mtdMostrarMensaje("Selecciona un registo de la tabla", "error");
            return;
        }          
        
        var confirm = DevExpress.ui.dialog.confirm("<i>Seguro que desea eliminar el registro</i>", "¿Desea eliminar?");
        confirm.done((dialogResult) => {
            if (dialogResult) {

                clsGenerales_.mtdActivarLoadPagina();

                var e_datos = [{
                    id_meta: selectId
                }];

                $.ajax({
                    url: "ajax/ajax-usuarios.php",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        e_datos: JSON.stringify(e_datos),
                        opcion: "eliminar_usuarios_metas"
                    },
                    error: function() {
                        clsGenerales_.mtdDesactivarLoadPagina();
                        clsGenerales_.mtdMostrarMensaje("No se pudo completar la solicitud", "error");
                    }
                }).done((respuesta) => {
                    clsGenerales_.mtdDesactivarLoadPagina();
                    if (respuesta["estado"] === 'ok') {
                        clsGenerales_.mtdMostrarMensaje(respuesta["mensaje"]);
                        btnActualizarClick();
                    }
                    if (respuesta["estado"] === 'ko') {
                        clsGenerales_.mtdMostrarMensaje(respuesta["mensaje"], "error");
                    }            
                });
            }
        });       
        
    }


});

