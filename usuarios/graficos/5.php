<?php 
include("../sesion.php");
$idPagina = 355;
?>
<?php include("../../conexion.php");?>
<html>
<head>
	<meta charset="utf-8">
	<title>Gráficos estadísticos</title>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js?hcode=c11e6e3cfefb406e8ce8d99fa8368d33"></script>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-ui.min.js?hcode=c11e6e3cfefb406e8ce8d99fa8368d33"></script>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-exports.min.js?hcode=c11e6e3cfefb406e8ce8d99fa8368d33"></script>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-pyramid-funnel.min.js?hcode=c11e6e3cfefb406e8ce8d99fa8368d33"></script>
  <link href="https://cdn.anychart.com/releases/v8/css/anychart-ui.min.css?hcode=c11e6e3cfefb406e8ce8d99fa8368d33" type="text/css" rel="stylesheet">
  <link href="https://cdn.anychart.com/releases/v8/fonts/css/anychart-font.min.css?hcode=c11e6e3cfefb406e8ce8d99fa8368d33" type="text/css" rel="stylesheet">
	 	
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
$filtro = '';
if($_GET["usuario"]){$filtro .=" AND tik_usuario_responsable='".$_GET["usuario"]."'";}
if(isset($_GET["desde"]) and $_GET["desde"]!=""){$filtro .= " AND (tik_fecha_creacion>='".$_GET["desde"]."')";}
if(isset($_GET["hasta"]) and $_GET["hasta"]!=""){$filtro .= " AND (tik_fecha_creacion<='".$_GET["hasta"]."')";}	

$cte = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"
SELECT
(SELECT COUNT(tik_id) AS cant FROM clientes_tikets WHERE tik_etapa=1 $filtro),
(SELECT COUNT(tik_id) AS cant FROM clientes_tikets WHERE tik_etapa=2 $filtro),
(SELECT COUNT(tik_id) AS cant FROM clientes_tikets WHERE tik_etapa=3 $filtro),
(SELECT COUNT(tik_id) AS cant FROM clientes_tikets WHERE tik_etapa=5 $filtro),
(SELECT COUNT(tik_id) AS cant FROM clientes_tikets WHERE tik_etapa=6 $filtro)"));
	
	//if($cte[3]==0) continue;
	$cotizacionesResultados .= "['".strtoupper($usr[4])."', '".$cte[0]."', '".$cte[1]."', '".$cte[2]."'],";	
	
?>
	<script>
anychart.onDocumentReady(function () {
    // prepare data for the chart
    var data = [
        ['Progreso', <?=$cte[0];?>],
        ['Espera', <?=$cte[1];?>],
        ['Propuesta', <?=$cte[2];?>],
        ['Ganado', <?=$cte[3];?>],
        ['Perdido', <?=$cte[4];?>]
    ];

    // create funnel chart
    var chart = anychart.funnel(data);

    // set chart margin
    chart.margin(10, '20%', 10, '20%')
            // set chart base width settings
            .baseWidth('70%')
            // set the neck height
            .neckHeight('17%');

    // set chart labels settings
    chart.labels()
            .position('outside-left')
            .format('{%X} - {%Value}');

    // enable animation
    chart.animation(true);

    // set container id for the chart
    chart.container('container');
    // initiate chart drawing
    chart.draw();
});
</script>
	
</body>
</html>
                