<?php
    require_once("../sesion.php");

    $idPagina = 205;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	if(isset($_POST["producto"])){
        //Agregar productos
        $numero = (count($_POST["producto"]));
        if ($numero > 0) {
            $contador = 0;
            while ($contador < $numero) {
                $consultaProducto=$conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_id='" . $_POST["producto"][$contador] . "' AND prod_precio_predeterminado=1");
                $productoN = $consultaProducto->num_rows;
                if ($productoN == 0) {
                    $conexionBdPrincipal->query("UPDATE productos SET prod_precio_predeterminado=1 WHERE prod_id='" . $_POST["producto"][$contador] . "'");
                }
                $contador++;
            }
        }
        //ELIMINAR LOS QUE YA NO.
        if ($numero > 0) {
            $productosWeb = $conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_precio_predeterminado=1");
            while ($pWeb = mysqli_fetch_array($productosWeb, MYSQLI_BOTH)){
                $encontrado = 0;
                $contador = 0;
                while ($contador < $numero) {
                    if ($pWeb['prod_id'] == $_POST["producto"][$contador]){
                        $encontrado = 1;
                        break;
                    }
                    $contador++;
                }
                if ($encontrado == 0) {
                    $conexionBdPrincipal->query("UPDATE productos SET prod_precio_predeterminado=0 WHERE prod_id='" . $pWeb['prod_id'] . "'");
                }
            }
        }
    }else{
        $conexionBdPrincipal->query("UPDATE productos SET prod_precio_predeterminado=0");
    }

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../productos-predeterminados.php?msg=1";</script>';
	exit();