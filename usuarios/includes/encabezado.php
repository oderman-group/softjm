<div class="loader"></div>

<?php if( $datosUsuarioActual['usr_tipo']==1 || isset($_SESSION['admin']) ){?>

	<div style="height:50px; width:100%; background-color:<?=COLOR_BARRA_DEV;?>; padding:2px; color:<?=COLOR_LETRA_BARRA_DEV;?>; display:flex; justify-content: center; align-items: center; font-size:11px;">
		<b>Sesion DB:&nbsp;</b> <?=$_SESSION["bd"]; ?>&nbsp;|&nbsp;
		<b>ID Company:&nbsp;</b> <?=$configuracion['conf_id_empresa']; ?>&nbsp;|&nbsp;
		<b>Current User ID:&nbsp;</b> <?=$_SESSION["id"]; ?>&nbsp;|&nbsp;
		<b>ID Page:&nbsp;</b> <?=$idPagina; ?>&nbsp;|&nbsp;
		<b>ID Module:&nbsp;</b> <?=$paginaActual['pag_id_modulo']; ?>&nbsp;|&nbsp;
		<b>Versión PHP:&nbsp;</b> <?=phpversion(); ?>&nbsp;|&nbsp; 
		<b>Server:&nbsp;</b> <?=$_SERVER['SERVER_NAME']; ?>&nbsp;|&nbsp;
		<b>BD:&nbsp;</b> <?=SERVER." - ".MAINBD; ?>&nbsp;|&nbsp;
		<?php if( isset($_SESSION['admin']) ){?>
			<b>User Admin:&nbsp;</b> <?=$_SESSION['admin']; ?>&nbsp;|&nbsp;
			<a href="return-admin-panel.php" style="color:white; text-decoration:underline;">RETURN TO ADMIN PANEL</a>
		<?php }?>	
		
	</div>

<?php }

require_once(RUTA_PROYECTO."/usuarios/config/colores-encabezado.php");
?>



<div class="navbar navbar-inverse top-nav">
		<div class="navbar-inner">
			<div class="container">
				<span class="home-link"><a href="index.php" class="icon-home"></a></span>
				<div class="nav-collapse">
					<ul class="nav">

						<li><a style="font-weight: bold; color: yellow; font-size: 14px;"><?=$_SESSION["dataAdicional"]["nombre_empresa"];?></a></li>

						<?php if(Modulos::validarAccesoModulo($configuracion['conf_id_empresa'], 1, $conexionBdAdmin, $datosUsuarioActual)){?>
							<li class="dropdown"><a href="index.php"><i class="icon-dashboard"></i> Escritorio</a></li>
						<?php }?>

						<?php if(Modulos::validarAccesoModulo($configuracion['conf_id_empresa'], 2, $conexionBdAdmin, $datosUsuarioActual)){?>
							<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-cogs"></i> Sistema <b class="icon-angle-down"></b></a>
							<div class="dropdown-menu">
								<ul>
								<?php if(Modulos::validarAccesoModulo($configuracion['conf_id_empresa'], 8, $conexionBdAdmin, $datosUsuarioActual)){?>
									<li class="dropdown-submenu"><a href="#"><i class="icon-sitemap"></i> Parametrización</a>
										<div class="dropdown-menu">
											<ul>
												<li><a href="configuracion.php"><i class=" icon-cogs"></i> Configuración</a></li>
												<li><a href="configuracion-color-encabezado.php"><i class=" icon-tint"></i> Configuración Encabezado</a></li>
												<!-- Check_id 7 y 15 -->
													<li><a href="metricas.php?id=1"><i class="icon-bar-chart"></i> Métricas </a></li>
												<!-- endif-check_id  -->
												<li><a href="estructura-mensajes.php"><i class=" icon-edit"></i> Estructura de mensajes</a></li>
											</ul>
										</div>
									</li>
								<?php }?>
									<li><a href="modulos.php"><i class="icon-th-large"></i>Módulos</a></li>
									<li><a href="paginas.php"><i class="icon-th-list"></i> Páginas</a></li>
									<li><a href="documentos.php"><i class="icon-file"></i> Documentos</a></li>
									<li><a href="buzon.php"><i class="icon-envelope"></i> Buzón de salida </a></li>

									<li class="dropdown-submenu"><a href="#"><i class="icon-file-alt"></i> Documentación</a>
									<div class="dropdown-menu">
										<ul>
											<li><a href="tutoriales.php"><i class="icon-facetime-video"></i> Tutoriales</a></li>
											<li><a href="https://www.loom.com/share/308bdd148ddc4bffb2af76e27e3d5139" target="_blank"><i class="icon-facetime-video"></i> Tutorial Completo</a></li>
										</ul>
									</div>
									</li>
								</ul>
							</div>
							</li>
						<?php }?>

						<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-suitcase"></i> Administración <b class="icon-angle-down"></b></a>
						<div class="dropdown-menu">
							<ul>
								<?php
								$listaPaginasAdmin = [
									["url" => "roles.php", "id" => 5, "nombre" => "Roles", "clase" => "icon-group"],
									["url" => "areas.php", "id" => 161, "nombre" => "Áreas", "clase" => "icon-sitemap"],
									["url" => "zonas.php", "id" => 47, "nombre" => "Zonas", "clase" => "icon-globe"],
									["url" => "sucursales.php", "id" => 136, "nombre" => "Mis sucursales", "clase" => "icon-sitemap"],
									["url" => "bodegas.php", "id" => 142, "nombre" => "Bodegas", "clase" => "icon-stop"],
									["url" => "usuarios.php", "id" => 2, "nombre" => "Usuarios", "clase" => "icon-user"],
									["url" => "historial-acciones.php", "id" => 8, "nombre" => "Historial de acciones", "clase" => "icon-time"],
									["url" => "proyectos.php", "id" => 108, "nombre" => "Proyectos", "clase" => "icon-tasks"],
									["url" => "encuesta.php", "id" => 229, "nombre" => "Encuestas", "clase" => "icon-ok-sign"],
								];
								$informesSubmenu = [
									["url" => "informes-todos.php", "id" => 120, "nombre" => "Informes", "clase" => "icon-file-alt"],
									["url" => "estadisticas.php", "id" => 67, "nombre" => "Estadisticas", "clase" => "icon-bar-chart"],
								];
								    foreach ($listaPaginasAdmin as $datosPagina => $pagina) {
											if (Modulos::validarRol([$pagina['id']], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
												echo '<li><a href="'.$pagina['url'].'"><i class="'. $pagina['clase'].'"></i>'.$pagina['nombre'].'</a></li>';
										}
									}
								?>
															
								<?php if($_SESSION["bd"]=='odermancom_jm_crm' and $datosUsuarioActual['usr_tipo']==1){?>
									<li class="divider"></li>
									<li><a href="clientes-orion.php"><i class="icon-group"></i> CLientes ORION </a></li>
								<?php }?>
								<li class="dropdown-submenu"><a href="#"><i class="icon-paste"></i> Informes</a>
								<div class="dropdown-menu">
									<ul>
										<?php 
											foreach ($informesSubmenu as $datosPagina => $pagina) {
												if (Modulos::validarRol([$pagina['id']], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
													echo '<li><a href="'.$pagina['url'].'"><i class="'. $pagina['clase'].'"></i>'.$pagina['nombre'].'</a></li>';
												}
											}
										?>
									</ul>
								</div>
								</li>
							</ul>
						</div>
						</li>

						<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-bullhorn"></i> Comercial <b class="icon-angle-down"></b></a>
						<div class="dropdown-menu">
							<ul>
								<?php
												$submenuClientes = [
													["url" => "clientes.php", "id" => 9 , "nombre" => " Clientes", "clase" => " icon-file-alt"],
													["url" => "dealer.php", "id" => 232, "nombre" => " Grupos", "clase" => " icon-file-alt"],
													["url" => "clientes-filtro.php", "id" => 103, "nombre" => " Mercadeo", "clase" => " icon-file-alt"],
													["url" => "enviar-portafolios.php", "id" => 110, "nombre" => " Enviar portafolios", "clase" => " icon-list-ul"],
													["divider" => true],
													["url" => "publicidad.php", "id" => 267, "nombre" => " Publicidad", "clase" => " icon-file-alt"],
													["url" => "cupones.php", "id" => 165, "nombre" => " Cupones", "clase" => " icon-file-alt"],
												];
								?>
							<li class="dropdown-submenu"><a href="#"><i class="icon-user"></i> Clientes</a>
								<div class="dropdown-menu">
								<ul>
									<?php foreach ($submenuClientes as $pagina) { ?>
											<?php if (isset($pagina['divider'])) { ?>
													<li class="divider"></li>
											<?php } else { ?>
													<?php if (Modulos::validarRol([$pagina['id']], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
															<li><a href="<?= $pagina['url'] ?>"><i class="<?= $pagina['clase'] ?>"></i><?= $pagina['nombre'] ?></a></li>
													<?php } ?>
											<?php } ?>
									<?php } ?>
								</ul>
								</div>
							</li>
							<?php
								$submenuProductos = [
									["url" => "productos.php", "id" => 36 , "nombre" => " Productos", "clase" => " icon-file-alt"],
									["url" => "combos.php", "id" => 172, "nombre" => " Combos", "clase" => " icon-file-alt"],
									["url" => "categoriasp.php", "id" => 39, "nombre" => " Categorías", "clase" => " icon-file-alt"],
									["url" => "marcas.php", "id" => 33, "nombre" => " Marcas", "clase" => " icon-file-alt"],
								];
								?>
							<li class="dropdown-submenu"><a href="#"><i class="icon-minus-sign"></i> Productos</a>
								<div class="dropdown-menu">
									<ul>
									<?php foreach ($submenuProductos as $pagina) { ?>										
										<?php if (Modulos::validarRol([$pagina['id']], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
												<li><a href="<?= $pagina['url'] ?>"><i class="<?= $pagina['clase'] ?>"></i><?= $pagina['nombre'] ?></a></li>
										<?php } ?>
									<?php } ?>
									</ul>
								</div>
							</li>
							<?php
								$menuComercial = [
									["url" => "clientes-tikets.php?tipo=1", "id" => 88 , "nombre" => " Tickets/Negociación", "clase" => " icon-unlock"],
									["url" => "clientes-seguimiento.php", "id" => 12, "nombre" => " Seguimientos", "clase" => " icon-unlock"],
									["url" => "proveedores.php", "id" => 123, "nombre" => " Proveedores", "clase" => " icon-unlock"],
									["url" => "servicios.php", "id" => 154, "nombre" => " Servicios", "clase" => " icon-unlock"],
									["url" => "cotizaciones.php", "id" => 77, "nombre" => " Cotización", "clase" => " icon-file"],
									["url" => "pedidos.php", "id" => 151, "nombre" => " Pedido", "clase" => " icon-unlock"],
									["url" => "remisionbdg.php", "id" => 148, "nombre" => " Remisión", "clase" => " icon-unlock"],
									["url" => "facturas.php", "id" => 126, "nombre" => " Factura", "clase" => " icon-unlock"],
									["url" => "importacion.php", "id" => 133, "nombre" => " Importación", "clase" => " icon-unlock"],
									["divider" => true],
									["url" => "store-pedidos.php", "id" => 141, "nombre" => " Tienda virtual", "clase" => " icon-unlock"],
								];
								?>
									<?php foreach ($menuComercial as $pagina) { ?>
											<?php if (isset($pagina['divider'])) { ?>
													<li class="divider"></li>
											<?php } else { ?>
													<?php if (Modulos::validarRol([$pagina['id']], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
															<li><a href="<?= $pagina['url'] ?>"><i class="<?= $pagina['clase'] ?>"></i><?= $pagina['nombre'] ?></a></li>
													<?php } ?>
											<?php } ?>
									<?php } ?>						
							</ul>
						</div>
						</li>
						
						
						<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-desktop"></i> Soporte Operativo <b class="icon-angle-down"></b></a>
						<div class="dropdown-menu">
							<ul>
								<li><a href="clientes-tikets.php?tipo=3"><i class="icon-copy"></i> Tickets </a></li>
								<li><a href="productos-sop.php"><i class="icon-circle-blank"></i> Productos soporte</a></li>
                                <li><a href="facturacion.php"><i class="icon-list-ol"></i> Productos a clientes</a></li>
							</ul>
						</div>
						</li>
						
						
                        
                        
                        <li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-wrench"></i> Soporte técnico <b class="icon-angle-down"></b></a>
						<div class="dropdown-menu">
							<ul>
								<li><a href="remisiones.php"><i class="icon-wrench"></i> Soporte técnico </a></li>	
								<li><a href="tipos-equipos.php"><i class="icon-list-ol"></i> Tipos de equipos</a></li>
							</ul>
						</div>
						</li>

						<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-user"></i> Mi Cuenta <b class="icon-angle-down"></b></a>
						<div class="dropdown-menu">
							<ul>
								<li><a href="perfil-editar.php"><i class="icon-list-alt"></i> Editar perfil </a></li>
								<li><a href="mis-ventas.php"><i class="icon-shopping-cart"></i> Mis ventas </a></li>
								<li><a href="calendario.php"><i class="icon-calendar"></i> Mi calendario</a></li>
                                <li><a href="../salir.php"><i class="icon-signout"></i> Salir </a></li>
							</ul>
						</div>
						</li>
	

					</ul>
				</div>
				<div class="btn-toolbar pull-right notification-nav">
					

					<div class="btn-group">
						<div class="dropdown">
							<a href="../salir.php" class="btn btn-notification"><i class="icon-signout"></i></a>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

   
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
	
	$('[data-toggle="popover"]').popover();   
});
</script>
