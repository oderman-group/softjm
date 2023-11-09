<?php 
include("sesion.php");

$idPagina = 160;

$tabla = 'productos_soptec';
$pk = 'prod_id';

include("includes/verificar-paginas.php");
include("includes/head.php");
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

<script type="text/javascript">
  function productos(enviada){
	var campo = enviada.title;
	var producto = enviada.name;
	var proceso = 1;
	var valor = enviada.value;
	

	if(campo == 'prod_costo'){
		
		/* 
		document.getElementById("costo"+producto).value=valor;
		
		var utilidad = (parseFloat(enviada.alt)/100);
		var precioNuevo = (parseFloat(valor) + (parseFloat(valor)*parseFloat(utilidad)));
		var precioNuevoIva = (parseFloat(precioNuevo) + (parseFloat(precioNuevo)*0.19));
		  alert(utilidad);
		  
		document.getElementById("precioLista"+producto).innerHTML="$"+precioNuevo.toLocaleString();
		document.getElementById("precioListaIva"+producto).innerHTML="$"+precioNuevoIva.toLocaleString();
		*/
	}
	
	 
	if(campo == 'prod_utilidad'){	
		document.getElementById("utilidad"+producto).value=valor;

		var costo = enviada.alt;
		var utilidad = (valor/100);
		var precioNuevo = (parseFloat(costo) + (parseFloat(costo) * parseFloat(utilidad)));
		var precioNuevoIva = (parseFloat(precioNuevo) + (parseFloat(precioNuevo)*0.19));

		document.getElementById("precioLista"+producto).innerHTML="$"+precioNuevo.toLocaleString();
		document.getElementById("precioListaIva"+producto).innerHTML="$"+precioNuevoIva.toLocaleString();
	}
	
	$('#resp').empty().hide().html("Esperando...").show(1);
		datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo)+"&tabla="+$("#tabla").val()+"&pk="+$("#pk").val();
			$.ajax({
				type: "POST",
				url: "ajax/ajax-productos.php",
				data: datos,
				success: function(data){
				$('#resp').empty().hide().html(data).show(1);
				}
			});
	}
	
	function pred(enviada){
	var valorActual = enviada.title;
	var producto = enviada.name;
	var proceso = 6;
		
		if(valorActual==0){
			document.getElementById("p"+producto).innerHTML="SI";
			document.getElementById("p"+producto).title=1;
		}
		
		if(valorActual==1){
			document.getElementById("p"+producto).innerHTML="NO";
			document.getElementById("p"+producto).title=0;
		}
		
	$('#resp').empty().hide().html("Esperando...").show(1);
		datos = "producto="+(producto)+"&proceso="+(proceso)+"&valorActual="+(valorActual);
			$.ajax({
				type: "POST",
				url: "ajax/ajax-productos.php",
				data: datos,
				success: function(data){
				$('#resp').empty().hide().html(data).show(1);
				}
			});
	}
</script>

<?php include("includes/funciones-js.php");?>

</head>
<body>
<input type="hidden" value="<?=$tabla;?>" name="tabla" id="tabla">
<input type="hidden" value="<?=$pk;?>" name="pk" id="pk">
	
<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
            <?php include("includes/notificaciones.php");?>
			
			<span id="resp"></span>
            <p>
				<a href="#" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
            </p>
			

			
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3><?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<p></p>
							<table class="table table-striped table-bordered" id="data-table" style="font-size: 9px;">
							<thead>
							<tr>
								<th>No</th>
                                <th>Nombre</th>
								<th>Grupo 2</th>
								<th>Marca</th>

							</tr>
							</thead>
							<tbody>
                            <?php
							$filtro ='';
							if(is_numeric($_GET["grupo1"])){$filtro .=" AND prod_grupo1='".$_GET["grupo1"]."'";}
							if(is_numeric($_GET["grupo2"])){$filtro .=" AND prod_categoria='".$_GET["grupo2"]."'";}
							if(is_numeric($_GET["marca"])){$filtro .=" AND prod_marca='".$_GET["marca"]."'";}	
								
							$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_soptec 
							LEFT JOIN productos_categorias ON catp_id=prod_categoria
							WHERE prod_id=prod_id AND prod_id_empresa='".$idEmpresa."' $filtro");
							$no = 1;
							$visible = array("SI","SI","NO");
							$estadoVisible = array(2,2,1);	
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
								
								$consultaGrupo=mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_categorias WHERE catp_id='".$res['prod_grupo1']."'  AND catp_id_empresa='".$idEmpresa."'");
								$grupo1 = mysqli_fetch_array($consultaGrupo, MYSQLI_BOTH);
								
								$consultaMarca=mysqli_query($conexionBdPrincipal,"SELECT * FROM marcas WHERE mar_id='".$res['prod_marca']."'  AND mar_id_empresa='".$idEmpresa."'");
								$marca = mysqli_fetch_array($consultaMarca, MYSQLI_BOTH);

							?>
							<tr>
								<td><?=$no;?></td>

								
                                <td>
									<div>
									<?=$res['prod_nombre'];?>
									<h4>
										<a href="#" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
										<a href="#" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
										<a href="productos-materiales.php?pdto=<?=$res[0];?>" data-toggle="tooltip" title="Materiales"><i class="icon-folder-open"></i></a>
									</h4>
									</div>	
								</td>
								
                                <td><?=$res['catp_nombre'];?></td>
								<td><?=$marca['mar_nombre'];?></td>
								
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