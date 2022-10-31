
<?php
	$consultaColorEncabezado = $conexionBdPrincipal->query("SELECT * FROM encabezado_color_empresa WHERE cxe_id_empresa='".$configuracion['conf_id_empresa']."'");
	$colorEncabezado = mysqli_fetch_array($consultaColorEncabezado, MYSQLI_BOTH);
?>

<style type="text/css">
.navbar-inverse .navbar-inner {
	background-color: <?=$colorEncabezado[1];?>;
}

.navbar-inverse .brand, .navbar-inverse .nav > li > a {
	color: <?=$colorEncabezado[2];?>;
}

.navbar-inverse .brand, .navbar-inverse .nav > li > a:hover {
	color: <?=$colorEncabezado[3];?>;
}

.navbar-inverse .nav li.dropdown.open > .dropdown-toggle, .navbar-inverse .nav li.dropdown.active > .dropdown-toggle, .navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle {
	background-color: <?=$colorEncabezado[4];?>;
	color: <?=$colorEncabezado[5];?>;
}

.dropdown-menu {
	background-color: <?=$colorEncabezado[6];?>;
}

.top-nav .nav .dropdown-menu ul {
	background-color: <?=$colorEncabezado[7];?>;
}

.dropdown-menu li > a:hover, .dropdown-menu li > a:focus, .dropdown-submenu:hover > a {
	background-color: <?=$colorEncabezado[8];?>;
}
</style>