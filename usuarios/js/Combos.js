/* Inicializacion de productos en combos-editar.php (inline). */
function initComboProductSelect() {
    var $el = $("#product-select");
    if (!$el.length || typeof $.fn.select2 !== "function") {
        return;
    }

    if ($el.hasClass("select2-hidden-accessible")) {
        $el.select2("destroy");
    }

    var ajaxUrl = $el.attr("data-ajax-url") || "ajax/ajax-buscar-productos.php";
    var opts = {
        placeholder: "Escriba para buscar productos...",
        multiple: true,
        width: "100%",
        closeOnSelect: false,
        allowClear: true
    };

    if ($el.prop("disabled")) {
        opts.disabled = true;
        $el.select2(opts);
        return;
    }

    opts.minimumInputLength = 1;
    opts.language = {
        inputTooShort: function () { return "Escriba al menos 1 caracter para buscar"; },
        noResults: function () { return "Sin resultados"; },
        searching: function () { return "Buscando..."; }
    };
    opts.ajax = {
        url: ajaxUrl,
        dataType: "json",
        delay: 250,
        data: function (params) {
            return { term: params.term || "" };
        },
        processResults: function (data) {
            if (!data || !$.isArray(data)) {
                return { results: [] };
            }
            return {
                results: $.map(data, function (item) {
                    return { id: String(item.id), text: item.text };
                })
            };
        }
    };

    $el.select2(opts);
}
