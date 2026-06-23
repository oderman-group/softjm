<?php 
include("logica-menu.php");
require_once RUTA_PROYECTO . '/usuarios/class/Notificacion.php';
?>
<div class="loader"></div>

<?php if($datosUsuarioActual['usr_id']==7 || $datosUsuarioActual['usr_id']==2 || isset($_SESSION['admin']) ){?>

	<div class="top-nav-dev-bar" style="--dev-bar-bg:<?=COLOR_BARRA_DEV;?>; --dev-bar-color:<?=COLOR_LETRA_BARRA_DEV;?>;">
		<div class="top-nav-dev-bar__grid">
			<div class="top-nav-dev-bar__item"><strong>Sesión DB:</strong> <?= htmlspecialchars($_SESSION["bd"] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
			<div class="top-nav-dev-bar__item"><strong>ID Company:</strong> <?= (int) $configuracion['conf_id_empresa'] ?></div>
			<div class="top-nav-dev-bar__item"><strong>User ID:</strong> <?= (int) $_SESSION["id"] ?></div>
			<div class="top-nav-dev-bar__item"><strong>ID Page:</strong> <?= (int) $idPagina ?></div>
			<div class="top-nav-dev-bar__item"><strong>ID Module:</strong> <?= (int) $paginaActual['pag_id_modulo'] ?></div>
			<div class="top-nav-dev-bar__item"><strong>PHP:</strong> <?= phpversion() ?></div>
			<div class="top-nav-dev-bar__item"><strong>Server:</strong> <?= htmlspecialchars($_SERVER['SERVER_NAME'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
			<div class="top-nav-dev-bar__item top-nav-dev-bar__item--wide"><strong>BD:</strong> <?= htmlspecialchars(SERVER . ' - ' . MAINBD, ENT_QUOTES, 'UTF-8') ?></div>
			<?php if (isset($_SESSION['admin'])) { ?>
				<div class="top-nav-dev-bar__item"><strong>Admin:</strong> <?= htmlspecialchars($_SESSION['admin'], ENT_QUOTES, 'UTF-8') ?></div>
				<div class="top-nav-dev-bar__item top-nav-dev-bar__item--wide">
					<a href="return-admin-panel.php" class="top-nav-dev-bar__link">RETURN TO ADMIN PANEL</a>
				</div>
			<?php } ?>
		</div>
	</div>

<?php }

require_once(RUTA_PROYECTO."/usuarios/config/colores-encabezado.php");

$nombreEmpresa = htmlspecialchars($_SESSION["dataAdicional"]["nombre_empresa"] ?? '', ENT_QUOTES, 'UTF-8');
$nombreUsuario = !empty($datosUsuarioActual['usr_nombre'])
	? htmlspecialchars($datosUsuarioActual['usr_nombre'])
	: (!empty($datosUsuarioActual['usr_seudonimo']) ? htmlspecialchars($datosUsuarioActual['usr_seudonimo']) : 'Usuario');
?>

<div class="navbar navbar-inverse top-nav">
		<div class="navbar-inner">
			<div class="container top-nav-container">
				<div class="top-nav-header-bar">
					<button type="button" class="btn btn-navbar top-nav-toggle" aria-expanded="false" aria-label="Abrir menú de navegación">
						<span class="top-nav-toggle-bars" aria-hidden="true">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</span>
					</button>
					<span class="home-link"><a href="index.php" class="icon-home" aria-label="Inicio"></a></span>
					<span class="top-nav-brand-mobile" title="<?= $nombreEmpresa ?>"><?= $nombreEmpresa ?></span>
					<div class="btn-toolbar pull-right notification-nav top-nav-toolbar">
					<?php
					$notificaciones = mysqli_query($conexionBdPrincipal, "SELECT * FROM notificaciones
					INNER JOIN clientes ON cli_id=not_cliente AND cli_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'
					WHERE not_usuario='".$_SESSION["id"]."' AND not_visto=0 AND not_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."' LIMIT 0,5");
					$numNotf = mysqli_num_rows($notificaciones);
					?>
						<div class="btn-group header-notify-menu">
							<div class="dropdown">
							<a class="btn btn-notification dropdown-toggle top-nav-notify-toggle" data-toggle="dropdown" href="#" aria-label="Notificaciones" title="Notificaciones" aria-expanded="false"><i class="icon-globe"><?php if($numNotf>0){?><span class="notify-tip"><?=$numNotf;?></span><?php }?></i></a>
								<div class="dropdown-menu pull-right top-nav-notify-dropdown">
									<div class="top-nav-mobile-panel-head top-nav-notify-panel-head">
										<span class="top-nav-mobile-panel-title">Notificaciones</span>
										<button type="button" class="top-nav-mobile-panel-close" data-mobile-close="notify" aria-label="Cerrar notificaciones">&times;</button>
									</div>
									<span class="notify-h"> Tienes <?=$numNotf;?> notificaciones</span>
	                                <?php 
									while($notf = mysqli_fetch_array($notificaciones)){
										$color = 'black';
										if($notf['not_varios']==1){$color = 'red';}
									?>
	                                <a href="<?= htmlspecialchars(Notificacion::obtenerUrlDestino($notf)) ?>" class="msg-container clearfix"><span class="notification-thumb"><img src="images/notify-thumb.png" width="50" height="50" alt="user-thumb"></span><span class="notification-intro" style="color: <?=$color;?>;"> <?=$notf['not_asunto']?> - <b><?=$notf['cli_nombre']?></b><span class="notify-time"> <?=$notf['not_fecha']?> </span></span></a>
	                                <?php }?>
	                                
									<a href="notificaciones-lista.php" class="btn btn-primary btn-large btn-block"> Ver todo</a>
								</div>
							</div>
						</div>

						<div class="btn-group dropdown header-user-menu">
							<a class="btn btn-notification dropdown-toggle top-nav-user-toggle" data-toggle="dropdown" href="#" title="Mi cuenta" aria-label="Mi cuenta" aria-expanded="false">
								<i class="icon-user"></i>
								<span class="header-user-name"><?= $nombreUsuario ?></span>
								<b class="icon-angle-down header-user-caret"></b>
							</a>
							<ul class="dropdown-menu pull-right header-user-dropdown">
								<li class="top-nav-mobile-panel-head top-nav-user-panel-head">
									<span class="top-nav-mobile-panel-title">Mi cuenta</span>
									<button type="button" class="top-nav-mobile-panel-close" data-mobile-close="user" aria-label="Cerrar menú de usuario">&times;</button>
								</li>
								<li class="header-user-dropdown-head">
									<span class="header-user-dropdown-label">Mi cuenta</span>
									<strong class="header-user-dropdown-name"><?= $nombreUsuario ?></strong>
								</li>
								<li class="divider"></li>
								<li><a href="<?= REDIRECT_ROUTE ?>/usuarios/mis-ventas.php"><i class="icon-shopping-cart"></i> Mis ventas</a></li>
								<li><a href="<?= REDIRECT_ROUTE ?>/usuarios/calendario.php"><i class="icon-calendar"></i> Mi calendario</a></li>
								<li><a href="<?= REDIRECT_ROUTE ?>/usuarios/perfil-editar.php"><i class="icon-bar-chart"></i> Editar perfil</a></li>
								<li class="divider"></li>
								<li><a href="<?= REDIRECT_ROUTE ?>/salir.php"><i class="icon-signout"></i> Salir</a></li>
							</ul>
						</div>
					</div>
				</div>

				<div class="nav-collapse collapse top-nav-main-collapse" aria-hidden="true">
					<ul class="nav draggable-menu">
					<li class="top-nav-brand-desktop"><a class="top-nav-brand-link"><?= $nombreEmpresa ?></a></li>
					<?php foreach ($menu as $menu_item) : ?>
						<li class="dropdown" id="<?=$menu_item['mod_id'];?>">
							<?php if (Modulos::validarAccesoModulo($configuracion['conf_id_empresa'], $menu_item['mod_id'], $conexionBdAdmin, $datosUsuarioActual)) { ?>
								<?php if (!empty($menu_item['ruta_pagina'])) : ?>
									<a href="<?= REDIRECT_ROUTE . '/' . $menu_item['ruta_pagina']; ?>">
										<i class="<?= $menu_item['mod_icon']; ?>"></i> <?= $menu_item['mod_nombre']; ?>
									</a>
								<?php else : ?>
									<a href="#" class="dropdown-toggle top-nav-menu-trigger" role="button" aria-expanded="false">
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
													<a href="#" class="top-nav-submenu-trigger" role="button" aria-expanded="false">
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
			</div>
		</div>
	</div>
<div class="top-nav-mobile-backdrop" aria-hidden="true"></div>

<script src="js/top-nav-mobile.js"></script>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover();
});
</script>
