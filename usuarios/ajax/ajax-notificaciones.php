<?php include("sesion.php");?>
<?php
$notificaciones = mysqli_query($conexionBdPrincipal, "SELECT * FROM notificaciones
INNER JOIN clientes ON cli_id=not_cliente AND cli_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'
WHERE not_usuario='".$_SESSION["id"]."' AND not_visto=0 AND not_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."' LIMIT 0,5");
$numNotf = mysqli_num_rows($notificaciones);
?>


<a class="btn btn-notification dropdown-toggle" data-toggle="dropdown"><i class="icon-globe"><?php if($numNotf>0){?><span class="notify-tip"><?=$numNotf;?></span><?php }?></i></a>
							<div class="dropdown-menu pull-right">
								<span class="notify-h"> Tienes <?=$numNotf;?> notificaciones</span>
                                <?php 
								while($notf = mysqli_fetch_array($notificaciones)){
									$color = 'black';
									if($notf['not_varios']==1){$color = 'red';}
								?>
                                <a href="notificaciones-lista.php?idNot=<?=$notf['not_id']?>&idSeg=<?=$notf['not_seguimiento']?>" class="msg-container clearfix"><span class="notification-thumb"><img src="images/notify-thumb.png" width="50" height="50" alt="user-thumb"></span><span class="notification-intro" style="color: <?=$color;?>;"> <?=$notf['not_asunto']?> - <b><?=$notf['cli_nombre']?></b><span class="notify-time"> Hace un momento </span></span></a>
                                <?php }?>
                                
								<a href="notificaciones-lista.php" class="btn btn-primary btn-large btn-block"> Ver todo</a>
							</div>