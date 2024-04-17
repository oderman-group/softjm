<?php 
include("logica-menu.php");
?>	
<div class="loader"></div>

<?php if( $datosUsuarioActual['usr_id']==7 || isset($_SESSION['admin']) ){?>

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
					<ul class="nav draggable-menu"> <!--aqui agregamos la propiedad draggable-menu-->
					<li><a style="font-weight: bold; color: yellow; font-size: 14px;"><?= $_SESSION["dataAdicional"]["nombre_empresa"]; ?></a></li>
					<?php foreach ($menu as $menu_item) : ?>
						<li class="dropdown" id="<?=$menu_item['mod_id'];?>">
							<?php if (Modulos::validarAccesoModulo($configuracion['conf_id_empresa'], $menu_item['mod_id'], $conexionBdAdmin, $datosUsuarioActual)) { ?>
								<?php if (!empty($menu_item['ruta_pagina'])) : ?>
									<a href="<?= REDIRECT_ROUTE . '/' . $menu_item['ruta_pagina']; ?>">
										<i class="<?= $menu_item['mod_icon']; ?>"></i> <?= $menu_item['mod_nombre']; ?>
									</a>
								<?php else : ?>
									<a data-toggle="dropdown" class="dropdown-toggle" href="#">
										<i class="<?= $menu_item['mod_icon']; ?>"></i> <?= $menu_item['mod_nombre']; ?> <b class="icon-angle-down"></b>
									</a>
								<?php endif; ?>
							<?php } ?>

							<?php if (!empty($menu_item['submenus']) || !empty($menu_item['paginas'])) : ?>
								<div class="dropdown-menu">
									<ul>
										<?php foreach ($menu_item['submenus'] as $submenu) : ?>
											<?php if (!empty($submenu['paginas']) && Modulos::validarAccesoModulo($configuracion['conf_id_empresa'], $submenu['mod_id'], $conexionBdAdmin, $datosUsuarioActual)) : ?>
												<li class="dropdown-submenu">
													<a href="#">
														<i class="<?= $submenu['mod_icon']; ?>"></i> <?= $submenu['mod_nombre']; ?>
													</a>
													<div class="dropdown-menu">
														<ul>
															<?php foreach ($submenu['paginas'] as $pagina) : ?>
																<?php if (Modulos::validarRol([$pagina['mod_id_pagina']], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
																	<li>
																		<a href="<?= REDIRECT_ROUTE . '/' . $pagina['ruta_pagina']; ?>">
																			<i class="<?= $pagina['mod_icon']; ?>"></i> <?= $pagina['mod_nombre']; ?>
																		</a>
																	</li>
																<?php } ?>
															<?php endforeach; ?>
														</ul>
													</div>
												</li>
											<?php endif; ?>
										<?php endforeach; ?>
										<?php foreach ($menu_item['paginas'] as $pagina) : ?>
											<?php if (Modulos::validarRol([$pagina['mod_id_pagina']], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
												<li>
													<a href="<?= REDIRECT_ROUTE . '/' .  $pagina['ruta_pagina']; ?>">
														<i class="<?= $pagina['mod_icon']; ?>"></i> <?= $pagina['mod_nombre']; ?>
													</a>
												</li>
											<?php } ?>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
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
