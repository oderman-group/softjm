<?php
include("sesion.php");

$idPagina = 207;

include("includes/verificar-paginas.php");

if($_FILES['planilla']['name']!=""){
	$archivo = $_FILES['planilla']['name'];
	$destino = "files/excel";
	move_uploaded_file($_FILES['planilla']['tmp_name'], $destino ."/".$archivo);
}
//set_time_limit (0);

// Test CVS
require_once '../Excel/reader.php';


// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();

// Set output Encoding.
$data->setOutputEncoding('CP1251');

//Read File
$data->read('files/excel/'.$archivo);

error_reporting(E_ALL ^ E_NOTICE);
/*
for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		echo $data->sheets[0]['cells'][$i][$j].", ";	
	}
	echo "<br>";
}
*/
$eliminados = 0;
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
	if(trim($data->sheets[0]['cells'][$i][1])!=""){
		//echo $data->sheets[0]['cells'][$i][3]."<br>"; exit();
		
		//ELIMINAR REPETIDOS POR REFERENCIA
		/*
		if($data->sheets[0]['cells'][$i][2]<=52){
			$numRpt = mysql_num_rows($conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_referencia='".$data->sheets[0]['cells'][$i][3]."'"));
			
				
			if($numRpt>1){
				$conexionBdPrincipal->query("DELETE FROM productos WHERE prod_referencia='".$data->sheets[0]['cells'][$i][3]."' AND prod_id>52");
				
				$eliminados++; 
			}
		}
		*/
		$consultaProducto=$conexionBdPrincipal->query("SELECT * FROM productos 
		WHERE prod_id='".$data->sheets[0]['cells'][$i][2]."'");
		$numProducto = $consultaProducto->num_rows;

		$datos = mysqli_fetch_array($conexionBdPrincipal->query("SELECT * FROM productos 
			WHERE prod_id='".$_POST["id"]."'"), MYSQLI_BOTH);

		$origen = 0;

		if($datos['prod_costo'] != $data->sheets[0]['cells'][$i][12]){
			$origen = 1;
		}
		
		//USUARIOS ESPECIALES
		if($_SESSION["id"]==7 /*or $_SESSION["id"]==15*/){
			if($numProducto==0){
				
				$conexionBdPrincipal->query("INSERT INTO productos(
				prod_id,
				prod_referencia,
				prod_nombre,
				prod_grupo1,
				prod_categoria,
				prod_marca,
				prod_costo,
				
				prod_descuento1,
				prod_descuento2,
				prod_utilidad,
				prod_precio_fabrica,
				prod_flete,
				prod_aduana,
				prod_costo_dolar,
				
				prod_visible,
				prod_ultima_actualizacion,
				prod_ultima_actualizacion_usuario
				)
				VALUES(
				'".$data->sheets[0]['cells'][$i][2]."',
				'".$data->sheets[0]['cells'][$i][3]."', 
				'".$data->sheets[0]['cells'][$i][4]."', 
				'".$data->sheets[0]['cells'][$i][5]."',
				'".$data->sheets[0]['cells'][$i][7]."',
				'".$data->sheets[0]['cells'][$i][9]."',
				'".$data->sheets[0]['cells'][$i][12]."',
				
				'".$data->sheets[0]['cells'][$i][13]."',
				'".$data->sheets[0]['cells'][$i][14]."',
				'".$data->sheets[0]['cells'][$i][15]."',
				'".$data->sheets[0]['cells'][$i][16]."',
				'".$data->sheets[0]['cells'][$i][17]."',
				'".$data->sheets[0]['cells'][$i][18]."',
				'".$data->sheets[0]['cells'][$i][19]."',
				
				1,
				now(),
				'".$_SESSION["id"]."'
				)
				");
				
			}else{

				$conexionBdPrincipal->query("UPDATE productos SET
				prod_costo_dolar='".round($data->sheets[0]['cells'][$i][19],2)."',

				prod_ultima_actualizacion=now(),
				prod_ultima_actualizacion_usuario='".$_SESSION["id"]."'
				WHERE prod_id='".$data->sheets[0]['cells'][$i][2]."'
				");
				
				
				
				
				/*
				$conexionBdPrincipal->query("UPDATE productos SET 
				prod_referencia='".$data->sheets[0]['cells'][$i][3]."',
				prod_nombre='".$data->sheets[0]['cells'][$i][4]."', 
				prod_categoria='".$data->sheets[0]['cells'][$i][7]."', 
				prod_costo='".$data->sheets[0]['cells'][$i][12]."', 
				prod_descuento1='".$data->sheets[0]['cells'][$i][13]."', 
				prod_descuento2='".$data->sheets[0]['cells'][$i][14]."',
				prod_grupo1='".$data->sheets[0]['cells'][$i][5]."',
				prod_marca='".$data->sheets[0]['cells'][$i][9]."',
				prod_existencias='".$data->sheets[0]['cells'][$i][11]."',

				prod_precio_fabrica='".$data->sheets[0]['cells'][$i][16]."',
				prod_flete='".$data->sheets[0]['cells'][$i][17]."',
				prod_aduana='".$data->sheets[0]['cells'][$i][18]."',
				prod_costo_dolar='".$data->sheets[0]['cells'][$i][19]."',

				prod_ultima_actualizacion=now(),
				prod_ultima_actualizacion_usuario='".$_SESSION["id"]."'
				WHERE prod_id='".$data->sheets[0]['cells'][$i][2]."'
				");
				
				
				
				$utilidad = $data->sheets[0]['cells'][$i][15]/100;
				$precio1 = $data->sheets[0]['cells'][$i][12] + ($data->sheets[0]['cells'][$i][12]*$utilidad);
				
				$conexionBdPrincipal->query("UPDATE productos SET prod_utilidad='".$data->sheets[0]['cells'][$i][15]."', prod_precio='".$precio1."' 
				WHERE prod_id='".$data->sheets[0]['cells'][$i][2]."'");
				*/
			}
		}
		//OTROS USUARIOS
		else{
			if($numProducto==0){
				$conexionBdPrincipal->query("INSERT INTO productos(
				prod_id,
				prod_referencia,
				prod_nombre,
				prod_grupo1,
				prod_categoria,
				prod_marca,
				prod_costo,
				
				prod_visible,
				prod_ultima_actualizacion,
				prod_ultima_actualizacion_usuario
				)
				VALUES(
				'".$data->sheets[0]['cells'][$i][2]."',
				'".$data->sheets[0]['cells'][$i][3]."', 
				'".$data->sheets[0]['cells'][$i][4]."', 
				'".$data->sheets[0]['cells'][$i][5]."',
				'".$data->sheets[0]['cells'][$i][7]."',
				'".$data->sheets[0]['cells'][$i][9]."',
				'".$data->sheets[0]['cells'][$i][12]."',
				
				1,
				now(),
				'".$_SESSION["id"]."'
				)
				");
			}else{
				$conexionBdPrincipal->query("UPDATE productos SET 
				prod_referencia='".$data->sheets[0]['cells'][$i][3]."',
				prod_nombre='".$data->sheets[0]['cells'][$i][4]."', 
				prod_grupo1='".$data->sheets[0]['cells'][$i][5]."',
				prod_categoria='".$data->sheets[0]['cells'][$i][7]."',
				prod_marca='".$data->sheets[0]['cells'][$i][9]."',
				prod_costo='".$data->sheets[0]['cells'][$i][12]."', 
				
				prod_ultima_actualizacion=now(),
				prod_ultima_actualizacion_usuario='".$_SESSION["id"]."'
				WHERE prod_id='".$data->sheets[0]['cells'][$i][2]."'
				");
				


				if($origen > 0){

					$utilidad = $datos['prod_utilidad']/100;
					$precio1 = $data->sheets[0]['cells'][$i][12] + ($data->sheets[0]['cells'][$i][12] * $utilidad);

					$conexionBdPrincipal->query("INSERT INTO productos_historial_precios(php_producto, php_precio_anterior, php_precio_nuevo, php_usuario, php_causa)VALUES('".$data->sheets[0]['cells'][$i][2]."', '".$datos['prod_precio']."', '".$precio1."', '".$_SESSION["id"]."', '".$origen."')");
					

				}
				
			}
			
		}
		
	}
	//echo "Eliminados: ".$eliminados;
}
//exit();

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="productos.php";</script>';
exit();


//print_r($data);
//print_r($data->formatRecords);
?>
