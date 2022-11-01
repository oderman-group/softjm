
<?php
	$consultaColorEncabezado = $conexionBdAdmin->query("SELECT * FROM encabezado_color_empresa WHERE cxe_id_empresa='".$configuracion['conf_id_empresa']."'");
	$colorEncabezado = mysqli_fetch_array($consultaColorEncabezado, MYSQLI_BOTH);
?>

<style type="text/css">
.navbar-inverse .navbar-inner {
	background-color: <?=$colorEncabezado['cxe_fondo'];?>;
}

.navbar-inverse .brand, .navbar-inverse .nav > li > a {
	color: <?=$colorEncabezado['cxe_text_items'];?>;
}

.navbar-inverse .brand, .navbar-inverse .nav > li > a:hover {
	color: <?=$colorEncabezado['cxe_text_items_hover'];?>;
}

.navbar-inverse .nav li.dropdown.open > .dropdown-toggle, .navbar-inverse .nav li.dropdown.active > .dropdown-toggle, .navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle {
	background-color: <?=$colorEncabezado['cxe_fondo_items_activo'];?>;
	color: <?=$colorEncabezado['cxe_text_items_activo'];?>;
}

.dropdown-menu {
	background-color: <?=$colorEncabezado['cxe_border_submenu'];?>;
}

.top-nav .nav .dropdown-menu ul {
	background-color: <?=$colorEncabezado['cxe_fondo_submenu'];?>;
}

.dropdown-menu li > a:hover, .dropdown-menu li > a:focus, .dropdown-submenu:hover > a {
	background-color: <?=$colorEncabezado['cxe_fondo_submenu_hover'];?>;
}
</style>