/* loader */
const loader = document.getElementById("loaderGlobal");

const showLoader = () => {
    loader.classList.add("show_loader");
}
const hideLoader = () => {
    loader.classList.remove("show_loader");
}

const APP_ID = '1908014503405045';
const API_VERSION = 'v24.0';
const PERMISOS = 'public_profile,pages_show_list,pages_read_engagement,pages_manage_posts';

const STORAGE_PAGE_ID = 'fb_page_id';
const STORAGE_PAGE_TOKEN = 'fb_page_token';

const ENDPOINT_EXCHANGE = 'ajax/api-meta/exchange-token.php';
const ENDPOINT_SAVE = 'ajax/api-meta/save-token.php';
const ENDPOINT_PUBLISH = 'ajax/api-meta/publish-post.php';
const ENDPOINT_PRODUCT_ID = 'ajax/ajax-buscar-producto-por-id.php';

(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) { return; }
    js = d.createElement(s); js.id = id;
    js.src = `https://connect.facebook.net/en_US/sdk.js`;
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

window.fbAsyncInit = function () {
    FB.init({
        appId: APP_ID,
        xfbml: true,
        version: API_VERSION
    });
    console.log('[FB SDK] Inicializado');
};

$(function () {
    $(document).on("click", ".js-share-product", async function (event) {
        event.preventDefault();

        const idProducto = $(this).data("id_product");
        await compartirProducto(idProducto);
    });
});

function compartirProducto(idProducto) {

    const pageId = localStorage.getItem(STORAGE_PAGE_ID);
    const pageToken = localStorage.getItem(STORAGE_PAGE_TOKEN);
    const message = "Publicación de prueba instantánea.";

    // Función para manejar el error de token y forzar re-autenticación
    const handleTokenError = (error) => {
        console.error("Fallo de publicación/autenticación:", error);
        localStorage.removeItem(STORAGE_PAGE_ID);
        localStorage.removeItem(STORAGE_PAGE_TOKEN);

        // Inicia el flujo de autenticación nuevamente
        iniciarFlujoAutenticacion(message, idProducto)
            .catch(authError => {
                console.log('', `Fallo de re-autenticación: ${authError.message}`);
            });
    };

    //INTENTAR PUBLICACIÓN DIRECTA (Flujo recurrente)
    if (pageId && pageToken) {
        console.log('🚀 Publicando con token guardado...');

        abrirModalpublicarPost(idProducto);

    } else {
        //INICIAR FLUJO DE AUTENTICACIÓN (Primera vez o token borrado)
        console.log('⚠️ Token no encontrado. Iniciando autenticación...');

        iniciarFlujoAutenticacion(message, idProducto)
            .catch(error => {
                console.log('', `Fallo de autenticación: ${error.message}`);
            })
            .finally(() => {
                console.log('Peticion Publicar Finalizada');
            });
    }
}

function iniciarFlujoAutenticacion(message, idProducto) {
    return new Promise((resolve, reject) => {
        FB.login(function (response) {
            if (response.authResponse) {
                const shortLivedToken = response.authResponse.accessToken;
                const fbUserId = response.authResponse.userID;

                console.log('Conexión exitosa. Intercambiando tokens...');
                exchangeAndGetPages(shortLivedToken, fbUserId)
                    .then(data => {
                        mostrarSelectorDePaginas(data.pages, fbUserId, message, idProducto);
                        resolve();
                    })
                    .catch(error => {
                        console.log('', `Fallo de intercambio: ${error.message}`);
                        reject(error);
                    });
            } else {
                console.log('', `Error: Autenticación cancelada.`);
                reject(new Error("Autenticación cancelada."));
            }
        }, { scope: PERMISOS });
    });
}

function exchangeAndGetPages(shortToken, fbUserId) {
    const payload = { short_token: shortToken, fb_user_id: fbUserId };

    return fetch(ENDPOINT_EXCHANGE, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.error);
            }
            return data;
        });
}

function savePageTokenAndPublish(fbUserId, pageId, pageToken, message, idProducto) {
    const payload = { page_id: pageId, page_token: pageToken, fb_user_id: fbUserId };

    return fetch(ENDPOINT_SAVE, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    })
        .then(response => response.json())
        .then(saveData => {
            if (!saveData.success) {
                throw new Error(saveData.error || "Fallo al guardar el token en el servidor.");
            }

            localStorage.setItem(STORAGE_PAGE_ID, pageId);
            localStorage.setItem(STORAGE_PAGE_TOKEN, pageToken);
            $('#modalPagesMeta').modal('hide');
            document.getElementById("pages_container").innerHTML = "";

            return abrirModalpublicarPost(idProducto);
        });
}

function obtenerProducto(idProducto = 10) {
    if (!idProducto || idProducto === 'ID_DEL_PRODUCTO_ACTUAL') {
        console.warn('Advertencia: Usando ID de producto de prueba fijo. Asegúrate de pasar el ID real.');
    }

    return fetch(`${ENDPOINT_PRODUCT_ID}?idProducto=${idProducto}`, {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
    })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.error || "Fallo al obtener datos del producto.");
            }
            return data;
        });
}

function abrirModalpublicarPost(idProducto = 10) {
    $('#post_message_input').val('');
    $('#modalDescrptionMeta').modal('show');
    document.getElementById("idProductoFB").value = idProducto;
}

$(function () {
    $(document).on("click", "#publicarPost", async function (event) {
        event.preventDefault();

        const idProducto = document.getElementById("idProductoFB").value;
        publicarPost(localStorage.getItem(STORAGE_PAGE_ID), localStorage.getItem(STORAGE_PAGE_TOKEN), idProducto)
    });
});

function publicarPost(pageId, pageToken, idProducto = 10) {
    showLoader();
    return obtenerProducto(idProducto)
        .then(dataProduct => {
            const messageInput = $('#post_message_input').val();

            const mensajeEstructurado = `PRODUCTO: ${dataProduct?.nombre ?? 'Producto'} \n Descripción: ${dataProduct?.descripcion_corta ?? dataProduct?.description ?? 'Sin descripción.'}`;

            const mensajeFinal = messageInput + "\n\n" + mensajeEstructurado.trim();
            let imageURLs = [
                "https://www.coversstoreperu.com/cdn/shop/products/s7SUEIorD-CW33-1.jpg?v=1688674904",
                "https://www.impacto.com.pe/storage/products/md/173963873893868.webp",
                "https://c.files.bbci.co.uk/8B70/production/_102469653_gettyimages-962792890.jpg"
            ]
            const payload = {
                page_id: pageId,
                page_token: pageToken,
                message: mensajeFinal,
                picture_url: null, //url de la pagina donde esta la IGM para generar privisualizacion
                image_urls: imageURLs,
                link_url: "https://koha.mk/wp-content/uploads/2019/07/Mesi-4-750x430.jpg" // Simulación de URL del producto
            };

            return fetch(ENDPOINT_PUBLISH, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.error);
            }
            Swal.fire({
                title: "¡Publicacion Creada con Exito!",
                icon: "success",
                draggable: false
            });
            hideLoader();
            $('#modalDescrptionMeta').modal('hide');
            return data;
        })
        .catch(error => {
            throw error;
        });
}

function mostrarSelectorDePaginas(pages, fbUserId, message, idProducto) {
    $('#modalPagesMeta').modal('show');
    const container = document.getElementById("pages_container");

    if (!pages || pages.length === 0) {
        container.innerHTML = "<p class='error p-2 bg-red-100 rounded-lg'>No administras ninguna página con permisos de publicación.</p>";
        return;
    }

    let html = `
        <select id="page_selector" class="mt-2 p-2" style="width: 100%">
        <option value="">-- Seleccionar Página --</option>
        `;

    pages.forEach(page => {
        const encodedValue = `${page.id}|${encodeURIComponent(page.access_token)}`;
        html += `<option value='${encodedValue}'>${page.name} (${page.id})</option>`;
    });

    html += `</select>
            <button onclick="handleSaveAndPublish('${fbUserId}', '${message}', '${idProducto}')" 
            class="mt-4 w-full" style="float: right">
            Guardar Token y Publicar Post Inicial
            </button>
        `;

    container.innerHTML = html;
}

// Manejador del botón en la interfaz de selección de página
function handleSaveAndPublish(fbUserId, message, idProducto) {
    const selector = document.getElementById("page_selector");
    const selectedEncodedValue = selector.value;

    if (!selectedEncodedValue) {
        alert(`⚠️ Por favor, selecciona una página.`)
        return;
    }

    const [pageId, encodedToken] = selectedEncodedValue.split('|');
    const pageToken = decodeURIComponent(encodedToken);

    if (pageId && pageToken) {
        console.log(`💾 Iniciando guardado y publicación...`);

        savePageTokenAndPublish(fbUserId, pageId, pageToken, message, idProducto)
            .catch(error => {
                console.error("Error en guardar/publicar:", error);
            });
    }
}