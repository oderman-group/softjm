<?php include("sesion.php");?>
<?php
$idPagina = 68;
$paginaActual['pag_nombre'] = "Materiales";
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>
<?php
$producto = mysql_fetch_array(mysql_query("SELECT * FROM productos_soptec WHERE prod_id='".$_GET["pdto"]."'",$conexion));
?>
<!-- styles -->


<link href="css/tablecloth.css" rel="stylesheet">

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
            /*$( function () {
		  // Set the classes that TableTools uses to something suitable for Bootstrap
		  $.extend( true, $.fn.DataTable.TableTools.classes, {
			  "container": "btn-group",
			  "buttons": {
				  "normal": "btn",
				  "disabled": "btn disabled"
			  },
			  "collection": {
				  "container": "DTTT_dropdown dropdown-menu",
				  "buttons": {
					  "normal": "",
					  "disabled": "disabled"
				  }
			  }
		  } );
		  // Have the collection use a bootstrap compatible dropdown
		  $.extend( true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
			  "collection": {
				  "container": "ul",
				  "button": "li",
				  "liner": "a"
			  }
		  } );
		  });
		  */
            $(function () {
                $('#data-table').dataTable({
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
                    /*"oTableTools": {
			"aButtons": [
				"copy",
				"print",
				{
					"sExtends":    "collection",
					"sButtonText": 'Save <span class="caret" />',
					"aButtons":    [ "csv", "xls", "pdf" ]
				}
			]
		}*/
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

<?php include("includes/funciones-js.php");?>
</head>
<body>
<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?> de <b><?=$producto['prod_nombre'];?></b></h3>
						<ul class="top-right-toolbar">
							<li><a data-toggle="dropdown" class="dropdown-toggle blue-violate" href="#" title="Users"><i class="icon-user"></i></a>
							</li>
							<li><a href="#" class="green" title="Upload"><i class=" icon-upload-alt"></i></a></li>
							<li><a href="#" class="bondi-blue" title="Settings"><i class="icon-cogs"></i></a></li>
						</ul>
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
                        <li><a href="productos.php">Productos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?> de <b><?=$producto['prod_nombre'];?></b></li>
					</ul>
				</div>
			</div>
            <?php include("includes/notificaciones.php");?>
            
            <div class="row-fluid">
				<div class="span12">
					<div class="hero-unit">
						<h2>¿Materiales?</h2>
						<p>
							Puedes añadir varios materiales a cada uno de tus productos, pueden ser documentos (PDF, Word, Excel) o videos de YouTube, con el objetivo de que el cliente pueda acceder a ellos cuando adquiera el producto.
						</p>
					</div>
				</div>
			</div>
            
            <p>
            	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                <a href="productos-materiales-agregar.php?pdto=<?=$_GET["pdto"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
            </p>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3><?=$paginaActual['pag_nombre'];?> de <b><?=$producto['prod_nombre'];?></b></h3>
						</div>
						<div class="widget-container">
							<p></p>
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>No</th>
                                <th>Nombre</th>
                                <th>Material</th>
								<th>Tipo</th>
                                <th>Visible</th>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$consulta = mysql_query("SELECT * FROM productos_materiales WHERE ppmt_producto='".$_GET["pdto"]."'",$conexion);
							$no = 1;
							while($res = mysql_fetch_array($consulta)){
								switch($res[2]){
									case 1: $categ = 'Documento'; break;
									case 2: $categ = 'Video'; break;
									case 3: $categ = 'Softare'; break;
								}
							?>
							<tr>
								<td><?=$no;?></td>
                                <td><?=$res[5];?></td>
                                <td><?php if($res[2]==1 or $res[2]==3) {echo '<a href="files/materiales/'.$res[1].'" target="_blank">'.$res[1].'</a>';} else {echo '<a href="https://www.youtube.com/watch?v='.$res[1].'" target="_blank">'.$res[1].'</a>';}?></td>
                                <td><?=$categ;?></td>
                                <td><?=$res[3];?></td>
                                <td><h4>
                                    <a href="productos-materiales-editar.php?id=<?=$res[0];?>&pdto=<?=$_GET["pdto"];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
                                    <a href="sql.php?id=<?=$res[0];?>&get=17" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
                                </h4></td>
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
	<?php include("includes/pie.php");?>
</div>
</body>
</html>