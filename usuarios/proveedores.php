<?php include("sesion.php");?>
<?php
$idPagina = 123;
$tituloPagina = "Proveedores";
?>
<?php include("verificar-paginas.php");?>
<?php include("head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>
<!-- styles -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="css/font-awesome.css">

<!--[if IE 7]>
            <link rel="stylesheet" href="css/font-awesome-ie7.min.css">
        <![endif]-->
<link href="css/styles.css" rel="stylesheet">
<link href="css/theme-blue.css" rel="stylesheet">

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
    
    <?php include("barra-izq.php");?>
	<div class="main-wrapper">
		<div class="container-fluid">
            <?php include("notificaciones.php");?>
            <p>
                <a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                <a href="proveedores-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
				<a href="proveedores-importar.php" class="btn btn-success"><i class="icon-upload"></i> Carga masiva</a>
            </p>

            
            
			<div class="row-fluid">
				<div class="span2">
					<div class="content-widgets light-gray">
						<div class="widget-head bondi-blue">
							<h5 align="center" style="color:white;">DEPARTAMENTOS</h5>
						</div>

					<div class="widget-container">
                <a href="proveedores.php" style="margin-bottom:10px;">TODOS</a><br>
                <?php
                if($datosUsuarioActual[3]==1){
					$departamentos = mysql_query("SELECT * FROM localidad_departamentos ORDER BY dep_nombre",$conexion);
				}else{
					$departamentos = mysql_query("SELECT * FROM localidad_departamentos
					INNER JOIN zonas_usuarios ON zpu_usuario='".$_SESSION["id"]."' AND zpu_zona=dep_id
					ORDER BY dep_nombre
					",$conexion);
				}
                while($deptos = mysql_fetch_array($departamentos)){
                    if($deptos[0]==$_GET["dpto"]) $color = 'green'; else $color = 'blue';
					
					$contarClientes = mysql_num_rows(mysql_query("SELECT * FROM proveedores 
					INNER JOIN localidad_ciudades ON ciu_id=prov_ciudad
					INNER JOIN localidad_departamentos ON dep_id=ciu_departamento AND dep_id='".$deptos[0]."'
					",$conexion));
                ?>
                    <a href="proveedores.php?dpto=<?=$deptos[0];?>" style="margin-bottom:10px; color:<?=$color;?>"><?=$deptos[1]." (".$contarClientes.")";?></a><br>
                <?php }?>
					</div>
					</div>
				</div>
				
				<div class="span10">
					<div class="content-widgets light-gray">
						<div class="widget-head bondi-blue">
							<h3><?=$tituloPagina;?></h3>
						</div>
                        <?php
						if(isset($_GET["busqueda"]) and $_GET["busqueda"]!=""){
							$filtro = "AND (prov_documento LIKE '%".$_GET["busqueda"]."%' OR prov_nombre LIKE '%".$_GET["busqueda"]."%')";
						}else{
							$filtro = "";
						}
						?>
                        
						<?php
						$filtroDepto = '';
						if(isset($_GET["dpto"]) and $_GET["dpto"]!=""){$filtroDepto .=" AND dep_id='".$_GET["dpto"]."'";}
						
						$SQL = "SELECT * FROM proveedores
						INNER JOIN localidad_ciudades ON ciu_id=prov_ciudad
						INNER JOIN localidad_departamentos ON dep_id=ciu_departamento $filtroDepto
						WHERE prov_eliminado='0' ".$filtro."
						";
						?>
						
						<div class="widget-container">
							<div style="border:thin; border-style:solid; height:150px; margin:10px;">
                            	<h4 align="center">-Busqueda general y paginación-</h4>
                                <p> 
                                    <form class="form-horizontal" action="<?=$_SERVER['PHP_SELF'];?>" method="get">
                                        <div class="search-box">
                                            <div class="input-append input-icon">
                                                <input class="search-input" placeholder="Buscar..." type="text" name="busqueda" value="<?=$_GET["busqueda"];?>">
                                                <i class=" icon-search"></i>
                                                <input class="btn" type="submit" name="buscar" value="Buscar">
                                            </div>
                                            <?php if(isset($_GET["busqueda"]) and $_GET["busqueda"]!=""){?> <a href="<?=$_SERVER['PHP_SELF'];?>" class="btn btn-warning"><i class="icon-minus"></i> Quitar Filtro</a> <?php } ?>
                                        </div>
                                    </form>
								<p style="margin: 10px;"><?php include("paginacion.php");?></p> 
                                </p>
                            </div>
						
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>No</th>
                                <th>País</th>
                                <th>Ciudad, Departamento(Ind.)<br>(COP)</th>
                                <th>Información</th>
								<th>Régimen</th>
							</tr>
							</thead>
							<tbody>
                            <?php
								$consulta = mysql_query("SELECT * FROM proveedores
                                LEFT JOIN localidad_paises ON pais_id=prov_pais
								LEFT JOIN localidad_ciudades ON ciu_id=prov_ciudad
								LEFT JOIN localidad_departamentos ON dep_id=ciu_departamento $filtroDepto
								WHERE prov_eliminado='0' ".$filtro."
								LIMIT $inicio, $limite
								",$conexion);
							$no = 1;
							while($res = mysql_fetch_array($consulta)){	
							?>
							<tr>
								<td><?php echo $no;?></td>
                                <td>
                                    <?php 
                                    echo $res['pais_nombre'];
                                    if($res['prov_otra_ciudad']!=""){echo "<br>".$res['prov_otra_ciudad'];}
                                    ?>
                                </td>
                                <td><?php if($res['prov_pais']==1) echo $res['ciu_nombre'].", ".$res['dep_nombre']." (03".$res['dep_indicativo'].")";?></td>
								
                                <td>
									<?php echo "<b>DNI</b>:". $res['prov_documento']."<br>";?>
									<?php echo "<b>Nombre</b>:". $res['prov_nombre'];?>
                                    <?php if($res['prov_telefono']!="") echo "<br><b>Tel:</b> ". $res['prov_telefono'];?>
                                    <?php if($res['prov_email']!="") echo "<br><b>Email:</b> ". $res['prov_email'];?>
									<?php if($res['prov_direccion']!="") echo "<br><b>Dirección:</b> ". $res['prov_direccion'];?>
									
                                    <h4 style="margin-top:5px;">
                                        <a href="proveedores-editar.php?id=<?=$res[0];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>&nbsp;
                                        <a href="sql.php?id=<?=$res[0];?>&get=61" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
                                	</h4>
                                </td>
								<td>
									<?php echo $res['prov_tipo_regimen'];?>
                                </td>
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