<?php
include("sesion.php");

$idPagina = 119;
include("includes/verificar-paginas.php");
include("includes/head.php");

$consultaProyecto = mysqli_query($conexionBdPrincipal, "SELECT * FROM proyectos 
WHERE proy_id='".$_GET["proy"]."'");
$proyecto = mysqli_fetch_array($consultaProyecto, MYSQLI_BOTH);
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
<script src="js/bootbox.js"></script>
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

<link rel="stylesheet" href="css/modal/jquery-ui.css">
<!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
  <script>
  $( function() {
    
	var dialog, form,
 
      // From http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#e-mail-state-%28type=email%29
      emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
      name = $( "#name" ),
      email = $( "#email" ),
      password = $( "#password" ),
      allFields = $( [] ).add( name ).add( email ).add( password ),
      tips = $( ".validateTips" );
	  

 
    function addUser() {
      
      if ( valid ) {
        $( "#users tbody" ).append( "<tr>" +
          "<td>" + name.val() + "</td>" +
          "<td>" + email.val() + "</td>" +
          "<td>" + password.val() + "</td>" +
        "</tr>" );
        dialog.dialog( "close" );
      }
      return valid;
    }
 
    dialog = $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 650,
      width: 450,
      modal: true,
      buttons: {
        Cancel: function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
        allFields.removeClass( "ui-state-error" );
      }
    });
 
    form = dialog.find( "form" ).on( "submit", function( event ) {
      event.preventDefault();
      addUser();
    });
 
    $( ".create-user" ).button().on( "click", function() {
      dialog.dialog( "open" );
    });
  } );
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
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?>:  <strong><?=$proyecto['proy_titulo'];?></strong></h3>
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="proyectos.php">Proyectos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            <?php include("includes/notificaciones.php");?>
			
            <p>
                <a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                <a href="proyectos-tareas-agregar.php?proy=<?=$_GET["proy"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
            </p>

            
            
			<div class="row-fluid">
				
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3><?=$paginaActual['pag_nombre'];?></h3>
						</div>
                        <?php
						if(isset($_GET["busqueda"]) and $_GET["busqueda"]!=""){
							$filtro = "AND (ptar_titulo LIKE '%".$_GET["busqueda"]."%' OR ptar_descripcion LIKE '%".$_GET["busqueda"]."%')";
						}else{
							$filtro = "";
						}
						?>
                        
						<?php
								$SQL = "SELECT * FROM proyectos_tareas
									INNER JOIN usuarios ON usr_id=ptar_responsable
									WHERE ptar_id_proyecto='".$_GET["proy"]."'
								";
						?>
						
						<div class="widget-container">
							<div style="border:thin; border-style:solid; height:150px; margin:10px;">
                            	<h4 align="center">-Busqueda general y paginación-</h4>
                                <p> 
                                    <form class="form-horizontal" action="<?=$_SERVER['PHP_SELF'];?>" method="get">
										<input type="hidden" name="proy" value="<?=$_GET["proy"];?>">
                                        <div class="search-box">
                                            <div class="input-append input-icon">
                                                <input class="search-input" placeholder="Buscar..." type="text" name="busqueda" value="<?=$_GET["busqueda"];?>">
                                                <i class=" icon-search"></i>
                                                <input class="btn" type="submit" name="buscar" value="Buscar">
                                            </div>
                                            <?php if(isset($_GET["busqueda"]) and $_GET["busqueda"]!=""){?> <a href="proyectos-tareas.php?proy=<?=$_GET["proy"];?>" class="btn btn-warning"><i class="icon-minus"></i> Quitar Filtro</a> <?php } ?>
                                        </div>
                                    </form>
                                    <?php include("includes/paginacion.php");?> 
                                </p>
                            </div>
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>No</th>
                                <th>Tarea</th>
                                <th>Inicio</th>
								<th>Fin</th>
								<th>Responsable</th>
								<th>Avance</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							
								$consulta = mysqli_query($conexionBdPrincipal, "SELECT * FROM proyectos_tareas
								INNER JOIN usuarios ON usr_id=ptar_responsable
								WHERE ptar_id_proyecto='".$_GET["proy"]."'
								LIMIT $inicio, $limite
								");
							
							$no = 1;
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
								
								
								$numeros = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "
								SELECT
								(SELECT count(ptar_id) FROM proyectos_tareas WHERE ptar_id_proyecto='".$res['proy_id']."')
								"), MYSQLI_BOTH);
								
								$color1='#FFF';
								if($numeros[0]==0){$color1='#FFF090';}
								
							?>
							<tr>
								
								<td><?=$no;?></td>                     

                                <td>
									<?=$res['ptar_titulo'];?>
									
                                    <h4 style="margin-top:5px;">
                                        <a href="proyectos-tareas-editar.php?id=<?=$res['ptar_id'];?>&proy=<?=$_GET["proy"];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>&nbsp;
										
                                        <a href="bd_delete/proyectos-avances-eliminar.php?get=36&id=<?=$res['ptar_id'];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
                                	</h4>
                                </td>
								
								<td><?=$res['ptar_inicio'];?></td>
								
								<td><?=$res['ptar_fin'];?></td>
								
								<td><?=$res['usr_nombre'];?></td>
								
								<td><?=$res['ptar_avance'];?>%</td>
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
