<?php include("sesion.php");?>
<?php
$idPagina = 82;
$paginaActual['pag_nombre'] = "Tutoriales";
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>

<!-- styles -->

<!--[if IE 7]>
<link rel="stylesheet" href="css/font-awesome-ie7.min.css">
<![endif]-->


<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
<![endif]-->
<link href="css/aristo-ui.css" rel="stylesheet">
<link href="css/elfinder.css" rel="stylesheet">

<!--============ javascript ===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.masonry.js"></script>
<script src="js/jquery.masonry.js"></script>
<script src="js/modernizr-transitions.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script type="text/javascript">
$(function(){
  $('#container').masonry({
    // options
    itemSelector : '.item',
	columnWidth : 240
  });
});
</script>
<style>
.item{ width:220px; margin:10px; float:left;}
.item{ margin-left:0px !important; margin-top:0px !important; margin-bottom:20px !important;}
.masonry,
.masonry .masonry-brick {
  -webkit-transition-duration: 0.7s;
     -moz-transition-duration: 0.7s;
      -ms-transition-duration: 0.7s;
       -o-transition-duration: 0.7s;
          transition-duration: 0.7s;
}
.masonry {
  -webkit-transition-property: height, width;
     -moz-transition-property: height, width;
      -ms-transition-property: height, width;
       -o-transition-property: height, width;
          transition-property: height, width;
}
.masonry .masonry-brick {
  -webkit-transition-property: left, right, top;
     -moz-transition-property: left, right, top;
      -ms-transition-property: left, right, top;
       -o-transition-property: left, right, top;
          transition-property: left, right, top;
}
</style>
</head>
<body>
<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?></h3>
						<ul class="top-right-toolbar">
							<li><a data-toggle="dropdown" class="dropdown-toggle blue-violate" href="#" title="Users"><i class="icon-user"></i></a>
							</li>
							<li><a href="#" class="green" title="Upload"><i class=" icon-upload-alt"></i></a></li>
							<li><a href="#" class="bondi-blue" title="Settings"><i class="icon-cogs"></i></a></li>
						</ul>
					</div>
					<ul class="breadcrumb">
						<li><a href="#" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="#">Inicio</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div id="container">
						<?php
						$tuto = array(array("2avvphM50iI", "Inicio y notificaciones"), array("fkRv5nAUMvQ", "Estádisticas"), array("sWcQBj492H4", "Cotizaciones"), array("ESGYu_UCn7o", "Facturación y O. Serv"), array("JN3tBeQO6lk", "Grupos"), array("NcJt0HRSYmA", "Categorías y Prod."), array("87f4b-RGap0", "Enviar mensajes"), array("lKMonwyEfWg", "Encuestas"), array("fb0pNapISgU", "Seg. Clientes"), array("8uxSAaChONQ", "Clientes"), array("0d16M7HoJkc", "His. Acciones"), array("1FIBk15Diok", "Roles"), array("E4IGQpa7Wyc", "Usuarios"), array("KlnKJH0lhto", "Configuración"), array("V0iDtzNOXao", "Editar Perfil"), array("DQtmoBzNTCo", "Ingreso al CRM"));
						$con = count($tuto);
						$i=0;
						while($i<$con){
						?>
						<div class="item">
							<div class="thumbnail">
								<iframe width="210" height="210" src="https://www.youtube.com/embed/<?=$tuto[$i][0];?>?rel=0" frameborder="0" allowfullscreen></iframe>
								<div class="caption">
									<h4><?=$tuto[$i][1];?></h4>
									<p><a class="btn btn-primary" href="https://www.youtube.com/watch?v=<?=$tuto[$i][0];?>" target="_blank">Ver en Youtube</a></p>
								</div>
							</div>
						</div>
                        <?php $i++;}?>

					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include("includes/pie.php");?>
</div>
</body>
</html>