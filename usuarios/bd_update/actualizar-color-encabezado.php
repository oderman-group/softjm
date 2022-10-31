<?php
require_once("../sesion.php");

$idPagina = 191;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$conexionBdPrincipal->query("UPDATE encabezado_color_empresa SET  cxe_fondo='" . $_POST["colFondo"] . "', cxe_text_items='" . $_POST["colText"] . "', cxe_text_items_hover='" . $_POST["colTextHover"] . "', cxe_fondo_items_activo='" . $_POST["colFonActivo"] . "', cxe_text_items_activo='" . $_POST["colTextActivo"] . "', cxe_border_submenu='" . $_POST["colBorSubmenu"] . "', cxe_fondo_submenu='" . $_POST["colFonSubmenu"] . "', cxe_fondo_submenu_hover='" . $_POST["colFonSubmenuHover"] . "' WHERE cxe_id_empresa='".$_POST["id"]."'");

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../configuracion-color-encabezado.php?id='.$_POST["id"].'";</script>';
exit();