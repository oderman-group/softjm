<?php include("sesion.php");?>
<?php
$idPagina = 92;
$paginaActual['pag_nombre'] = "Abonos a Facturas";
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");?>
<?php

$factura = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "SELECT * FROM facturacion WHERE fact_id='".$_GET["fact"]."'"), MYSQLI_BOTH);
$impuestos = $factura['fact_valor'] * $factura['fact_impuestos']/100;
$retencion = $factura['fact_valor'] * $factura['fact_retencion']/100;
$descuento = $factura['fact_valor'] * $factura['fact_descuento']/100;
								
$valorReal = ($factura['fact_valor'] + $impuestos) - ($retencion + $descuento);
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
</head>
<body>
<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?> : <b><?=$factura['fact_descripcion'];?></b></h3>
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?> : <b><?=$factura['fact_descripcion'];?></b></li>
					</ul>
				</div>
			</div>
            <?php include("includes/notificaciones.php");?>
            <p>
            <a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
            <?php if(isset($_GET["fact"]) and is_numeric($_GET["fact"]) and $factura[0]!=""){?><a href="facturacion-abonos-agregar.php?fact=<?=$_GET["fact"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a><?php }?>
            </p>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3><?=$paginaActual['pag_nombre'];?> : <b><?=$factura['fact_descripcion'];?></b></h3>
						</div>
						<div class="widget-container">
							<p></p>
                            
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>No</th>
                                <th>Comprobante</th>
                                <th>Tipo</th>
                                <th>#Factura</th>
                                <th>Fecha Factura</th>
                                <th>Descripción</th>
                                <th>Fecha Abono</th>
                                <th>Valor abono</th>
                                <th>Medio</th>
                                <th>Última Modificación</th>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$filtro="";
							if(isset($_GET["fact"]) and $_GET["fact"]!="" and $factura[0]!=""){$filtro .= " AND (fpab_factura='".$_GET["fact"]."')";}
							$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM facturacion_abonos
							INNER JOIN facturacion ON fact_id=fpab_factura
							WHERE fpab_id=fpab_id ".$filtro."
							ORDER BY fpab_id ASC
							");
							$no = 1;
							while($res = mysqli_fetch_array($consulta,MYSQLI_BOTH)){
								$suma +=$res['fpab_valor'];
								switch($res['fpab_medio_pago']){
									case 1: $medio = 'Consignación'; break;
									case 2: $medio = 'Transferencia'; break;
									case 3: $medio = 'P. Web'; break;
									case 4: $medio = 'Efectivo'; break;
									case 5: $medio = 'Cheque'; break;
									case 6: $medio = 'Efecty'; break;
									case 7: $medio = 'Gana'; break;
									case 8: $medio = 'Otro medio'; break;
								}
								switch($res['fact_tipo']){
									case 1: $tipoF = 'Ingreso'; $etiquetaT='success'; break;
									case 2: $tipoF = 'Egreso'; $etiquetaT='important'; break;
								}
							?>
							<tr>
								<td><?=$no;?></td>
                                <td align="center"><?php if($res['fpab_comprobante']!=""){?><a href="files/comprobantes/<?=$res['fpab_comprobante'];?>" target="_blank"><h3><i class="icon-download"></i></h3><?php }?></td>
                                <td><span class="label label-<?=$etiquetaT;?>"><?=$tipoF;?></span></td>
                                <td><?=$res['fpab_factura'];?></td>
                                <td><?=$res['fact_fecha_real'];?></td>
                                <td><?=$res['fact_descripcion'];?></td>
                                <td><?=$res['fpab_fecha_abono'];?></td>
                                <td>$<?=number_format($res['fpab_valor'],0,",",".");?></td>
                                <td><?=$medio;?></td>
                                <td><?=$res['fpab_fecha_ultima_modificacion'];?></td>
                                <td><h4>
                                	<a href="facturacion-abonos-editar.php?id=<?=$res['fpab_id'];?>&fact=<?=$_GET["fact"];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
                                    <a href="bd_delete/facturacion-bonos-eliminar.php?id=<?=$res['fpab_id'];?>&get=25&fact=<?=$_GET["fact"];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
                                </h4></td>
							</tr>
                            <?php $no++;}?>
							</tbody>
                            <?php
							$saldoFinal = ($valorReal - $suma);
							if(isset($_GET["fact"]) and is_numeric($_GET["fact"]) and $factura[0]!=""){
							?>
                            <tfoot style="font-weight:bold;">
                            	<tr>
                                    <td colspan="3">Total Factura: </td>
                                    <td>$<?=number_format($valorReal,0,",",".");?></td>
                                    <td colspan="3">&nbsp;</td>
								</tr>
                                <tr>
                                    <td colspan="3">Total Abonos: </td>
                                    <td >$<?=number_format($suma,0,",",".");?></td>
                                    <td colspan="3">&nbsp;</td>
								</tr>
                                <tr>
                                    <td colspan="3">Saldo Final: </td>
                                    <td>$<?=number_format($saldoFinal,0,",",".");?></td>
                                    <td colspan="3">&nbsp;</td>
								</tr>
                            </tfoot>
                            <?php }?>
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
