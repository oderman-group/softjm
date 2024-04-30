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
});