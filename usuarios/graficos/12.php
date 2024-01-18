<?php 
include("../sesion.php");
$idPagina = 362;
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
if(isset($_GET["usuario"]) and $_GET["usuario"]!=""){$filtro2 .= " AND (proy_responsable_principal<='".$_GET["usuario"]."')";}
if(isset($_GET["desde"]) and $_GET["desde"]!=""){$filtro2 .= " AND (proy_inicio>='".$_GET["desde"]."')";}
if(isset($_GET["hasta"]) and $_GET["hasta"]!=""){$filtro2 .= " AND (proy_inicio<='".$_GET["hasta"]."')";}

$clientes = mysqli_query($conexionBdPrincipal,"SELECT proy_titulo, ROUND(AVG(ptar_avance),0) FROM proyectos_tareas
INNER JOIN proyectos ON proy_id=ptar_id_proyecto $filtro2
GROUP BY ptar_id_proyecto
");
while($cte = mysqli_fetch_array($clientes)){
	//if($cte[3]==0) continue;
	$cotizacionesResultados .= "{low: 0, high: ".$cte[1].", x: '".$cte[0]."'},";
}	
?>
	
  <script>
anychart.onDocumentReady(function () {
    // create area series with passed data
    var data = anychart.data.set([
        <?=$cotizacionesResultados;?>
    ]);

    // create column chart
    var chart = anychart.bar();

    // set chart title text settings
    chart.title('Progreso de proyectos');

    var series = chart.rangeBar(data);
    // set the selection color
    series.selected()
            .fill('navy 0.7')
            .stroke('navy');

    // set container id for the chart
    chart.container('container');
    // initiate chart drawing
    chart.draw();
});
</script>
</body>
</html>
                