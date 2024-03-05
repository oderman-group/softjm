var idEditar = document.getElementById("id");

$(document).ready(function () {
    let productSelect = $("#product-select").select2({
        placeholder: "Escoja una opción...",
        multiple: true,
        minimumInputLength: 3,
        ajax: {
            type: "GET",
            url: "ajax/ajax-buscar-productos.php",
            dataType: "json",
            processResults: function (items) {
                return {
                    results: items.map(function (item) {
                        return {
                            id: item.id,
                            text: item.text,
                        };
                    })
                };
            }
        }
    });
    
    let comboSelect = $("#combos-select").select2({
        placeholder: "Escoja una opción...",
        multiple: true,
        minimumInputLength: 3,
        ajax: {
            type: "GET",
            url: "ajax/ajax-buscar-combo.php",
            dataType: "json",
            processResults: function (items) {
                return {
                    results: items.map(function (item) {
                        return {
                            id: item.id,
                            text: item.text,
                        };
                    })
                };
            }
        }
    });
    
    let serviciosSelect = $("#servicios-select").select2({
        placeholder: "Escoja una opción...",
        multiple: true,
        minimumInputLength: 3,
        ajax: {
            type: "GET",
            url: "ajax/ajax-buscar-servicio.php",
            dataType: "json",
            processResults: function (items) {
                return {
                    results: items.map(function (item) {
                        return {
                            id: item.id,
                            text: item.text,
                        };
                    })
                };
            }
        }
    });
    
    if (idEditar) {
        productSelect.on("change", function (e) {
            const producto = productSelect.val() || [];
            const url = new URL(window.location.href);
            const id = url.searchParams.get("id");
            $.ajax({
                type: "POST",
                url: "ajax/ajax-actualizar-productos-cotizacion.php",
                data: {
                    producto,
                    id
                },
                success: function (response) {
                    actulizarTablaProductos()
                }
            });
        });

        productSelect.on("select2:clear", function (e) {
            console.log("clear")
        });

        function actulizarTablaProductos() {
            $.ajax({
                type: "POST",
                url: "",
                data: {
                    action: "generarTablaProductos"
                },
                success: function (response) {
                    let bodyStart = response.indexOf('<body>');
                    let bodyEnd = response.indexOf('</body>');
                    let bodyContent = response.slice(bodyStart + 6, bodyEnd);
                    $('#tableBody .producto').remove();
                    $('#tableBody').append(bodyContent);
                }
            });
        }
        actulizarTablaProductos()

        comboSelect.on("change", function (e) {
            const combo = comboSelect.val() || [];
            const url = new URL(window.location.href);
            const id = url.searchParams.get("id");
            $.ajax({
                type: "POST",
                url: "ajax/ajax-actualizar-combos-cotizacion.php",
                data: {
                    combo,
                    id
                },
                success: function (response) {
                    actulizarTablacombos()
                }
            });
        });

        comboSelect.on("select2:clear", function (e) {
            console.log("clear")
        });

        function actulizarTablacombos() {
            $.ajax({
                type: "POST",
                url: "",
                data: {
                    action: "generarTablacombos"
                },
                success: function (response) {
                    let bodyStart = response.indexOf('<body>');
                    let bodyEnd = response.indexOf('</body>');
                    let bodyContent = response.slice(bodyStart + 6, bodyEnd);
                    $('#tableBody .combo').remove();
                    $('#tableBody').append(bodyContent);
                }
            });
        }
        actulizarTablacombos()

        serviciosSelect.on("change", function (e) {
            const servicio = serviciosSelect.val() || [];
            const url = new URL(window.location.href);
            const id = url.searchParams.get("id");
            $.ajax({
                type: "POST",
                url: "ajax/ajax-actualizar-servicios-cotizacion.php",
                data: {
                    servicio,
                    id
                },
                success: function (response) {
                    actulizarTablaservicios()
                }
            });
        });

        serviciosSelect.on("select2:clear", function (e) {
            console.log("clear")
        });

        function actulizarTablaservicios() {
            $.ajax({
                type: "POST",
                url: "",
                data: {
                    action: "generarTablaServicios"
                },
                success: function (response) {
                    let bodyStart = response.indexOf('<body>');
                    let bodyEnd = response.indexOf('</body>');
                    let bodyContent = response.slice(bodyStart + 6, bodyEnd);
                    $('#tableBody .servicio').remove();
                    $('#tableBody').append(bodyContent);
                }
            });
        }
        actulizarTablaservicios()
    }
});
if (idEditar) {
    // Función para recalcular totales
    function recalculate() {
        let subtotal = 0;
        let totalDiscount = 0;
        let totalIva = 0;

        $("#data-table tbody tr").each(function () {
            let quantity = parseInt($(this).find("input[title='czpp_cantidad']").val()) || 0;
            let value = parseFloat($(this).find("input[title='czpp_valor']").val()) || 0;
            let discount = parseFloat($(this).find("input[title='czpp_descuento']").val()) || 0;
            let subtotalRow = quantity * value;
            let discountAmount = (subtotalRow * discount) / 100;
            let rowTotal = subtotalRow - discountAmount;
            let rowIva = (rowTotal * parseFloat($(this).find("input[title='czpp_impuesto']").val())) / 100;

            subtotal += subtotalRow;
            totalDiscount += discountAmount;
            totalIva += rowIva;
        });

        $("#subtotal .valor-numerico").text(subtotal.toLocaleString('es-CO', { minimumFractionDigits: 0 }));
        $("#totalDiscount .valor-numerico").text(totalDiscount.toLocaleString('es-CO', { minimumFractionDigits: 0 }));
        $("#totalIva .valor-numerico").text(totalIva.toLocaleString('es-CO', { minimumFractionDigits: 0 }));

        let envio = parseFloat($("input[name='envio']").val()) || 0;
        let total = subtotal - totalDiscount + totalIva + envio;
        $("#total .valor-numerico").text(total.toLocaleString('es-CO', { minimumFractionDigits: 0 }));
    }

    $("#data-table tbody").on("input", "input[title='czpp_cantidad'], input[title='czpp_valor'], input[title='czpp_descuento'], input[title='czpp_impuesto']", recalculate);
    $("input[name='envio']").on("input", recalculate);

    let observer = new MutationObserver(function (mutations) {
        recalculate();
    });

    let tbody = document.getElementById("data-table").getElementsByTagName("tbody")[0];
    let config = { childList: true };
    observer.observe(tbody, config);
    recalculate();

    // Función para actualizar una subtotal parcial específica
    function updatePartialSubtotal(row) {
        let cantidad = parseFloat(row.find("input[title='czpp_cantidad']").val()) || 0;
        let valor = parseFloat(row.find("input[title='czpp_valor']").val()) || 0;
        let descuento = parseFloat(row.find("input[title='czpp_descuento']").val()) || 0;
        let iva = parseFloat(row.find("input[title='czpp_iva']").val()) || 0;

        let subtotalParcial = cantidad * valor;
        // let subtotalParcial = cantidad * valor - (cantidad * valor * descuento / 100);
        let ivaAmount = (subtotalParcial * iva / 100);
        subtotalParcial += ivaAmount;
        let valorNumeric = row.find('.valor-numerico');
        valorNumeric.text(subtotalParcial.toLocaleString('es-CO', { minimumFractionDigits: 0 }));
    }

    function recalculatePartialSubtotals() {
        $('#data-table tbody tr').each(function () {
            let row = $(this);
            updatePartialSubtotal(row);
        });
    }

    $('#data-table tbody').on('input', 'input[title^="czpp_"]', function () {
        let row = $(this).closest('tr');
        updatePartialSubtotal(row);
    });

    recalculatePartialSubtotals();

    // Controlador de eventos para hacer clic en los enlaces de eliminación
    $(document).on("click", ".delete-product", function (e) {
        e.preventDefault();
        if (!confirm('¿Desea eliminar este registro?')) {
            return;
        }
        const idItem = $(this).data("id");
        const select2Element = $("#product-select");
        const currentSelectedValues = select2Element.val();
        const newSelectedValues = currentSelectedValues.filter(value => value != idItem);

        select2Element.val(newSelectedValues || []);
        select2Element.trigger("change");
    });

    $(document).on("click", ".delete-combo", function (e) {
        e.preventDefault();
        if (!confirm('¿Desea eliminar este registro?')) {
            return;
        }
        const idItem = $(this).data("id");
        console.log(idItem);
        const select2Element = $("#combos-select");
        const currentSelectedValues = select2Element.val();
        const newSelectedValues = currentSelectedValues.filter(value => value != idItem);

        select2Element.val(newSelectedValues || []);
        select2Element.trigger("change");
    });

    $(document).on("click", ".delete-servicios", function (e) {
        e.preventDefault();
        if (!confirm('¿Desea eliminar este registro?')) {
            return;
        }
        const idItem = $(this).data("id");
        console.log(idItem);
        const select2Element = $("#servicios-select");
        const currentSelectedValues = select2Element.val();
        const newSelectedValues = currentSelectedValues.filter(value => value != idItem);

        select2Element.val(newSelectedValues || []);
        select2Element.trigger("change");
    });
}