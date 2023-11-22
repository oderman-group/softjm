<?php
include("sesion.php");

if(is_numeric($_GET["idSeg"])){
	mysqli_query($conexionBdPrincipal,"UPDATE remisiones_seguimiento SET remseg_visto_cliente=1, remseg_fecha_visto=now()
	WHERE remseg_id='".$_GET["idSeg"]."' AND (remseg_visto_cliente=0 OR remseg_visto_cliente IS NULL)
	");
}

include("head.php");
?>
<link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>
<!--fav and touch icons -->
<link rel="shortcut icon" href="ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
<!--============j avascript===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.tablecloth.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script src="js/ZeroClipboard.js"></script>
<script src="js/dataTables.bootstrap.js"></script>
<script src="js/TableTools.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script type="text/javascript">
            $(function () {
                $('#data-table').dataTable({
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
                });
            });
            $(function () {
                $('.tbl-simple').dataTable({
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
                });
            });
			
			$(function () {
			$(".tbl-paper-theme").tablecloth({
          theme: "paper"
		   });
			});
			
		$(function () {
			$(".tbl-dark-theme").tablecloth({
          theme: "dark"
		   });
		});
			$(function () {
                $('.tbl-paper-theme,.tbl-dark-theme').dataTable({
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
                });
	

            });
        </script>
</head>
<body>
<div class="layout">
	<?php include("encabezado.php");?>
    
    <?php include("barra-izq.php");?>
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?></h3>
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            <?php include("notificaciones.php");?>
            

			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3><?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<p></p>
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>No</th>
                                <th>Fecha</th>
                                <th>Contenido</th>
								<th>Equipo</th>
								<th>Referencia</th>
                                <th>Serial</th>
                                <th>Estado</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones_seguimiento
							INNER JOIN remisiones ON rem_id=remseg_id_remisiones AND rem_cliente='".$_SESSION["id_cliente"]."'
							WHERE remseg_notificar_cliente=1
							ORDER BY remseg_id DESC
							");
							$no = 1;
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
								switch($res['remseg_visto_cliente']){
									case 0: $estado = 'Pendiente'; $etiquetaE='important'; break;
									case 1: $estado = 'Visto'; $etiquetaE='success'; break;
								}
								
								$colorFondo = '';
								if($_GET["idSeg"]==$res['remseg_id']){$colorFondo = 'aquamarine';}
							?>
							<tr>
								<td style="background-color:<?=$colorFondo;?>;"><?=$no;?></td>
                                <td style="background-color:<?=$colorFondo;?>;"><?=$res['remseg_fecha'];?></td>
                                <td style="background-color:<?=$colorFondo;?>;">
									<?php if($_GET["idSeg"]==$res['remseg_id'] or $res['remseg_visto_cliente']==1){echo $res['remseg_comentario'];}else{?>
									<a href="notificaciones-lista.php?idSeg=<?=$res['remseg_id'];?>">Ver contenido</a> 
									<?php }?>
								</td>
                                <td style="background-color:<?=$colorFondo;?>;"><?=$res['rem_equipo'];?></td>
								<td style="background-color:<?=$colorFondo;?>;"><?=$res['rem_referencia'];?></td>
                                <td style="background-color:<?=$colorFondo;?>;"><?=$res['rem_serial'];?></td>
                                <td style="background-color:<?=$colorFondo;?>;"><span class="label label-<?=$etiquetaE;?>"><?=$estado;?></span></td>
							</tr>
                            <?php $no++;}?>
							</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
	
		
			</div>
		</div>
	</div>
	<?php include("pie.php");?>
</div>
</body>
</html>