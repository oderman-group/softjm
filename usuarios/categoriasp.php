<?php
include("sesion.php");

$idPagina = 39;
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

<script type="text/javascript">
  function productos(enviada){
  	  var campo = enviada.title;
	  var nombreCat = enviada.alt;
	  var producto = enviada.name;
	  var proceso = 3;
	  var valor = enviada.value;
	  $('#resp').empty().hide().html("Esperando...").show(1);
		datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo)+"&nombreCat="+(nombreCat);
			   $.ajax({
				   type: "POST",
				   url: "ajax/ajax-productos.php",
				   data: datos,
				   success: function(data){
				   $('#resp').empty().hide().html(data).show(1);
				   }
			   });
	}
	
	function grupoUno(enviada){
  	  var campo = enviada.title;
	  var nombreCat = enviada.alt;		
	  var producto = enviada.name;
	  var proceso = 4;
	  var valor = enviada.value;
	  $('#respG1').empty().hide().html("Esperando...").show(1);
		datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo)+"&nombreCat="+(nombreCat)
			   $.ajax({
				   type: "POST",
				   url: "ajax/ajax-productos.php",
				   data: datos,
				   success: function(data){
				   $('#respG1').empty().hide().html(data).show(1);
				   }
			   });
	}
	
	function grupoTres(enviada){
  	  var campo = enviada.title;
	  var nombreCat = enviada.alt;		
	  var producto = enviada.name;
	  var proceso = 5;
	  var valor = enviada.value;
	  $('#respG3').empty().hide().html("Esperando...").show(1);
		datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo)+"&nombreCat="+(nombreCat)
			   $.ajax({
				   type: "POST",
				   url: "ajax/ajax-productos.php",
				   data: datos,
				   success: function(data){
				   $('#respG3').empty().hide().html(data).show(1);
				   }
			   });
	}
</script>
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
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            <?php include("includes/notificaciones.php");?>
			
            <p>
            	<a href="categoriasp-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
            </p>
			
			<span id="respG1"></span>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head orange">
							<h3>GRUPO 1</h3>
						</div>
						<div class="widget-container">
							<p></p>
							<table class="table table-striped table-bordered">
							<thead>
							<tr>
								<th>No</th>
								<th>Cod.</th>
                                <th>Nombre</th>
								<!--<th>Grupo</th>-->
								<th>#Productos</th>
								<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15){?>
								<th>Actualización</th>
								<th>Utilidad Min (%)</th>
								<th>Utilidad Lista (%)</th>
								<th title="Sobre el precio de lista.">Dcto. Max. (%)</th>
								<th title="Sobre el precio de lista.">Utilidad Dealer. (%)</th>
								<th title="Sobre el precio de lista.">Utilidad Web. (%)</th>
								<th>Comisión (%)</th>
								<?php }?>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$consulta2 = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=1  AND catp_id_empresa='".$idEmpresa."'");
							$no = 1;
							$totalP2=0;
							while($res2 = mysqli_fetch_array($consulta2, MYSQLI_BOTH)){
								$consultaNumProductos=$conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_grupo1='".$res2[0]."' AND prod_id_empresa='".$idEmpresa."'");
								$numProductos2 = $consultaNumProductos->num_rows;
								
								//if($numProductos2==0) continue;
								
								$totalP2 += $numProductos2;
								$consultaUsuarios=$conexionBdPrincipal->query("SELECT * FROM usuarios WHERE usr_id='".$res2['catp_usuario']."' AND usr_id_empresa='".$idEmpresa."'");
								$usuario2 = mysqli_fetch_array($consultaUsuarios, MYSQLI_BOTH);
							?>
							<tr>
								<td><?=$no;?></td>
								<td><?=$res2[0];?></td>
                                <td><?=$res2[1];?></td>
								<!--<td><?=$res[2];?></td>-->
								<td style="text-align: center;">
									<a href="productos.php?grupo1=<?=$res2[0];?>" data-toggle="tooltip" title="Productos"><?=$numProductos2;?></a>
								</td>
								<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15){?>
								<td>
										<span style="font-size: 9px;"><?=strtoupper($usuario2['usr_nombre']);?></span>
										<br><span style="font-size: 9px;"><?=$res2['catp_fecha'];?></span>
								</td>
								
								<td>
										<input type="text" title="prod_utilidad_minima" alt="catp_utilidad_minima" name="<?=$res2[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res2['catp_utilidad_minima'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_utilidad" alt="catp_utilidad_lista" name="<?=$res2[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res2['catp_utilidad_lista'];?>">
										
								</td>
								
								
								<td>
										<input type="text" title="prod_descuento1" alt="catp_dcto_max" name="<?=$res2[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res2['catp_dcto_max'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_descuento2" alt="catp_utilidad_dealer" name="<?=$res2[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res2['catp_utilidad_dealer'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_descuento_web" alt="catp_utilidad_web" name="<?=$res2[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res2['catp_utilidad_web'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_comision" alt="catp_comision" name="<?=$res2[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res2['catp_comision'];?>">
								</td>
								<?php }?>
                                <td><h4>
                                    <a href="categoriasp-editar.php?id=<?=$res2[0];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
                                    <a href="bd_delete/categoriasp-eliminar.php?id=<?=$res2[0];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
                                </h4></td>
							</tr>
                            <?php $no++;}?>
							</tbody>
							<tfoot>
								<tr style="font-weight: bold;">
									<td colspan="2">TOTAL</td>
									<td style="text-align: center;"><?=$totalP2;?></td>
									<td colspan="6">&nbsp;</td>
								</tr>	
							</tfoot>	
							</table>
						</div>
					</div>
				</div>
			</div>
			
			<span id="resp"></span>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3>GRUPO 2</h3>
						</div>
						<div class="widget-container">
							<p></p>
							<table class="table table-striped table-bordered">
							<thead>
							<tr>
								<th>No</th>
								<th>Cod</th>
                                <th>Nombre</th>
								<!--<th>Grupo</th>-->
								<th>#Productos</th>
								<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15){?>
								<th>Actualización</th>
								<th>Utilidad Min (%)</th>
								<th>Utilidad Lista (%)</th>
								<th title="Sobre el precio de lista.">Dcto. Max. (%)</th>
								<th title="Sobre el precio de lista.">Utilidad Dealer. (%)</th>
								<th title="Sobre el precio de lista.">Utilidad Web. (%)</th>
								<th>Comisión (%)</th>
								<?php }?>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$consulta = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=2 AND catp_id_empresa='".$idEmpresa."'");
							$no = 1;
							$totalP=0;
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
								$consultaNumProductos=$conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_categoria='".$res[0]."' AND prod_id_empresa='".$idEmpresa."'");
								$numProductos = $consultaNumProductos->num_rows;
								
								//if($numProductos==0) continue;
								
								$totalP += $numProductos;
								$consultaUsuarios2=$conexionBdPrincipal->query("SELECT * FROM usuarios WHERE usr_id='".$res['catp_usuario']."' AND usr_id_empresa='".$idEmpresa."'");
								$usuario = mysqli_fetch_array($consultaUsuarios2, MYSQLI_BOTH);
							?>
							<tr>
								<td><?=$no;?></td>
								<td><?=$res[0];?></td>
                                <td><?=$res[1];?></td>
								<!--<td><?=$res[2];?></td>-->
								<td style="text-align: center;">
									<a href="productos.php?grupo2=<?=$res[0];?>" data-toggle="tooltip" title="Productos"><?=$numProductos;?></a>
								</td>
								<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15){?>
								<td>
										<span style="font-size: 9px;"><?php if(isset($usuario['usr_nombre'])){strtoupper($usuario['usr_nombre']);}?></span>
										<br><span style="font-size: 9px;"><?=$res['catp_fecha'];?></span>
								</td>
								
								<td>
										<input type="text" title="prod_utilidad_minima" alt="catp_utilidad_minima" name="<?=$res[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res['catp_utilidad_minima'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_utilidad" alt="catp_utilidad_lista" name="<?=$res[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res['catp_utilidad_lista'];?>">
								</td>
								
								
								<td>
										<input type="text" title="prod_descuento1" alt="catp_dcto_max" name="<?=$res[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res['catp_dcto_max'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_descuento2" alt="catp_utilidad_dealer" name="<?=$res[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res['catp_utilidad_dealer'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_descuento_web" alt="catp_utilidad_web" name="<?=$res[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res['catp_utilidad_web'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_comision" alt="catp_comision" name="<?=$res[0];?>" style="width: 40px; text-align: center" onChange="grupoUno(this)" value="<?=$res['catp_comision'];?>">
								</td>
								<?php }?>
                                <td><h4>
                                    <a href="categoriasp-editar.php?id=<?=$res[0];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
                                    <a href="bd_delete/categoriasp-eliminar.php?id=<?=$res[0];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
                                </h4></td>
							</tr>
                            <?php $no++;}?>
							</tbody>
							<tfoot>
								<tr style="font-weight: bold;">
									<td colspan="2">TOTAL</td>
									<td style="text-align: center;"><?=$totalP;?></td>
									<td colspan="6">&nbsp;</td>
								</tr>	
							</tfoot>	
							</table>
						</div>
					</div>
				</div>
			</div>
			
			<span id="respG3"></span>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head bondi-blue">
							<h3>MARCAS</h3>
						</div>
						<div class="widget-container">
							<p></p>
							<table class="table table-striped table-bordered">
							<thead>
							<tr>
								<th>No</th>
								<th>Cod.</th>
                                <th>Nombre</th>
								<!--<th>Grupo</th>-->
								<th>#Productos</th>
								<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15){?>
								<th>Actualización</th>
								<th>Utilidad Min (%)</th>
								<th>Utilidad Lista (%)</th>
								<th title="Sobre el precio de lista.">Dcto. Max. (%)</th>
								<th title="Sobre el precio de lista.">Utilidad Dealer. (%)</th>
								<th title="Sobre el precio de lista.">Utilidad Web. (%)</th>
								<th>Comisión (%)</th>
								<?php }?>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$consulta3 = $conexionBdPrincipal->query("SELECT * FROM marcas WHERE mar_id_empresa='".$idEmpresa."'");
							$no = 1;
							$totalP3=0;
							while($res3 = mysqli_fetch_array($consulta3, MYSQLI_BOTH)){
								$consultaNumProductos3=$conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_marca='".$res3[0]."' AND prod_id_empresa='".$idEmpresa."'");
								$numProductos3 = $consultaNumProductos3->num_rows;
								
								//if($numProductos3==0) continue;
								
								$totalP3 += $numProductos3;
								/*$consultaUsuarios3=$conexionBdPrincipal->query("SELECT * FROM usuarios WHERE usr_id='".$res3['catp_usuario']."'");
								$usuario3 = mysqli_fetch_array($consultaUsuarios3, MYSQLI_BOTH);*/
							?>
							<tr>
								<td><?=$no;?></td>
								<td><?=$res3[0];?></td>
                                <td><?=$res3[1];?></td>
								<!--<td><?=$res[2];?></td>-->
								<td style="text-align: center;">
									<a href="productos.php?marca=<?=$res3[0];?>" data-toggle="tooltip" title="Productos"><?=$numProductos3;?></a>
								</td>
								<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15){?>
								<td>
										<span style="font-size: 9px;"><!--<?=strtoupper($usuario3['usr_nombre']);?>--></span>
										<br><span style="font-size: 9px;"><!--<?=$res3['catp_fecha'];?>--></span>
								</td>
								
								<td>
										<input type="text" title="prod_utilidad_minima" alt="catp_utilidad_minima" name="<?=$res3[0];?>" style="width: 40px; text-align: center" onChange="grupoTres(this)" value="<?=$res3['catp_utilidad_minima'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_utilidad" alt="catp_utilidad_lista" name="<?=$res3[0];?>" style="width: 40px; text-align: center" onChange="grupoTres(this)" value="<?=$res3['catp_utilidad_lista'];?>">
										
								</td>
								
								
								<td>
										<input type="text" title="prod_descuento1" alt="catp_dcto_max" name="<?=$res3[0];?>" style="width: 40px; text-align: center" onChange="grupoTres(this)" value="<?=$res3['catp_dcto_max'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_descuento2" alt="catp_utilidad_dealer" name="<?=$res3[0];?>" style="width: 40px; text-align: center" onChange="grupoTres(this)" value="<?=$res3['catp_utilidad_dealer'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_descuento_web" alt="catp_utilidad_web" name="<?=$res3[0];?>" style="width: 40px; text-align: center" onChange="grupoTres(this)" value="<?=$res3['catp_utilidad_web'];?>">
								</td>
								
								<td>
										<input type="text" title="prod_comision" alt="catp_comision" name="<?=$res3[0];?>" style="width: 40px; text-align: center" onChange="grupoTres(this)" value="<?=$res3['catp_comision'];?>">
								</td>
								<?php }?>
                                <td>
									<!--
									<h4>
									
                                    <a href="categoriasp-editar.php?id=<?=$res3[0];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
                                    <a href="bd_delete/categoriasp-eliminar.php?id=<?=$res3[0];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
                                </h4>
-->
							</td>
							</tr>
                            <?php $no++;}?>
							</tbody>
							<tfoot>
								<tr style="font-weight: bold;">
									<td colspan="2">TOTAL</td>
									<td style="text-align: center;"><?=$totalP3;?></td>
									<td colspan="6">&nbsp;</td>
								</tr>	
							</tfoot>	
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