<?php include("sesion.php");?>
<?php
$idPagina = 67;
$paginaActual['pag_nombre'] = "Estádisticas Anuales (".date("Y").")";

if(is_numeric($_GET["a"])){$agnoConsulta = $_GET["a"];}else{$agnoConsulta = date("Y");}
if(is_numeric($_GET["m"])){$mesConsulta = $_GET["m"];}else{$mesConsulta = date("m");}

if(is_numeric($_GET["u"])){$userConsulta = $_GET["u"];}else{$userConsulta = 9;}
?>
<?php include("includes/verificar-paginas.php");?>
<?php
include("includes/head.php");

$consultaClientes=mysqli_query($conexionBdPrincipal,"SELECT 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=1 AND cli_categoria='".CLI_CATEGORIA_CLIENTE."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=2 AND cli_categoria='".CLI_CATEGORIA_CLIENTE."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=3 AND cli_categoria='".CLI_CATEGORIA_CLIENTE."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=4 AND cli_categoria='".CLI_CATEGORIA_CLIENTE."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=5 AND cli_categoria='".CLI_CATEGORIA_CLIENTE."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=6 AND cli_categoria='".CLI_CATEGORIA_CLIENTE."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=7 AND cli_categoria='".CLI_CATEGORIA_CLIENTE."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=8 AND cli_categoria='".CLI_CATEGORIA_CLIENTE."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=9 AND cli_categoria='".CLI_CATEGORIA_CLIENTE."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=10 AND cli_categoria='".CLI_CATEGORIA_CLIENTE."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=11 AND cli_categoria='".CLI_CATEGORIA_CLIENTE."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=12 AND cli_categoria='".CLI_CATEGORIA_CLIENTE."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta.")
");
$clientes = mysqli_fetch_array($consultaClientes, MYSQLI_BOTH);
for($i=0; $i<=11; $i++){if($clientes[$i]==null){$clientes[$i]=0;}}

$consultaProspectos=mysqli_query($conexionBdPrincipal,"SELECT 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=1 AND cli_categoria='".CLI_CATEGORIA_PROSPECTO."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=2 AND cli_categoria='".CLI_CATEGORIA_PROSPECTO."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=3 AND cli_categoria='".CLI_CATEGORIA_PROSPECTO."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=4 AND cli_categoria='".CLI_CATEGORIA_PROSPECTO."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=5 AND cli_categoria='".CLI_CATEGORIA_PROSPECTO."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=6 AND cli_categoria='".CLI_CATEGORIA_PROSPECTO."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=7 AND cli_categoria='".CLI_CATEGORIA_PROSPECTO."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=8 AND cli_categoria='".CLI_CATEGORIA_PROSPECTO."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=9 AND cli_categoria='".CLI_CATEGORIA_PROSPECTO."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=10 AND cli_categoria='".CLI_CATEGORIA_PROSPECTO."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=11 AND cli_categoria='".CLI_CATEGORIA_PROSPECTO."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta."), 
(SELECT count(cli_id) FROM clientes WHERE MONTH(cli_fecha_ingreso)=12 AND cli_categoria='".CLI_CATEGORIA_PROSPECTO."' AND YEAR(cli_fecha_ingreso)=".$agnoConsulta.")
");
$prospectos = mysqli_fetch_array($consultaProspectos, MYSQLI_BOTH);
for($i=0; $i<=11; $i++){if($prospectos[$i]==null){$prospectos[$i]=0;}}

$consultaVentas=mysqli_query($conexionBdPrincipal,"SELECT 
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=1 WHERE MONTH(fpab_fecha_abono)=1 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=1 WHERE MONTH(fpab_fecha_abono)=2 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=1 WHERE MONTH(fpab_fecha_abono)=3 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=1 WHERE MONTH(fpab_fecha_abono)=4 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=1 WHERE MONTH(fpab_fecha_abono)=5 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=1 WHERE MONTH(fpab_fecha_abono)=6 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=1 WHERE MONTH(fpab_fecha_abono)=7 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=1 WHERE MONTH(fpab_fecha_abono)=8 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=1 WHERE MONTH(fpab_fecha_abono)=9 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=1 WHERE MONTH(fpab_fecha_abono)=10 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=1 WHERE MONTH(fpab_fecha_abono)=11 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=1 WHERE MONTH(fpab_fecha_abono)=12 AND YEAR(fpab_fecha_abono)=".$agnoConsulta.")
");
$ventas = mysqli_fetch_array($consultaVentas, MYSQLI_BOTH);
for($i=0; $i<=11; $i++){if($ventas[$i]==null){$ventas[$i]=0;}}

$consultaEgresos=mysqli_query($conexionBdPrincipal,"SELECT 
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=2 WHERE MONTH(fpab_fecha_abono)=1 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=2 WHERE MONTH(fpab_fecha_abono)=2 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=2 WHERE MONTH(fpab_fecha_abono)=3 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=2 WHERE MONTH(fpab_fecha_abono)=4 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=2 WHERE MONTH(fpab_fecha_abono)=5 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=2 WHERE MONTH(fpab_fecha_abono)=6 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=2 WHERE MONTH(fpab_fecha_abono)=7 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=2 WHERE MONTH(fpab_fecha_abono)=8 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=2 WHERE MONTH(fpab_fecha_abono)=9 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=2 WHERE MONTH(fpab_fecha_abono)=10 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=2 WHERE MONTH(fpab_fecha_abono)=11 AND YEAR(fpab_fecha_abono)=".$agnoConsulta."),
(SELECT sum(fpab_valor) FROM facturacion_abonos INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=2 WHERE MONTH(fpab_fecha_abono)=12 AND YEAR(fpab_fecha_abono)=".$agnoConsulta.")
");
$egresos = mysqli_fetch_array($consultaEgresos, MYSQLI_BOTH);
for($i=0; $i<=11; $i++){if($egresos[$i]==null){$egresos[$i]=0;}}

$i=1;
$ipordia ="";
while($i<=31){
  $consultaIpdia=mysqli_query($conexionBdPrincipal,"SELECT sum(fpab_valor) FROM facturacion_abonos
	INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=1
	WHERE YEAR(fpab_fecha_abono)=".$agnoConsulta." AND MONTH(fpab_fecha_abono)=".$mesConsulta." AND DAY(fpab_fecha_abono)=".$i);
	$datosipdia = mysqli_fetch_array($consultaIpdia, MYSQLI_BOTH);
	if($datosipdia[0]=="") $datosipdia[0] = 0;
	$ipordia .= "[$i, $datosipdia[0]],";
	$i++;
}
$ipordia = substr($ipordia,0,-1);


$i=1;
$epordia ="";
while($i<=31){
  $consultaIpdia=mysqli_query($conexionBdPrincipal,"SELECT sum(fpab_valor) FROM facturacion_abonos
	INNER JOIN facturacion ON fact_id=fpab_factura AND fact_estado!=3 AND fact_tipo=2
	WHERE YEAR(fpab_fecha_abono)=".$agnoConsulta." AND MONTH(fpab_fecha_abono)=".$mesConsulta." AND DAY(fpab_fecha_abono)=".$i);
	$datosepdia = mysqli_fetch_array($consultaIpdia, MYSQLI_BOTH);
	if($datosepdia[0]=="") $datosepdia[0] = 0;
	$epordia .= "[$i, $datosepdia[0]],";
	$i++;
}
$epordia = substr($epordia,0,-1);
?>
<!-- styles -->

<!--[if IE 7]>
<link rel="stylesheet" href="css/font-awesome-ie7.min.css">
<![endif]-->


<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
<![endif]-->

<!--============ javascript ===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="https://www.google.com/jsapi"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script>
      google.load("visualization", "1", {
       packages: ["corechart"]
   });
   google.setOnLoadCallback(drawChart);
   function drawChart() {
       var data = google.visualization.arrayToDataTable([
           ['Task', 'Hours per Day'],
           ['Work', 11],
           ['Eat', 2],
           ['Commute', 2],
           ['Watch TV', 2],
           ['Sleepe', 7]
       ]);
       var options = {
           title: 'My Daily Activities',
		   slices: [{color: '#b51c44'},{color: '#ce4b27'},{color: '#009600'},{color: '#e88a05'},{color: '#3498db'}]
       };
       var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
       chart.draw(data, options);
   }
    </script>


<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Clientes', 'Prospectos'],
          ['Ene',  <?=$clientes[0];?>,      <?=$prospectos[0];?>],
          ['Feb',  <?=$clientes[1];?>,      <?=$prospectos[1];?>],
          ['Mar',  <?=$clientes[2];?>,      <?=$prospectos[2];?>],
		  ['Abr',  <?=$clientes[3];?>,      <?=$prospectos[3];?>],
		  ['May',  <?=$clientes[4];?>,      <?=$prospectos[4];?>],
		  ['Jun',  <?=$clientes[5];?>,      <?=$prospectos[5];?>],
		  ['Jul',  <?=$clientes[6];?>,      <?=$prospectos[6];?>],
		  ['Ago',  <?=$clientes[7];?>,      <?=$prospectos[7];?>],
		  ['Sep',  <?=$clientes[8];?>,      <?=$prospectos[8];?>],
		  ['Oct',  <?=$clientes[9];?>,      <?=$prospectos[9];?>],
		  ['Nov',  <?=$clientes[10];?>,      <?=$prospectos[10];?>],
          ['Dic',  <?=$clientes[11];?>,      <?=$prospectos[11];?>]
        ]);
        var options = {
          title: 'Prospectos y clientes',
		  		  series: [{color: '#3498db',pointSize: '5',curveType:'function'},{color: '#009600',pointSize: '5',curveType:'function'}]
        };
        var chart = new google.visualization.AreaChart(document.getElementById('chart_div1'));
        chart.draw(data, options);
      }
    </script>
    
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Sales', 'Expenses', 'tercero', 'cuatro'],
          ['2004',  1000,      400,100,100],
          ['2005',  1170,      460,100,100],
          ['2006',  660,       1120,100,100],
          ['2007',  1030,      540,100,100],
		  ['2009',  1030,      540,100,100]
        ]);
        var options = {
          title: 'Company Performance',
          vAxis: {title: 'Year',  titleTextStyle: {color: 'red'}},
		   series: [{color: '#3498db'},{color: '#e88a05'},{color: '#000000'},{color: 'red'}]
        };
        var chart = new google.visualization.BarChart(document.getElementById('chart_div2'));
        chart.draw(data, options);
      }
    </script>
    
    <?php
	$compradores = mysqli_query($conexionBdPrincipal,"SELECT cli_nombre, sum(fpab_valor) as total FROM facturacion_abonos
								INNER JOIN facturacion ON fact_id=fpab_factura AND fact_tipo=1 AND fact_estado!=3
								INNER JOIN clientes ON cli_id=fact_cliente
								WHERE YEAR(fpab_fecha_abono)=".$agnoConsulta."
								GROUP BY fact_cliente ORDER BY total desc LIMIT 0,10");
	$num = mysqli_num_rows($compradores);
	$i=1;
	$datos="";
	while($comp = mysqli_fetch_array($compradores, MYSQLI_BOTH)){
		if($i<$num){$datos .="['$comp[0]',  $comp[1]],";}
		else{$datos .="['$comp[0]',  $comp[1]]";}
		$i++;
	}
	?>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Cliente', 'Compras'],
          <?=$datos;?>
        ]);
        var options = {
          title: '10 Clientes con más abonos',
          hAxis: {title: 'Year', titleTextStyle: {color: 'red'}},
		  series: [{color: '#009600'}]
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_compradores'));
        chart.draw(data, options);
      }
    </script>
    
    <?php
	$confianza = mysqli_query($conexionBdPrincipal,"SELECT cli_nombre, count(fact_cliente) as numero FROM facturacion
								INNER JOIN clientes ON cli_id=fact_cliente
								WHERE fact_tipo=1 AND fact_estado!=3 AND YEAR(fact_fecha_real)=".$agnoConsulta."
								GROUP BY fact_cliente ORDER BY numero desc LIMIT 0,10");
	$num = mysqli_num_rows($confianza);
	$i=1;
	$datos="";
	while($conf = mysqli_fetch_array($confianza, MYSQLI_BOTH)){
		if($i<$num){$datos .="['$conf[0]',  $conf[1]],";}
		else{$datos .="['$conf[0]',  $conf[1]]";}
		$i++;
	}
	?>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Cliente', 'Reincidencia'],
          <?=$datos;?>
        ]);
        var options = {
          title: '10 Clientes con más reincidencia',
          hAxis: {title: 'Year', titleTextStyle: {color: 'red'}},
		  series: [{color: '#F37635'}]
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_confianza'));
        chart.draw(data, options);
      }
    </script>



    
    
<script type="text/javascript">
      google.load('visualization', '1', {packages: ['corechart']});
    </script>
  
    
<script type="text/javascript">
      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Bolivia', 'Ecuador', 'Madagascar', 'Papua New Guinea', 'Rwanda', 'Average'],
          ['2004/05',  165,      938,         522,             998,           450,      614.6],
          ['2005/06',  135,      1120,        599,             1268,          288,      682],
          ['2006/07',  157,      1167,        587,             807,           397,      623],
          ['2007/08',  139,      1110,        615,             968,           215,      609.4],
          ['2008/09',  136,      691,         629,             1026,          366,      569.6]
        ]);
        var options = {
          title : 'Monthly Coffee Production by Country',
          vAxis: {title: "Cups"},
          hAxis: {title: "Month"},
          seriesType: "bars",
          series:[{color: '#ce4b27'},{color: '#b51c44', type: "line", curveType:'function'},{color: '#5b3ab6'},{color: '#a300aa'},{color: '#009600'},{color: '#0093a8'}]
        };
        var chart = new google.visualization.ComboChart(document.getElementById('chart_div4'));
        chart.draw(data, options);
      }
      google.setOnLoadCallback(drawVisualization);
    </script>
    
<script type='text/javascript'>
      google.load('visualization', '1', {packages:['gauge']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Memory', 80],
          ['CPU', 55],
          ['Network', 68]
        ]);
        var options = {
          width: 400, height: 120,
          redFrom: 90, redTo: 100,
          greenFrom:75, greenTo: 90,
          minorTicks: 5
        };
        var chart = new google.visualization.Gauge(document.getElementById('chart_div5'));
        chart.draw(data, options);
      }
    </script>
    
<script type='text/javascript'>
     google.load('visualization', '1', {'packages': ['geochart']});
     google.setOnLoadCallback(drawRegionsMap);
      function drawRegionsMap() {
        var data = google.visualization.arrayToDataTable([
          ['Country', 'Popularity'],
          ['Germany', 200],
          ['United States', 300],
          ['Colombia', 400],
          ['Canada', 500],
          ['France', 600],
          ['RU', 700]
        ]);
        var options = {
			 colorAxis: {colors: ['#009600', '#e88a05', '#3498db']}
			};
        var chart = new google.visualization.GeoChart(document.getElementById('chart_div_geo'));
        chart.draw(data, options);
    };
    </script>
    
<script type='text/javascript'>
     google.load('visualization', '1', {'packages': ['geochart']});
     google.setOnLoadCallback(drawMarkersMap);
      function drawMarkersMap() {
      var data = google.visualization.arrayToDataTable([
        ['City',   'Population', 'Area'],
        ['Rome',      2761477,    1285.31],
        ['Milan',     1324110,    181.76],
        ['Naples',    959574,     117.27],
        ['Turin',     907563,     130.17],
        ['Palermo',   655875,     158.9],
        ['Genoa',     607906,     243.60],
        ['Bologna',   380181,     140.7],
        ['Florence',  371282,     102.41],
        ['Fiumicino', 67370,      213.44],
        ['Anzio',     52192,      43.43],
        ['Ciampino',  38262,      11]
      ]);
      var options = {
        region: 'IT',
        displayMode: 'markers',
        colorAxis: {colors: ['#009600', '#e88a05']}
      };
      var chart = new google.visualization.GeoChart(document.getElementById('chart_div_geo1'));
      chart.draw(data, options);
    };
    </script>
    
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Ingresos'],
          ['Ene',  <?=$ventas[0];?>],
          ['Feb',  <?=$ventas[1];?>],
          ['Mar',  <?=$ventas[2];?>],
		  ['Abr',  <?=$ventas[3];?>],
		  ['May',  <?=$ventas[4];?>],
		  ['Jun',  <?=$ventas[5];?>],
		  ['Jul',  <?=$ventas[6];?>],
		  ['Ago',  <?=$ventas[7];?>],
		  ['Sept', <?=$ventas[8];?>],
		  ['Oct',  <?=$ventas[9];?>],
		  ['Nov',  <?=$ventas[10];?>],
          ['Dic',  <?=$ventas[11];?>]
        ]);
        var options = {
          title: 'Ventas',
		  series: [{color: '#3498db',pointSize: '8',curveType:'function'}]
        };
        var chart = new google.visualization.LineChart(document.getElementById('chart_div_line'));
        chart.draw(data, options);
      }
    </script>
    
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Egresos'],
          ['Ene',  <?=$egresos[0];?>],
          ['Feb',  <?=$egresos[1];?>],
          ['Mar',  <?=$egresos[2];?>],
		  ['Abr',  <?=$egresos[3];?>],
		  ['May',  <?=$egresos[4];?>],
		  ['Jun',  <?=$egresos[5];?>],
		  ['Jul',  <?=$egresos[6];?>],
		  ['Ago',  <?=$egresos[7];?>],
		  ['Sept', <?=$egresos[8];?>],
		  ['Oct',  <?=$egresos[9];?>],
		  ['Nov',  <?=$egresos[10];?>],
          ['Dic',  <?=$egresos[11];?>]
        ]);
        var options = {
          title: 'Egresos',
		  series: [{color: '#F23700',pointSize: '8',curveType:'function'}]
        };
        var chart = new google.visualization.LineChart(document.getElementById('chart_div_line_egresos'));
        chart.draw(data, options);
      }
    </script>
    
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Dias', 'Ingresos'],
          <?=$ipordia;?>
        ]);
        var options = {
          title: 'Ingresos del mes',
		  series: [{color: '#3498db',pointSize: '8',curveType:'function'}]
        };
        var chart = new google.visualization.LineChart(document.getElementById('ingresodiario'));
        chart.draw(data, options);
      }
    </script>
    
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Dias', 'Egresos'],
          <?=$epordia;?>
        ]);
        var options = {
          title: 'Egresos del mes',
		  series: [{color: '#F23700',pointSize: '8',curveType:'function'}]
        };
        var chart = new google.visualization.LineChart(document.getElementById('egresodiario'));
        chart.draw(data, options);
      }
    </script>

 
<style>
.widget-container{ padding-bottom:20px;}
</style>

<?php include("includes/funciones-js.php");?>
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
				</div>
			</div>
			
            
            <div align="center" style="margin:10px; font-size:20px;">
            <?php
			$a = $configuracion['conf_agno_inicio'];
			while($a<=date("Y")){
				if($a==$agnoConsulta)
					echo '<a style="font-weight:bold;">'.$a.'</a>&nbsp;&nbsp;&nbsp;';
				else
					echo '<a href="estadisticas.php?a='.$a.'&m='.$_GET["m"].'&u='.$_GET["u"].'">'.$a.'</a>&nbsp;&nbsp;&nbsp;';	
            	$a++;
			}
			?>
            </div>
            
            <div align="center" style="margin:10px; font-size:18px;">
            <?php
			$m = 1;
			while($m<=12){
				if($m==$mesConsulta)
					echo '<a style="font-weight:bold;">'.$mesesAbre[$m].'</a>&nbsp;&nbsp;&nbsp;';
				else
					echo '<a href="estadisticas.php?m='.$m.'&a='.$_GET["a"].'&u='.$_GET["u"].'">'.$mesesAbre[$m].'</a>&nbsp;&nbsp;&nbsp;';	
            	$m++;
			}
			?>
            </div>

            
            <div class="row-fluid">
				<div class="span5">
					<div class="content-widgets white">
						<div class="widget-head blue">
							<h3>Ingresos del año</h3>
						</div>
						<div class="widget-container">
							<div id="chart_div_line">
							</div>
						</div>
					</div>
				</div>
			
				<div class="span7">
					<div class="content-widgets white">
						<div class="widget-head blue">
							<h3>Ingresos del mes</h3>
						</div>
						<div class="widget-container">
							<div id="ingresodiario">
							</div>
						</div>
					</div>
				</div>
			</div>
            
            <div class="row-fluid">
				<div class="span5">
					<div class="content-widgets white">
						<div class="widget-head blue-violate">
							<h3>Egresos del año</h3>
						</div>
						<div class="widget-container">
							<div id="chart_div_line_egresos">
							</div>
						</div>
					</div>
				</div>
				
				<div class="span7">
					<div class="content-widgets white">
						<div class="widget-head blue-violate">
							<h3>Egresos del mes</h3>
						</div>
						<div class="widget-container">
							<div id="egresodiario">
							</div>
						</div>
					</div>
				</div>
			</div>
            
            <div class="row-fluid">
            	<div class="span6">
					<div class="content-widgets white">
						<div class="widget-head green">
							<h3>Clientes con más abonos</h3>
						</div>
						<div class="widget-container">
							<div id="chart_div_compradores">
							</div>
						</div>
					</div>
				</div>

            	<div class="span6">
					<div class="content-widgets white">
						<div class="widget-head orange">
							<h3>Clientes con más reincidencia</h3>
						</div>
						<div class="widget-container">
							<div id="chart_div_confianza">
							</div>
						</div>
					</div>
				</div>
            </div>
            
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets white">
						<div class="widget-head blue-violate">
							<h3>Relación prospectos/clientes</h3>
						</div>
						<div class="widget-container">
							<div id="chart_div1">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span6">
					<div class="content-widgets white">
						<div class="widget-head blue">
							<h3>Bar Chart</h3>
						</div>
						<div class="widget-container">
							<div id="chart_div2">
							</div>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="content-widgets white">
						<div class="widget-head orange">
							<h3>Promedio mensual</h3>
						</div>
						<div class="widget-container">
							<div id="chart_div3">
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