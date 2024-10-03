<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/softjm/constantes.php");
require_once(RUTA_PROYECTO."/conexion.php");
require_once(RUTA_PROYECTO."/usuarios/config/config.php");
require_once(RUTA_PROYECTO."/usuarios/includes/funciones-para-el-sistema.php");

date_default_timezone_set("America/Bogota");//Zona horaria

require '../librerias/Excel/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\CellAddress;
use PhpOffice\PhpSpreadsheet\IOFactory;

$excel      = new Spreadsheet();
$hojaActiva = $excel->getActiveSheet();

$hojaActiva->setTitle("Productos en bodegas");

$hojaActiva->getStyle('A1:F1')->getFont()->setBold('Bold')->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKBLUE);

$hojaActiva->getColumnDimension('A')->setWidth(10);
$hojaActiva->setCellValue([1,1], 'COD');

$hojaActiva->getColumnDimension('B')->setWidth(15);
$hojaActiva->setCellValue([2,1], 'COD. BODEGA');

$hojaActiva->getColumnDimension('C')->setWidth(30);
$hojaActiva->setCellValue([3,1], 'BODEGA');

$hojaActiva->getColumnDimension('D')->setWidth(30);
$hojaActiva->setCellValue([4,1], 'COD. PRODUCTO');

$hojaActiva->getColumnDimension('E')->setWidth(80);
$hojaActiva->setCellValue([5,1], 'PRODUCTO');

$hojaActiva->getColumnDimension('F')->setWidth(30);
$hojaActiva->setCellValue([6,1], 'EXISTENCIAS');

$hojaActiva->getColumnDimension('G')->setWidth(50);

$filtro = '';
if(isset($_GET["bod"])){
    if ($_GET["bod"] != "") {
        $filtro .= " AND prodb_bodega='" . $_GET["bod"] . "'";
    }
}
if(isset($_GET["prod"])){
    if ($_GET["prod"] != "") {
        $filtro .= " AND prodb_producto='" . $_GET["prod"] . "'";
    }
}

try {
    $consulta = $conexionBdPrincipal->query("SELECT * FROM productos_bodegas 
    INNER JOIN productos ON prod_id=prodb_producto 
    INNER JOIN bodegas ON bod_id=prodb_bodega 
    LEFT JOIN usuarios ON usr_id=prodb_usuario_actualizacion 
    WHERE 
        prodb_id=prodb_id 
    AND prod_id_empresa='".$idEmpresa."' 
    AND bod_id_empresa='".$idEmpresa."' 
    $filtro");

} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    exit();
}

$i = 2;

while ($res = mysqli_fetch_array($consulta, MYSQLI_ASSOC)) {

    $hojaActiva->setCellValue('A'.$i, $res['prodb_id']);
    $hojaActiva->setCellValue('B'.$i, $res['bod_id']);
    $hojaActiva->setCellValue('C'.$i, $res['bod_nombre']);
    $hojaActiva->setCellValue('D'.$i, $res['prod_referencia']);
    $hojaActiva->setCellValue('E'.$i, $res['prod_nombre']);
    $hojaActiva->setCellValue('F'.$i, $res['prodb_existencias']);
	// Set cell A4 with a formula
	$hojaActiva->setCellValue(
		'G'.$i,
		'=IF(F'.$i.' < 0, "INGRESE EXISTENCIAS POSITIVAS, POR FAVOR.", "")'
	);


    $i ++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="productos_bodegas_'.date("dmYHis").'.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit();