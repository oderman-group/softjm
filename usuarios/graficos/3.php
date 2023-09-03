<?php include("../sesion.php");?>
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
$filtro1 = '';
if(is_numeric($_GET["usuario"])){ $filtro1 .=" AND usr_id='".$_GET["usuario"]."'";}
	
$filtro2 = '';		
if(isset($_GET["desde"]) and $_GET["desde"]!=""){$filtro2 .= " AND (cotiz_fecha_propuesta>='".$_GET["desde"]."')";}
if(isset($_GET["hasta"]) and $_GET["hasta"]!=""){$filtro2 .= " AND (cotiz_fecha_propuesta<='".$_GET["hasta"]."')";}	

$clientes = mysqli_query($conexionBdPrincipal,"SELECT cotiz_id, cli_nombre, usr_nombre, SUM(czpp_valor) AS cant FROM cotizacion_productos
INNER JOIN cotizacion ON cotiz_id=czpp_cotizacion $filtro2
INNER JOIN clientes ON cli_id=cotiz_cliente
INNER JOIN usuarios ON usr_id=cotiz_vendedor $filtro1
GROUP BY czpp_cotizacion ORDER BY cant DESC LIMIT 0,10");
while($cte = mysqli_fetch_array($clientes)){
	if($cte[3]==0) continue;
	$cotizacionesResultados .= "['#".$cte[0]." - ".$cte[1]." / ".$cte[2]."', '".$cte[3]."'],";	
}	
?>
	
  <script>
anychart.onDocumentReady(function () {
    // create column chart
    var chart = anychart.column();

    // turn on chart animation
    chart.animation(true);

    // set chart title text settings
    chart.title('10 cotizaciones más importanes');

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
            .format('${%Value}{groupsSeparator: }');

    // set scale minimum
    chart.yScale().minimum(0);

    // set yAxis labels formatter
    chart.yAxis().labels().format('${%Value}{groupsSeparator: }');

    // tooltips position and interactivity settings
    chart.tooltip().positionMode('point');
    chart.interactivity().hoverMode('by-x');

    // axes titles
    chart.xAxis().title('Cotizaciones');
    chart.yAxis().title('Valor');

    // set container id for the chart
    chart.container('container');

    // initiate chart drawing
    chart.draw();
});
</script>
</body>
</html>
                