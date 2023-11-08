<?php
$notificaciones = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones_seguimiento
INNER JOIN remisiones ON rem_id=remseg_id_remisiones AND rem_cliente='".$_SESSION["id_cliente"]."'
WHERE remseg_notificar_cliente=1 AND (remseg_visto_cliente=0 OR remseg_visto_cliente IS NULL)
");
$numNotf = mysqli_num_rows($notificaciones);
?>	
<!-- Navbar
    ================================================== -->
	<div class="navbar navbar-inverse top-nav">
		<div class="navbar-inner">
			<div class="container">
				<span class="home-link"><a href="index.php" class="icon-home"></a></span>
				<div class="nav-collapse">
					<ul class="nav">
						<li class="dropdown"><a href="index.php"><i class="icon-dashboard"></i> Inicio</a></li>
					</ul>
				</div>
				<div class="btn-toolbar pull-right notification-nav">
					<div class="btn-group">
						<div class="dropdown">
							<a class="btn btn-notification dropdown-toggle" data-toggle="dropdown"><i class="icon-globe"><?php if($numNotf>0){?><span class="notify-tip"><?=$numNotf;?></span><?php }?></i></a>
							<div class="dropdown-menu pull-right">
								<span class="notify-h"> Tienes <?=$numNotf;?> notificaciones</span>
                                <?php 
								$i=1;
								while($notf = mysqli_fetch_array($notificaciones, MYSQLI_BOTH)){
								if($i==6) break;
								?>
                                <a href="notificaciones-lista.php" class="msg-container clearfix"><span class="notification-thumb"><img src="images/notify-thumb.png" width="50" height="50" alt="user-thumb"></span><span class="notification-intro"> Nueva notificación (ID:<?=$notf["remseg_id"];?>) - <b><?=$notf['rem_equipo']?></b><span class="notify-time"> <?=$notf['remseg_fecha']?> </span></span></a>
                                <?php $i++;}?>
                                
								<a href="notificaciones-lista.php" class="btn btn-primary btn-large btn-block"> Ver todo</a>
							</div>
						</div>
					</div>
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
});
</script>