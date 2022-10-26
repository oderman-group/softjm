<?php
switch($_SESSION["bd"]){
	case "odermancom_jm_crm":
		$bdEmpresa = "JMEQUIPOS";
	break;
		
	case "odermancom_orioncrm_exacta":
		$bdEmpresa = "EXACTA ING.";
	break;
		
	case "odermancom_orioncrm_demo":
		$bdEmpresa = "DEMO ORIÓN";
	break;
		
	case "odermancom_orioncrm_ingestore":
		$bdEmpresa = "INGESTORE";
	break;
		
	case "odermancom_orioncrm_orion":
		$bdEmpresa = "ORIÓN COMPANY";
	break;
		
	case "odermancom_orioncrm_asalliancesas":
		$bdEmpresa = "AS ALLIANCE SAS";
	break;	

	case "orioncrmcom_oscar":
		$bdEmpresa = "OSCAR B.";
	break;	
		
		
	default:
		$bdEmpresa = "Desc.";
	break;		
}
?>
<div class="loader"></div>

<?php if( $datosUsuarioActual['usr_tipo']==1 || isset($_SESSION['admin']) ){?>

	<div style="height:50px; width:100%; background-color:black; padding:2px; color:#42FF00; display:flex; justify-content: center; align-items: center;">
		<b>Sesion DB:&nbsp;</b> <?=$_SESSION["bd"]; ?>&nbsp;|&nbsp;
		<b>ID Company:&nbsp;</b> <?=$configu['conf_id_empresa']; ?>&nbsp;|&nbsp;
		<b>Current User ID:&nbsp;</b> <?=$_SESSION["id"]; ?>&nbsp;|&nbsp;
		<b>ID Page:&nbsp;</b> <?=$idPagina; ?>&nbsp;|&nbsp;
		<b>Versión PHP:&nbsp;</b> <?=phpversion(); ?>&nbsp;|&nbsp; 
		<b>Server:&nbsp;</b> <?=$_SERVER['SERVER_NAME']; ?>&nbsp;|&nbsp;
		<?php if( isset($_SESSION['admin']) ){?>
			<b>User Admin:&nbsp;</b> <?=$_SESSION['admin']; ?>&nbsp;|&nbsp;
			<a href="return-admin-panel.php" style="color:white; text-decoration:underline;">RETURN TO ADMIN PANEL</a>
		<?php }?>	
		
	</div>

<?php }?>

<!--
<style type="text/css">
	.navbar-inverse .navbar-inner {
		background-color: #DA2201;
		}	
</style>
	-->

<div class="navbar navbar-inverse top-nav">
		<div class="navbar-inner">
			<div class="container">
				<span class="home-link"><a href="index.php" class="icon-home"></a></span>
				<div class="nav-collapse">
					<ul class="nav">
						<li><a style="font-weight: bold; color: yellow; font-size: 14px;"><?=$bdEmpresa;?></a></li>

						<li class="dropdown"><a href="index.php"><i class="icon-dashboard"></i> Escritorio</a></li>

						<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-file-alt"></i> Sistema <b class="icon-angle-down"></b></a>
						<div class="dropdown-menu">
							<ul>
								<li class="dropdown-submenu"><a href="#"><i class="icon-minus-sign"></i> Parametrización</a>
								<div class="dropdown-menu">
									<ul>
										<li><a href="configuracion.php"><i class=" icon-file-alt"></i> Configuración</a></li>
										<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15){?>
											<li><a href="metricas.php?id=1"><i class="icon-cogs"></i> Métricas </a></li>
										<?php }?>
										<li><a href="estructura-mensajes.php"><i class=" icon-file-alt"></i> Estructura de mensajes</a></li>
									</ul>
								</div>
								</li>
								<li><a href="#"><i class=" icon-unlock"></i>Módulos</a></li>
                                <li><a href="#"><i class="icon-file"></i> Páginas</a></li>
								<li><a href="buzon.php"><i class="icon-envelope"></i> Buzón de salida </a></li>

								<li class="dropdown-submenu"><a href="#"><i class="icon-minus-sign"></i> Documentación</a>
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

						<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-file-alt"></i> Administración <b class="icon-angle-down"></b></a>
						<div class="dropdown-menu">
							<ul>
								<li><a href="roles.php"><i class=" icon-unlock"></i>Roles</a></li>
								<li><a href="areas.php"><i class=" icon-unlock"></i>Áreas</a></li>
								<li><a href="zonas.php"><i class=" icon-unlock"></i>Zonas</a></li>
								<li><a href="sucursales.php"><i class=" icon-unlock"></i>Mis sucursales</a></li>
								<li><a href="bodegas.php"><i class=" icon-unlock"></i>Bodegas</a></li>
                                <li><a href="usuarios.php"><i class="icon-file"></i> Usuarios</a></li>
								<li><a href="historial-acciones.php"><i class="icon-time"></i> Historial de acciones </a></li>
								<li><a href="proyectos.php"><i class="icon-ok-sign"></i> Proyectos </a></li>
								<li><a href="encuesta.php"><i class="icon-file"></i> Encuestas </a></li>
															
								<?php if($_SESSION["bd"]=='odermancom_jm_crm' and $datosUsuarioActual['usr_tipo']==1){?>
									<li class="divider"></li>
									<li><a href="clientes-orion.php"><i class="icon-group"></i> CLientes ORION </a></li>
								<?php }?>
								<li class="dropdown-submenu"><a href="#"><i class="icon-minus-sign"></i> Informes</a>
								<div class="dropdown-menu">
									<ul>
										<li><a href="#><i class=" icon-file-alt"></i> Informes planos</a></li>
										<li><a href="#"><i class=" icon-file-alt"></i> Excel</a></li>
										<li><a href="#"><i class=" icon-file-alt"></i> Gráficos</a></li>
										<li><a href="estadisticas.php"><i class="icon-bar-chart"></i> Estadisticas</a></li>
									</ul>
								</div>
								</li>
							</ul>
						</div>
						</li>

						<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-file-alt"></i> Comercial <b class="icon-angle-down"></b></a>
						<div class="dropdown-menu">
							<ul>
							<li class="dropdown-submenu"><a href="#"><i class="icon-minus-sign"></i> Clientes</a>
								<div class="dropdown-menu">
									<ul>
										<li><a href="clientes.php"><i class=" icon-file-alt"></i> Clientes</a></li>
										<li><a href="dealer.php"><i class=" icon-file-alt"></i> Grupos</a></li>
										<li><a href="clientes-filtro.php"><i class=" icon-file-alt"></i> Mercadeo</a></li>
										<li><a href="enviar-portafolios.php"><i class="icon-list-ul"></i> Enviar portafolios </a></li>
										<li class="divider"></li>
										<li><a href="publicidad.php"><i class=" icon-file-alt"></i> Publicidad</a></li>
										<li><a href="cupones.php"><i class=" icon-file-alt"></i> Cupones</a></li>
									</ul>
								</div>
							</li>
							<li><a href="#"><i class=" icon-unlock"></i>Tickets/Negociación</a></li>
							<li><a href="#"><i class=" icon-unlock"></i>Seguimientos</a></li>	
							<li class="dropdown-submenu"><a href="#"><i class="icon-minus-sign"></i> Productos</a>
								<div class="dropdown-menu">
									<ul>
										<li><a href="#"><i class=" icon-file-alt"></i> Productos</a></li>
										<li><a href="#"><i class=" icon-file-alt"></i> Combos</a></li>
										<li><a href="#"><i class=" icon-file-alt"></i>Categorías</a></li>
									</ul>
								</div>
							</li>
											
								<li><a href="proveedores.php"><i class=" icon-unlock"></i>Proveedores</a></li>
								<li><a href="servicios.php"><i class=" icon-unlock"></i>Servicios</a></li>
                                <li><a href="cotizaciones.php"><i class="icon-file"></i> Cotización</a></li>
								<li><a href="pedidos.php"><i class=" icon-unlock"></i>Pedido</a></li>
								<li><a href="remisionbdg.php"><i class=" icon-unlock"></i>Remisión</a></li>
								<li><a href="facturas.php"><i class=" icon-unlock"></i>Factura</a></li>
								<li><a href="importacion.php"><i class=" icon-unlock"></i>Importación</a></li>
								<li class="divider"></li>
								<li><a href="store-pedidos.php"><i class=" icon-unlock"></i>Tienda virtual</a></li>
								
							</ul>
						</div>
						</li>
						
						
						<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-desktop"></i> Soporte Operativo <b class="icon-angle-down"></b></a>
						<div class="dropdown-menu">
							<ul>
								<li><a href="clientes-tikets.php?tipo=3"><i class="icon-group"></i> Tickets </a></li>
								<li><a href="productos-sop.php"><i class="icon-tasks"></i> Productos soporte</a></li>
                                <li><a href="facturacion.php"><i class="icon-list-ol"></i> Productos a clientes</a></li>
							</ul>
						</div>
						</li>
						
						
                        
                        
                        <li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-suitcase"></i> Soporte técnico <b class="icon-angle-down"></b></a>
						<div class="dropdown-menu">
							<ul>
								<li><a href="remisiones.php"><i class="icon-ok-sign"></i> Soporte técnico </a></li>	
								<li><a href="tipos-equipos.php"><i class="icon-list-ol"></i> Tipos de equipos</a></li>
							</ul>
						</div>
						</li>

						<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-user"></i> Mi Cuenta <b class="icon-angle-down"></b></a>
						<div class="dropdown-menu">
							<ul>
								<li><a href="perfil-editar.php"><i class="icon-user"></i> Editar perfil </a></li>
								<li><a href="mis-ventas.php"><i class="icon-shopping-cart"></i> Mis ventas </a></li>
								<li><a href="calendario.php"><i class="icon-envelope"></i> Mi calendario</a></li>
                                <li><a href="../salir.php"><i class="icon-lock"></i> Salir </a></li>
							</ul>
						</div>
						</li>
	

					</ul>
				</div>
				<div class="btn-toolbar pull-right notification-nav">
					

					<div class="btn-group">
						<div class="dropdown">
							<a href="../salir.php" class="btn btn-notification"><i class="icon-lock"></i></a>
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
