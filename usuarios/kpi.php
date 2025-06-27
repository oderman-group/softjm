<?php
include("sesion.php");
$idPagina = 416;
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
				<?php include("includes/notificaciones.php");?>

				<div class="row-fluid">

					<div class="span2">
						<div class="content-widgets light-gray">
							<div class="widget-head green">
								<h5 align="center" style="color:white;">Listado de KPIs</h5>
							</div>

							<div class="widget-container">
								<a href="#" style="margin-bottom:10px;">1. Número de ventas</a><br>
								<a href="#" style="margin-bottom:10px;">2. Promedio de valor venta por factura</a><br>
							</div>
						</div>
					</div>

					<div class="span10">
						<div class="content-widgets light-gray">
							<div class="widget-head green">
								<h3><?=$paginaActual['pag_nombre'];?></h3>
							</div>

							<div class="widget-container">
								<table class="table table-striped table-bordered">
									<thead>
										<tr>
											<th>No</th>
											<th>Vendedor</th>
											<th>Valor</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>1</td>
											<td>Jhon</td>
											<td>$1.000.000</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php include("includes/pie.php"); ?>
	</div>

</body>
</html>