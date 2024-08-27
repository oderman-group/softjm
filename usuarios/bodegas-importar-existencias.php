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

			if ($_POST["filaFinal"] > 0) {
				$numFilas = $_POST["filaFinal"];
			}

			$letraColumnas = $hojaActual->getHighestDataColumn();
			$f             = 2;

			while ($f <= $numFilas) {

				mysqli_query($conexionBdPrincipal,"UPDATE productos_bodegas 
				SET prodb_existencias = '".$hojaActual->getCell('E'.$f)->getValue()."', 
				prodb_fecha_actualizacion = now(), 
				prodb_usuario_actualizacion = '".$_SESSION["id"]."' 
				WHERE prodb_id='".$hojaActual->getCell('A'.$f)->getValue()."'
				");

				$f++;
			}

			if(file_exists($nombreArchivo)){
				unlink($nombreArchivo);
			}

			$message       = 'Las existencias se han actualizado correctamente.';
			$tituloMensaje = "Exito!";
			$tipoAlerta    = "success";

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
?>

<div class="alert alert-<?=$tipoAlerta;?>">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<i class="icon-exclamation-sign"></i><strong><?=$tituloMensaje;?></strong> <?=$message;?>
</div>