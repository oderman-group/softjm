<?php include("sesion.php");?>
<?php
$idPagina = 108;
$paginaActual['pag_nombre'] = "Tareas";
?>
<?php include("verificar-paginas.php");?>
<?php include("head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>
<?php
$proyecto = mysql_fetch_array(mysql_query("SELECT * FROM proyectos 
WHERE proy_id='".$_GET["proy"]."'",$conexion));
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
<link href="css/tablecloth.css" rel="stylesheet">
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
  
<?php include("funciones-js.php");?>
</head>
<body>



<div class="layout">
	<?php include("encabezado.php");?>
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?>:  <strong><?=$proyecto['proy_titulo'];?></strong></h3>
						<ul class="top-right-toolbar">
							<li><a data-toggle="dropdown" class="dropdown-toggle blue-violate" href="#" title="Users"><i class="icon-user"></i></a>
							</li>
							<li><a href="#" class="green" title="Upload"><i class=" icon-upload-alt"></i></a></li>
							<li><a href="#" class="bondi-blue" title="Settings"><i class="icon-cogs"></i></a></li>
						</ul>
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="proyectos.php">Proyectos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            <?php include("notificaciones.php");?>
			
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
                                    <?php include("paginacion.php");?> 
                                </p>
                            </div>
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>No</th>
                                <th>Tarea</th>
                                <th>Inicio</th>
								<th>Fin</th>
								<th>Asignada por</th>
								<th>Responsable</th>
								<th>Avance</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							
								$consulta = mysql_query("SELECT * FROM proyectos_tareas
								INNER JOIN usuarios ON usr_id=ptar_responsable
								WHERE ptar_id_proyecto='".$_GET["proy"]."'
								LIMIT $inicio, $limite
								",$conexion);
							
							$no = 1;
							while($res = mysql_fetch_array($consulta)){
								
								$creada = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usr_id='".$res["ptar_creada_usuario"]."'",$conexion));
								
								$numeros = mysql_fetch_array(mysql_query("
								SELECT
								(SELECT count(ptar_id) FROM proyectos_tareas WHERE ptar_id_proyecto='".$res['proy_id']."')
								",$conexion));
								
								$color1='#FFF';
								if($numeros[0]==0){$color1='#FFF090';}
								
							?>
							<tr>
								
								<td><?=$no;?></td>                     

                                <td>
									<?=$res['ptar_titulo'];?>
									
                                    <h4 style="margin-top:5px;">
										<?php if($res['ptar_creada_usuario']==$_SESSION["id"]){?>
                                        <a href="proyectos-tareas-editar.php?id=<?=$res['ptar_id'];?>&proy=<?=$_GET["proy"];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>&nbsp;
										
                                        <a href="sql.php?get=36&id=<?=$res['ptar_id'];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
										<?php }?>
                                	</h4>
                                </td>
								
								<td><?=$res['ptar_inicio'];?></td>
								
								<td><?=$res['ptar_fin'];?></td>
								
								<td><?=$creada['usr_nombre'];?></td>
								
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
	<?php include("pie.php");?>
</div>
</body>
</html>