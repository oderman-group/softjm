<?php 
include("../sesion.php");
$idPagina = 363;
?>
<?php include("../../conexion.php");?>
<html>
<head>
	<meta charset="utf-8">
	<title>Gráficos estadísticos</title>
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
//$opciones = array('','Facebook','WhatsApp','Fijo','Celular','Personal','Skype','Otro','Correo', 'Sitio Web');
$filtro = '';
if($_GET["usuario"]){$filtro .=" AND usr_id='".$_GET["usuario"]."'";}	

$filtro2 = '';
if(isset($_GET["desde"]) and $_GET["desde"]!=""){$filtro2 .= " AND (cseg_fecha_contacto>='".$_GET["desde"]."')";}
if(isset($_GET["hasta"]) and $_GET["hasta"]!=""){$filtro2 .= " AND (cseg_fecha_contacto<='".$_GET["hasta"]."')";}

$usuarios = mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_bloqueado=0 $filtro
");
while($usr = mysqli_fetch_array($usuarios)){
	$cte = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"
	SELECT
	(SELECT COUNT(cseg_id) AS cant FROM cliente_seguimiento WHERE cseg_canal=1 AND cseg_usuario_responsable='".$usr['usr_id']."' $filtro2),
	(SELECT COUNT(cseg_id) AS cant FROM cliente_seguimiento WHERE cseg_canal=2 AND cseg_usuario_responsable='".$usr['usr_id']."' $filtro2),
	(SELECT COUNT(cseg_id) AS cant FROM cliente_seguimiento WHERE cseg_canal=3 AND cseg_usuario_responsable='".$usr['usr_id']."' $filtro2),
	(SELECT COUNT(cseg_id) AS cant FROM cliente_seguimiento WHERE cseg_canal=4 AND cseg_usuario_responsable='".$usr['usr_id']."' $filtro2),
	(SELECT COUNT(cseg_id) AS cant FROM cliente_seguimiento WHERE cseg_canal=5 AND cseg_usuario_responsable='".$usr['usr_id']."' $filtro2)
	"));
	
	if($cte[0]==0 and $cte[1]==0 and $cte[2]==0 and $cte[3]==0 and $cte[4]==0) continue;
	$cotizacionesResultados .= "['".strtoupper($usr['usr_seudonimo'])."', '".$cte[0]."', '".$cte[1]."', '".$cte[2]."', '".$cte[3]."', '".$cte[4]."'],";	
}	
?>
	<script>
anychart.onDocumentReady(function () {
    // create data set on our data
    chartData = {
        title: 'Gestión comercial',
        header: ['#', 'Facebook', 'WhatsApp', 'Fijo', 'Celular', 'Personal'],
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
                