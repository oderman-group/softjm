<?php include("sesion.php");?>
<?php
$idPagina = 104;
$paginaActual['pag_nombre'] = "Calendario";
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");?>

<?php
if(is_numeric($_GET["id"])){
	$usuarioID = $_GET["id"];
}else{
	$usuarioID = $_SESSION["id"];
}
$consultaCalendario=mysqli_query($conexionBdPrincipal, "SELECT * FROM usuarios WHERE usr_id='".$usuarioID."'");
$usuarioCalendario = mysqli_fetch_array($consultaCalendario, MYSQLI_BOTH);

$consulta = mysqli_query($conexionBdPrincipal, "SELECT cseg_id, cseg_asunto, cseg_fecha_proximo_contacto, cseg_usuario_encargado, cseg_realizado, cseg_tiket, cseg_cliente, DAY(cseg_fecha_proximo_contacto) as dia, MONTH(cseg_fecha_proximo_contacto) as mes, YEAR(cseg_fecha_proximo_contacto) as agno FROM cliente_seguimiento 
WHERE cseg_usuario_encargado='".$usuarioID."' AND YEAR(cseg_fecha_proximo_contacto)>='".date("Y")."' AND MONTH(cseg_fecha_proximo_contacto)>='".date("m")."'
LIMIT 0,8
");
$contReg=1;
$eventos="";
while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
	
	$color = 'orange';
	if($resultado["cseg_realizado"]==1){$color='blue';}
	
	$resultado["mes"]--;
	$eventos .= '
		{
			title: "'.$resultado["cseg_id"].": ".$resultado["cseg_asunto"].'",
			start: new Date('.$resultado["agno"].', '.$resultado["mes"].', '.$resultado["dia"].', 6, 0),
			backgroundColor: "'.$color.'",
			url: "clientes-seguimiento-editar.php?id='.$resultado["cseg_id"].'&idTK='.$resultado["cseg_tiket"].'&cte='.$resultado["cseg_cliente"].'"
		},
	'; 
}
$eventos = substr($eventos,0,-1);


$proyectos = mysqli_query($conexionBdPrincipal, "SELECT proy_id, proy_titulo, proy_descripcion, proy_inicio, proy_fin, proy_responsable_principal, proy_estado, DAY(proy_fin) as dia, MONTH(proy_fin) as mes, YEAR(proy_fin) as agno FROM proyectos 
WHERE proy_responsable_principal='".$usuarioID."' AND YEAR(proy_fin)>='".date("Y")."' AND MONTH(proy_fin)>='".date("m")."' AND proy_id_empresa={$_SESSION['dataAdicional']['id_empresa']}
LIMIT 0,8
");

$i=1;
while($proy = mysqli_fetch_array($proyectos, MYSQLI_BOTH)){
	
	$proy["mes"]--;
	
		$eventos .= '
			{
				title: "'.$proy["proy_id"].": ".$proy["proy_titulo"].'",
				start: new Date('.$proy["agno"].', '.$proy["mes"].', '.$proy["dia"].', 6, 0),
				backgroundColor: "green",
				url: "proyectos-editar.php?id='.$proy["proy_id"].'"
			},
		'; 
	
	$i++;
}

$eventos = substr($eventos,0,-1);


$agenda = mysqli_query($conexionBdPrincipal, "SELECT age_id, age_evento, age_fecha, age_usuario, DAY(age_fecha) as dia, MONTH(age_fecha) as mes, YEAR(age_fecha) as agno FROM agenda 
WHERE age_usuario='".$usuarioID."' AND YEAR(age_fecha)>='".date("Y")."' AND MONTH(age_fecha)>='".date("m")."'
LIMIT 0,8
");

$i=1;
while($age = mysqli_fetch_array($agenda, MYSQLI_BOTH)){
	
	$age["mes"]--;

	$url = '';
	if( Modulos::validarRol(['117'], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) ) { 
		$url = "url: 'calendario-editar.php?id=".$age["age_id"]."'";
	}

	
		$eventos .= '
			{
				title: "'.$age["age_id"].": ".$age["age_evento"].'",
				start: new Date('.$age["agno"].', '.$age["mes"].', '.$age["dia"].', 6, 0),
				backgroundColor: "black",
				'.$url.'
			},

		'; 
	
	$i++;
}

$eventos = substr($eventos,0,-1);


$eventos .= '
	{title: "FESTIVO", start: new Date(2019, 0, 1), backgroundColor: "red"},
	{title: "FESTIVO", start: new Date(2019, 0, 7), backgroundColor: "red"},
	
	
	{title: "FESTIVO", start: new Date(2019, 2, 25), backgroundColor: "red"},
	
	{title: "FESTIVO", start: new Date(2019, 3, 18), backgroundColor: "red"},
	{title: "FESTIVO", start: new Date(2019, 3, 19), backgroundColor: "red"},
	
	{title: "FESTIVO", start: new Date(2019, 4, 1), backgroundColor: "red"},
	
	{title: "FESTIVO", start: new Date(2019, 5, 3), backgroundColor: "red"},
	{title: "FESTIVO", start: new Date(2019, 5, 24), backgroundColor: "red"},
	
	{title: "FESTIVO", start: new Date(2019, 6, 20), backgroundColor: "red"},
	
	{title: "FESTIVO", start: new Date(2019, 7, 7), backgroundColor: "red"},
	{title: "FESTIVO", start: new Date(2019, 7, 19), backgroundColor: "red"},
	
	
	{title: "FESTIVO", start: new Date(2019, 9, 14), backgroundColor: "red"},
	
	{title: "FESTIVO", start: new Date(2019, 10, 4), backgroundColor: "red"},
	{title: "FESTIVO", start: new Date(2019, 10, 11), backgroundColor: "red"},
	
	{title: "FESTIVO", start: new Date(2019, 11, 25), backgroundColor: "red"},
';
?>

<!-- styles -->

<!--[if IE 7]>
            <link rel="stylesheet" href="css/font-awesome-ie7.min.css">
        <![endif]-->
<link href="css/styles.css" rel="stylesheet">
<!--[if IE 7]>
            <link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
        <![endif]-->
<!--[if IE 8]>
            <link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
        <![endif]-->
<!--[if IE 9]>
            <link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
        <![endif]-->
<link href="css/fullcalendar.css" rel="stylesheet">

<!--============j avascript===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/fullcalendar.min.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script type='text/javascript'>
            $(document).ready(function () {
                var date = new Date();
                var d = date.getDate();
                var m = date.getMonth();
                var y = date.getFullYear();
                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    buttonText: {
                        prev: 'Prev',
                        next: 'Next',
                        today: 'Today',
                        month: 'Month',
                        week: 'Week',
                        day: 'Day'
                    },
                    editable: true,
                    events: [<?=$eventos;?>]
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
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?>: <?=$usuarioCalendario["usr_nombre"];?></h3>
						<ul class="top-right-toolbar">
							<li><a data-toggle="dropdown" class="dropdown-toggle blue-violate" href="#" title="Users"><i class="icon-user"></i></a>
							</li>
							<li><a href="#" class="green" title="Upload"><i class=" icon-upload-alt"></i></a></li>
							<li><a href="#" class="bondi-blue" title="Settings"><i class="icon-cogs"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					
					<?php if( Modulos::validarRol(['116'], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) ) {?>
						<p><a href="calendario-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar evento</a></p>
					<?php }?>
					
					<div class="content-widgets gray">
						<div class="widget-head orange">
							<h3><i class=" icon-calendar"></i><?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="ribbon-wrapper-green">
							<div class="ribbon-green">
								Eventos
							</div>
						</div>
						<div class="widget-container">
							<div id='calendar'>
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