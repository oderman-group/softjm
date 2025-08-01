<?php
    require_once("../sesion.php");

    $idPagina = 217;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

    if ($_FILES['foto']['name'] != "") {
		$destino = RUTA_PROYECTO."/usuarios/files/combos";
		$fileName = subirArchivosAlServidor($_FILES['foto'], 'comb', $destino);

        $conexionBdPrincipal->query("UPDATE combos SET combo_imagen='" . $fileName . "' WHERE combo_id='" . $_POST["id"] . "' AND combo_id_empresa = '".$_SESSION["dataAdicional"]["id_empresa"]."'");
    }

    $_POST["dcto"]          = !empty($_POST["dcto"]) ? $_POST["dcto"] : 0;
    $_POST["descuentoMax"]  = !empty($_POST["descuentoMax"]) ? $_POST["descuentoMax"] : 0;
    $_POST["dctoDealer"]    = !empty($_POST["dctoDealer"]) ? $_POST["dctoDealer"] : 0;

    $conexionBdPrincipal->query("UPDATE combos SET 
    combo_nombre='" . $_POST["nombre"] . "', 
    combo_descripcion='" . $_POST["descripcion"] . "', 
    combo_descuento='" . $_POST["dcto"] . "', 
    combo_actualizaciones=combo_actualizaciones+1, 
    combo_ultima_actualizacion=now(), 
    combo_estado='" . $_POST["estado"] . "', 
    combo_descuento_maximo='" . $_POST["descuentoMax"] . "', 
    combo_descuento_dealer='" . $_POST["dctoDealer"] . "' 
    WHERE combo_id='" . $_POST["id"] . "' AND combo_id_empresa = '".$_SESSION["dataAdicional"]["id_empresa"]."'");

    if (isset($_POST["producto"])) {
        $idCombo = $_POST["id"]; // El ID del combo que estamos editando
        $productosSeleccionadosUI = $_POST["producto"]; // Array de IDs de productos enviados desde el formulario (multi-select)

        // Convertir el array de productos seleccionados a un conjunto (Set) para búsquedas más rápidas (opcional, pero buena práctica)
        $productosSeleccionadosSet = array_flip($productosSeleccionadosUI); // Crea un array asociativo con los valores como claves

        // --- 1. Obtener los productos actuales asociados a este combo en la base de datos ---
        $productosActualesDB = [];
        $consultaActuales = $conexionBdPrincipal->query("SELECT copp_producto FROM combos_productos WHERE copp_combo = '" . $idCombo . "'");

        while ($fila = mysqli_fetch_assoc($consultaActuales)) {
            $productosActualesDB[] = $fila['copp_producto'];
        }
        // Convertir el array de productos actuales de la DB a un conjunto
        $productosActualesDBSet = array_flip($productosActualesDB);

        // --- 2. Determinar productos a eliminar ---
        $productosAEliminar = [];
        foreach ($productosActualesDB as $productoIdDB) {
            // Si un producto de la DB NO está en los seleccionados de la UI, hay que eliminarlo
            if (!isset($productosSeleccionadosSet[$productoIdDB])) {
                $productosAEliminar[] = $productoIdDB;
            }
        }

        // --- 3. Determinar productos a insertar ---
        $productosAInsertar = [];
        foreach ($productosSeleccionadosUI as $productoIdUI) {
            // Si un producto seleccionado de la UI NO está en la DB, hay que insertarlo
            if (!isset($productosActualesDBSet[$productoIdUI])) {
                $productosAInsertar[] = $productoIdUI;
            }
        }

        // --- 4. Ejecutar operaciones SQL dentro de una transacción ---
        // Iniciar transacción para asegurar atomicidad de las operaciones
        $conexionBdPrincipal->begin_transaction();
        try {
            // Eliminar productos
            if (!empty($productosAEliminar)) {
                $idsParaEliminar = implode("','", $productosAEliminar); // Formato 'id1','id2'
                $sqlDelete = "DELETE FROM combos_productos WHERE copp_combo = '" . $idCombo . "' AND copp_producto IN ('" . $idsParaEliminar . "')";
                $conexionBdPrincipal->query($sqlDelete);
            }

            // Insertar productos
            if (!empty($productosAInsertar)) {
                $valoresInsertar = [];
                foreach ($productosAInsertar as $productoId) {
                    // Para cada producto a insertar, necesitamos su precio actual de la tabla de productos.
                    // Esta consulta individual aquí no es la más eficiente si hay muchos,
                    // pero si 'productosAInsertar' no es enorme, está bien.
                    // Una alternativa sería cargar todos los precios de los productos seleccionados en un solo SELECT al principio.
                    $consultaProductoDatos = $conexionBdPrincipal->query("SELECT prod_precio FROM productos WHERE prod_id = '" . $productoId . "'");
                    $productoDatos = mysqli_fetch_array($consultaProductoDatos, MYSQLI_BOTH);
                    $precioProducto = $productoDatos['prod_precio'] ?? 0; // Usar 0 o valor predeterminado si no se encuentra el precio

                    $valoresInsertar[] = "('" . $idCombo . "', '" . $productoId . "', 1, '" . $precioProducto . "')";
                }
                if (!empty($valoresInsertar)) {
                    $sqlInsert = "INSERT INTO combos_productos(copp_combo, copp_producto, copp_cantidad, copp_precio) VALUES " . implode(", ", $valoresInsertar);
                    $conexionBdPrincipal->query($sqlInsert);
                }
            }

            // Si todo fue bien, confirmar la transacción
            $conexionBdPrincipal->commit();
            // Opcional: Redirigir o mostrar mensaje de éxito
            // header("Location: ...?success=1");
            // exit();

        } catch (Exception $e) {
            // Si algo falla, revertir la transacción
            $conexionBdPrincipal->rollback();
            // Opcional: Registrar error o mostrar mensaje de error
            error_log("Error al sincronizar combo: " . $e->getMessage());
            // header("Location: ...?error=1");
            // exit();
        }

    } else {
        // Si no se envió ningún producto en el multi-select (es decir, se deseleccionaron todos)
        // Entonces, se eliminan todos los productos asociados a este combo.
        $idCombo = $_POST["id"]; // Obtener el ID del combo
        $conexionBdPrincipal->query("DELETE FROM combos_productos WHERE copp_combo='" . $idCombo . "'");
    }

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../combos-editar.php?id=' . $_POST["id"] . '&msg=1";</script>';
    exit();