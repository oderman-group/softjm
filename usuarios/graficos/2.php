<?php 
include("../sesion.php");
$idPagina = 353;
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
$filtro2 = '';		
if(isset($_GET["desde"]) and $_GET["desde"]!=""){$filtro2 .= " AND (cotiz_fecha_propuesta>='".$_GET["desde"]."')";}
if(isset($_GET["hasta"]) and $_GET["hasta"]!=""){$filtro2 .= " AND (cotiz_fecha_propuesta<='".$_GET["hasta"]."')";}	

$clientes = mysqli_query($conexionBdPrincipal,"SELECT cotiz_cliente, cli_nombre, COUNT(cotiz_cliente) AS cant FROM cotizacion
INNER JOIN clientes ON cli_id=cotiz_cliente
WHERE cotiz_id=cotiz_id $filtro2
GROUP BY cotiz_cliente
HAVING cant > 10
ORDER BY cant DESC
LIMIT 0,10");
while($cte = mysqli_fetch_array($clientes)){
	if($cte[2]==0) continue;
	$cotizacionesResultados .= "['".$cte[1]."', '".$cte[2]."'],";	
}	
?>
	
  <script>
anychart.onDocumentReady(function () {
    // create column chart
    var chart = anychart.column();

    // turn on chart animation
    chart.animation(true);

    // set chart title text settings
    chart.title('10 Clientes con más cotizaciones');

    // create area series with passed data
    var series = chart.column([
        <?=$cotizacionesResultados;?>
    ]);

    // set series tooltip settings
    series.tooltip().titleFormat('{%X}');

    series.tooltip()
            .position('center-top')
            .anchor('center-bottom')
            .offsetX(0)
            .offsetY(5)
            .format('{%Value}{groupsSeparator: }');

    // set scale minimum
    chart.yScale().minimum(0);

    // set yAxis labels formatter
    chart.yAxis().labels().format('{%Value}{groupsSeparator: }');

    // tooltips position and interactivity settings
    chart.tooltip().positionMode('point');
    chart.interactivity().hoverMode('by-x');

    // axes titles
    chart.xAxis().title('Clientes');
    chart.yAxis().title('Cotizaciones');

    // set container id for the chart
    chart.container('container');

    // initiate chart drawing
    chart.draw();
});
</script>
</body>
</html>
                