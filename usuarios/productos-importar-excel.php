<?php
include("sesion.php");
$idPagina = 207;
include("includes/verificar-paginas.php");

require '../librerias/Excel/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$temName=$_FILES['planilla']['tmp_name'];
$archivo = $_FILES['planilla']['name'];
$destino = "files/excel/";
$explode = explode(".", $archivo);
$extension = end($explode);
$fullArchivo = uniqid('importado_').".".$extension;
$nombreArchivo= $destino.$fullArchivo;

if($extension == 'xlsx'){

	if (move_uploaded_file($temName, $nombreArchivo)) {		
		
		if ($_FILES['planilla']['error'] === UPLOAD_ERR_OK){

			$documento= IOFactory::load($nombreArchivo);
			$totalHojas= $documento->getSheetCount();

			$hojaActual = $documento->getSheet(0);
			$numFilas = $hojaActual->getHighestDataRow();
			if($_POST["filaFinal"] > 0){
				$numFilas = $_POST["filaFinal"];
			}
			$letraColumnas= $hojaActual->getHighestDataColumn();
			$f=2;
			$arrayTodos = [];
			$claves_validar = array('prod_id', 'prod_referencia', 'prod_nombre');

			$sql = "INSERT INTO productos(prod_id, prod_referencia, prod_nombre, prod_grupo1, prod_categoria, prod_marca, prod_costo, prod_visible, prod_ultima_actualizacion, prod_ultima_actualizacion_usuario) VALUES ";

			if($_SESSION["id"]==7){
				$sql = "INSERT INTO productos(prod_id, prod_referencia, prod_nombre, prod_grupo1, prod_categoria, prod_marca, prod_costo, prod_descuento1, prod_descuento2, prod_utilidad, prod_precio_fabrica, prod_flete, prod_aduana, prod_costo_dolar, prod_visible, prod_ultima_actualizacion, prod_ultima_actualizacion_usuario) VALUES ";
			}
			
			$productosCreados      = array();
			$productosActualizados = array();
			$productosNoCreados    = array();

			while($f<=$numFilas){

				/*
				***************PRODUCTOS********************
				*/
				$todoBien = true;

				$arrayIndividual = [
					'prod_id'		   						 => $hojaActual->getCell('B'.$f)->getValue(),
					'prod_referencia'   					 => $hojaActual->getCell('C'.$f)->getValue(),
					'prod_nombre'        					 => $hojaActual->getCell('D'.$f)->getValue(),
					'prod_grupo1'          					 => $hojaActual->getCell('E'.$f)->getValue(),
					'prod_categoria'          				 => $hojaActual->getCell('G'.$f)->getValue(),
					'prod_marca'  							 => $hojaActual->getCell('I'.$f)->getValue(),
					'prod_costo' 							 => $hojaActual->getCell('L'.$f)->getValue(),
				];

				if($_SESSION["id"]==7){
					$arrayIndividual = [
						'prod_id'		   						 => $hojaActual->getCell('B'.$f)->getValue(),
						'prod_referencia'   					 => $hojaActual->getCell('C'.$f)->getValue(),
						'prod_nombre'        					 => $hojaActual->getCell('D'.$f)->getValue(),
						'prod_grupo1'          					 => $hojaActual->getCell('E'.$f)->getValue(),
						'prod_categoria'          				 => $hojaActual->getCell('G'.$f)->getValue(),
						'prod_marca'  							 => $hojaActual->getCell('I'.$f)->getValue(),
						'prod_costo' 							 => $hojaActual->getCell('L'.$f)->getValue(),
	
						'prod_descuento1'           			 => $hojaActual->getCell('M'.$f)->getValue(),
						'prod_descuento2' 						 => $hojaActual->getCell('N'.$f)->getValue(),
						'prod_utilidad'            				 => $hojaActual->getCell('O'.$f)->getValue(),
						'prod_precio_fabrica'            		 => $hojaActual->getCell('P'.$f)->getValue(),
						'prod_flete'        					 => $hojaActual->getCell('Q'.$f)->getValue(),
						'prod_aduana'           				 => $hojaActual->getCell('R'.$f)->getValue(),
						'prod_costo_dolar'          			 => $hojaActual->getCell('S'.$f)->getValue(),
					];
				}

				//Validamos que los campos más importantes no vengan vacios
				foreach ($claves_validar as $clave) {
					if (empty($arrayIndividual[$clave])) {
						$todoBien = false;
					}
				}

				//Si los campos están completos entonces ordenamos los datos del producto
				if($todoBien) {

					$consultaProducto=$conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_id='".$arrayIndividual['prod_id']."'");
					$numProducto = mysqli_num_rows($consultaProducto);

					$origen = 0;
					if(!empty($_POST["id"])){
						$datos = mysqli_fetch_array($conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_id='".$_POST["id"]."'"), MYSQLI_BOTH);
				
						if($datos['prod_costo'] != $arrayIndividual['prod_costo']){
							$origen = 1;
						}
					}

					if($numProducto > 0) {

						$costoDolar=0;
						if(!empty($arrayIndividual['prod_costo_dolar'])){
							$costoDolar=$arrayIndividual['prod_costo_dolar'];
						}
						$camposActualizar=", prod_costo_dolar='".round($costoDolar,2)."'";

						if($_SESSION["id"]!=7){
							$camposActualizar=", prod_referencia='".$arrayIndividual['prod_referencia']."', prod_nombre='".$arrayIndividual['prod_nombre']."', prod_grupo1='".$arrayIndividual['prod_grupo1']."', prod_categoria='".$arrayIndividual['prod_categoria']."', prod_marca='".$arrayIndividual['prod_marca']."', prod_costo='".$arrayIndividual['prod_costo']."'";

							if($origen > 0){
								$utilidad = $datos['prod_utilidad']/100;
								$precio1 = $arrayIndividual['prod_costo'] + ($arrayIndividual['prod_costo'] * $utilidad);
			
								$conexionBdPrincipal->query("INSERT INTO productos_historial_precios(php_producto, php_precio_anterior, php_precio_nuevo, php_usuario, php_causa)VALUES('".$arrayIndividual['prod_id']."', '".$datos['prod_precio']."', '".$precio1."', '".$_SESSION["id"]."', '".$origen."')");
							}
						}

						try {
							$conexionBdPrincipal->query("UPDATE productos SET
							prod_ultima_actualizacion=now(),
							prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' $camposActualizar
							WHERE prod_id='".$arrayIndividual['prod_id']."'");

							$productosActualizados["FILA_".$f] = $arrayIndividual['prod_id'];

						} catch (Exception $e) {
							echo "Excepción catpurada: ".$e->getMessage();
							exit();
						}

					} else {

						$arrayTodos[$f] = $arrayIndividual;

						if($_SESSION["id"]==7){

							$sql .= "('".$arrayIndividual['prod_id']."', '".$arrayIndividual['prod_referencia']."', '".$arrayIndividual['prod_nombre']."', '".$arrayIndividual['prod_grupo1']."', '".$arrayIndividual['prod_categoria']."', '".$arrayIndividual['prod_marca']."', '".$arrayIndividual['prod_costo']."', '".$arrayIndividual['prod_descuento1']."', '".$arrayIndividual['prod_descuento2']."', '".$arrayIndividual['prod_utilidad']."', '".$arrayIndividual['prod_precio_fabrica']."', '".$arrayIndividual['prod_flete']."', '".$arrayIndividual['prod_aduana']."', '".$arrayIndividual['prod_costo_dolar']."', 1, now(), '".$_SESSION["id"]."'),";

						}else{

							$sql .= "('".$arrayIndividual['prod_id']."', '".$arrayIndividual['prod_referencia']."', '".$arrayIndividual['prod_nombre']."', '".$arrayIndividual['prod_grupo1']."', '".$arrayIndividual['prod_categoria']."', '".$arrayIndividual['prod_marca']."', '".$arrayIndividual['prod_costo']."', 1, now(), '".$_SESSION["id"]."'),";

						}

						$productosCreados["FILA_".$f] = $arrayIndividual['prod_id'];

					}
				} else {
					$productosNoCreados[] = "FILA ".$f;
				}

				$f++;
			}
			
			$numeroProductosCreados = 0;
			if(!empty($productosCreados)){
				$numeroProductosCreados = count($productosCreados);
			}

			$numeroProductosActualizados = 0;
			if(!empty($productosActualizados)){
				$numeroProductosActualizados = count($productosActualizados);
			}

			$numeroProductosNoCreados = 0;
			if(!empty($productosNoCreados)){
				$numeroProductosNoCreados = count($productosNoCreados);
			}

			$respuesta = [
				"summary" => "
					Resumen del proceso:<br>
					- Total filas leidas: {$numFilas}<br><br>
					- Productos creados nuevos: {$numeroProductosCreados}<br>
					- Productos que ya estaban creados y se les actualizó la información: {$numeroProductosActualizados}<br>
					- Productos que les faltó algun campo obligatorio: {$numeroProductosNoCreados}<br><br>
				"
			];

			$summary = http_build_query($respuesta);

			if(!empty($productosCreados) && count($productosCreados) > 0) {
				$sql = substr($sql, 0, -1);
				try {
					$conexionBdPrincipal->query($sql);
				} catch(Exception $e){
					print_r($sql);
					echo "<br>Hubo un error al guardar todo los datos: ".$e->getMessage();
					exit();
				}
			}

			if(file_exists($nombreArchivo)){
				unlink($nombreArchivo);
			}
			
			echo '<script type="text/javascript">window.location.href="productos.php?msg=15&'.$summary.'";</script>';
			exit();

		}else{
			switch ($_FILES['planilla']['error']) {
				case UPLOAD_ERR_INI_SIZE:
					$message = "El fichero subido excede la directiva upload_max_filesize de php.ini.";
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$message = "El fichero subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML.";
					break;
		
				case UPLOAD_ERR_PARTIAL:
					$message = "El fichero fue sólo parcialmente subido.";
					break;
		
				case UPLOAD_ERR_NO_FILE:
					$message = "No se subió ningún fichero.";
					break;
		
				case UPLOAD_ERR_NO_TMP_DIR:
					$message = "Falta la carpeta temporal.";
					break;
		
				case UPLOAD_ERR_CANT_WRITE:
					$message = "No se pudo escribir el fichero en el disco.";
					break;
				case UPLOAD_ERR_EXTENSION:
					$message = "Una extensión de PHP detuvo la subida de ficheros. PHP no proporciona una forma de determinar la extensión que causó la parada de la subida de ficheros; el examen de la lista de extensiones cargadas con phpinfo() puede ayudar.";
					break;
			}
			echo '<script type="text/javascript">window.location.href="productos-importar.php?error=3&message='.$message.'";</script>';
			exit();
		}
	}else{
		$message = 'El archivo enviado es invalido. Por favor vuelva a intentarlo.';
		echo '<script type="text/javascript">window.location.href="productos-importar.php?error=3&message='.$message.'";</script>';
		exit();
	}	
}else{
	$message = "Este archivo no es admitido, por favor verifique que el archivo a importar sea un excel (.xlsx)";
	echo '<script type="text/javascript">window.location.href="productos-importar.php?error=3&message='.$message.'";</script>';
	exit();
}