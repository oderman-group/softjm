<?php include("../sesion.php");?>
<?php include("../../conexion.php");?>
<html>
<head>
	<meta charset="utf-8">
	<title>Gráficos estadísticos</title>
	<meta charset="UTF-8">
  <script src="../../librerias/graficos/anychart-base.min.js"></script>
  <script src="../../librerias/graficos/anychart-ui.min.js"></script>
  <script src="../../librerias/graficos/anychart-exports.min.js"></script>
  <link href="../../librerias/graficos/anychart-ui.min.css" type="text/css" rel="stylesheet">
  <link href="../../librerias/graficos/anychart-font.min.css" type="text/css" rel="stylesheet">
  <style type="text/css">
html, body, #container {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
}
</style>
</head>
<body>
  <div id="container"></div>
	
	
<?php
$filtro1 = '';
if(is_numeric($_GET["usuario"])){ $filtro1 .=" AND usr_id='".$_GET["usuario"]."'";}
	
$filtro2 = '';		
if(isset($_GET["desde"]) and $_GET["desde"]!=""){$filtro2 .= " AND (cli_estado_mercadeo_fecha>='".$_GET["desde"]."')";}
if(isset($_GET["hasta"]) and $_GET["hasta"]!=""){$filtro2 .= " AND (cli_estado_mercadeo_fecha<='".$_GET["hasta"]."')";}	
	
$usuarios = mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios 
WHERE usr_bloqueado=0 $filtro1
");
while($usr = mysqli_fetch_array($usuarios)){
	$cte = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"
	SELECT
	(SELECT COUNT(cli_id) FROM clientes WHERE cli_estado_mercadeo_usuario='".$usr['usr_id']."' AND cli_estado_mercadeo=1 $filtro2),
	(SELECT COUNT(cli_id) FROM clientes WHERE cli_estado_mercadeo_usuario='".$usr['usr_id']."' AND cli_estado_mercadeo=2 $filtro2),
	(SELECT COUNT(cli_id) FROM clientes WHERE cli_estado_mercadeo_usuario='".$usr['usr_id']."' AND cli_estado_mercadeo=3 $filtro2),
	(SELECT COUNT(cli_id) FROM clientes WHERE cli_estado_mercadeo_usuario='".$usr['usr_id']."' AND cli_estado_mercadeo=4 $filtro2),
	(SELECT COUNT(cli_id) FROM clientes WHERE cli_estado_mercadeo_usuario='".$usr['usr_id']."' AND cli_estado_mercadeo=5 $filtro2),
	(SELECT COUNT(cli_id) FROM clientes WHERE cli_estado_mercadeo_usuario='".$usr['usr_id']."' AND cli_estado_mercadeo=6 $filtro2)
	"));
	
	if($cte[0]==0 and $cte[1]==0 and $cte[2]==0 and $cte[3]==0 and $cte[4]==0 and $cte[5]==0) continue;
	$cotizacionesResultados .= "['".strtoupper($usr[14])."', '".$cte[0]."', '".$cte[1]."', '".$cte[2]."', '".$cte[3]."', '".$cte[4]."', '".$cte[5]."'],";	
}	
?>
	<script>
anychart.onDocumentReady(function () {
    // create data set on our data
    chartData = {
        title: 'Gestion de mercadeo \n (<?=$_GET["desde"];?> - <?=$_GET["hasta"];?>)',
        header: ['#', 'No contestó', 'Número equivocado', 'Envió portafolio', 'Inició negociación', 'Actualizado', 'Papelera'],
        rows: [
            <?=$cotizacionesResultados;?>
        ]
    };

    // create column chart
    var chart = anychart.column();

    // set chart data
    chart.data(chartData);

    // turn on chart animation
    chart.animation(true);

    chart.yAxis().labels().format('{%Value}{groupsSeparator: }');

    // set titles for Y-axis
    chart.yAxis().title('Gestión');

    chart.labels()
            .enabled(true)
            .position('center-top')
            .anchor('center-bottom')
            .format('{%Value}{groupsSeparator: }');
    chart.hovered().labels(false);

    // turn on legend and tune it
    chart.legend()
            .enabled(true)
            .fontSize(13)
            .padding([0, 0, 20, 0]);

    // interactivity settings and tooltip position
    chart.interactivity().hoverMode('single');

    chart.tooltip()
            .positionMode('point')
            .position('center-top')
            .anchor('center-bottom')
            .offsetX(0)
            .offsetY(5)
            .titleFormat('{%X}')
            .format('{%SeriesName} : {%Value}{groupsSeparator: }');

    // set container id for the chart
    chart.container('container');

    // initiate chart drawing
    chart.draw();
});
</script>
	
</body>
</html>
                