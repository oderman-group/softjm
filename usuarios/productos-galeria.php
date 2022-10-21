<?php include("sesion.php");?>
<?php
$idPagina = 82;
$paginaActual['pag_nombre'] = "Galería de productos";
?>
<?php include("verificar-paginas.php");?>
<?php include("head.php");?>
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

body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}

/* Button used to open the contact form - fixed at the bottom of the page */
.open-button {
  background-color: #555;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: fixed;
  bottom: 23px;
  right: 28px;
  width: 280px;
}

/* The popup form - hidden by default */
.form-popup {
  display: none;
  position: fixed;
  bottom: 0;
  right: 15px;
  border: 3px solid #f1f1f1;
  z-index: 9;
}

/* Add styles to the form container */
.form-container {
  max-width: 300px;
  padding: 10px;
  background-color: white;
}

/* Full-width input fields */
.form-container input[type=text], .form-container input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
}

/* When the inputs get focus, do something */
.form-container input[type=text]:focus, .form-container input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Set a style for the submit/login button */
.form-container .btn {
  background-color: #4CAF50;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container .cancel {
  background-color: red;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover {
  opacity: 1;
}
</style>
</head>
<body>
<div class="layout">
	<?php include("encabezado.php");?>
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
			
			
			
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?></h3>
					</div>
					<ul class="breadcrumb">
						<li><a href="#" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="productos.php">Productos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
			
			
			<p>
				<a href="#" class="btn btn-danger open-button" onclick="openForm()"><i class="icon-plus"></i> Agregar foto</a>
			</p>
			
			<div class="row-fluid">
				<div class="span12">
					<div id="container">
						<?php
						$consulta = mysql_query("SELECT * FROM productos_galeria 
						INNER JOIN productos ON prod_id=pgal_producto
						WHERE pgal_producto='".$_GET["id"]."' $filtro
						",$conexion);
						$no = 1;
						while($res = mysql_fetch_array($consulta)){
						?>
						<div class="item">
							<div class="thumbnail">
								<img src="files/productos/galeria/<?=$res['pgal_foto'];?>" width="100" height="100">
								<div class="caption">
									<h4><?=$res['pgal_foto'];?></h4>
									<p>
										<a class="btn btn-primary" href="#">Reemplazar</a>
										<a class="btn btn-primary" href="sql.php?get=57&idItem=<?=$res['pgal_id'];?>" onClick="if(!confirm('Seguro desea eliminar esta foto?')){return false;}">Eliminar</a>
									</p>
								</div>
							</div>
						</div>
                        <?php $no++;}?>

					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="form-popup" id="myForm">
  <form action="sql.php" method="post" enctype="multipart/form-data" class="form-container">
	  <input type="hidden" value="64" name="idSql">
	  <input type="hidden" value="<?=$_GET["id"];?>" name="id">
    <h1>Nueva foto</h1>

    <label for="archivo"><b>Foto</b></label>
    <input type="file" name="archivo" required>


    <button type="submit" class="btn">Subir foto</button>
    <button type="button" class="btn cancel" onclick="closeForm()">Cerrar</button>
  </form>
</div>

<script>
function openForm() {
  document.getElementById("myForm").style.display = "block";
}

function closeForm() {
  document.getElementById("myForm").style.display = "none";
}
</script>
	
	
	<?php include("pie.php");?>
</div>
</body>
</html>