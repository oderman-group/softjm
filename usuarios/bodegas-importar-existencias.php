<?php
include("sesion.php");
include_once RUTA_PROYECTO."/usuarios/class/Producto.php";

$idPagina = 207;
include("includes/verificar-paginas.php");

require '../librerias/Excel/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$temName       = $_FILES['planilla']['tmp_name'];
$archivo       = $_FILES['planilla']['name'];
$destino       = "files/excel/";
$explode       = explode(".", $archivo);
$extension     = end($explode);
$fullArchivo   = uniqid('importado_').".".$extension;
$nombreArchivo = $destino.$fullArchivo;

$tipoAlerta   = "danger";
$tituloMensaje = "Error!";

if ($extension == 'xlsx') {

	if ($_FILES['planilla']['error'] != UPLOAD_ERR_OK){
		$message = 'Ha ocurrido un error al subir el archivo: '.$_FILES['planilla']['error'];
	}

	if (move_uploaded_file($temName, $nombreArchivo)) {

		if ($_FILES['planilla']['error'] === UPLOAD_ERR_OK) {

			$documento  = IOFactory::load($nombreArchivo);
			$totalHojas = $documento->getSheetCount();
			$hojaActual = $documento->getSheet(0);
			$numFilas   = $hojaActual->getHighestDataRow();

			$letraColumnas = $hojaActual->getHighestDataColumn();
			$f             = 2;

			$registrosActualizados    = 0;
			$registrosSinCoincidencia = 0;
			$registrosConError        = 0;

			try {

				if ($numFilas <= 1) {
					$message = 'No se encontraron filas a procesar en este archivo.';
					throw new Exception("No se encontraron filas a procesar en este archivo.");
				}

				while ($f <= $numFilas) {

					//Buscar los datos del producto con el código de World Office: Ejemplo: ACC 108
					if (!empty($hojaActual->getCell('D'.$f)->getValue())) {
						$predicado = [
							'prod_referencia' => $hojaActual->getCell('D'.$f)->getValue(),
							'prod_id_empresa' => $idEmpresa
						];

						$datosCompletosProducto = mysqli_fetch_array(Producto::Select($predicado),MYSQLI_ASSOC);
					} else {
						$f++;
						continue;
					}

					//Validar y continuar en caso tal de no encontrar coincidencia
					if (empty($datosCompletosProducto)) {
						$f++;
						continue;
					}

					$existencia = intval($hojaActual->getCell('G'.$f)->getValue());

					if (filter_var($existencia, FILTER_VALIDATE_INT) === false) {
						$f++;
						continue;
					}

					try {
						//Actualizar las existencias basado en el ID del producto en el CRM y en el código de la Bodega
						$result = mysqli_query($conexionBdPrincipal,"UPDATE productos_bodegas 
						SET prodb_existencias = '".$existencia."', 
						prodb_fecha_actualizacion = now(), 
						prodb_usuario_actualizacion = '".$_SESSION["id"]."' 
						WHERE 
							prodb_producto = ".$datosCompletosProducto['prod_id']." 
						AND prodb_bodega = ".$hojaActual->getCell('B'.$f)->getValue()."
						");

						$filasAfectadas = mysqli_affected_rows($conexionBdPrincipal);

						if ($filasAfectadas > 0) {
							$registrosActualizados ++;
						} else {
							$registrosSinCoincidencia ++;
						}
					} catch(Exception $e) {
						$registrosConError ++;
					}

					//Sincronizar existencias con el ID del producto consultado
					Producto::sincronizarExistenciasConBodegas($datosCompletosProducto['prod_id'], $conexionBdPrincipal);

					$f++;
				}

				if(file_exists($nombreArchivo)){
					unlink($nombreArchivo);
				}

				$message       = 'Las existencias se han actualizado correctamente.';
				$tituloMensaje = "Exito!";
				$tipoAlerta    = "success";
			} catch (Exception $e) {
					$message = 'Ha ocurrido un error.';
			}

		} else {
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

		}

	} else {
		$message = 'El archivo enviado es invalido. Por favor vuelva a intentarlo: '.$_FILES['planilla']['error'];
	}

} else {
	$message = "Este archivo no es admitido, por favor verifique que el archivo a importar sea un excel (.xlsx)";
}

if ($tipoAlerta == 'success') {
	echo '
		<div class="alert alert-info">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Resumen del proceso</strong><br> 
			✅ Registros Actualizados: '.$registrosActualizados.'<br>
			⚠️ Registros sin coincidencia en esta bodega: '.$registrosSinCoincidencia.'<br>
			❌ Registros que generaron error: '.$registrosConError.'
			</div>';
} else {
?>
	<div class="alert alert-<?=$tipoAlerta;?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<i class="icon-exclamation-sign"></i><strong><?=$tituloMensaje;?></strong> <?=$message;?>
	</div>
<?php }