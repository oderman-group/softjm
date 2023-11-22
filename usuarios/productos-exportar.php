<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/softjm/constantes.php");
require_once(RUTA_PROYECTO."/conexion.php");
require_once(RUTA_PROYECTO."/usuarios/config/config.php");
require_once(RUTA_PROYECTO."/usuarios/includes/funciones-para-el-sistema.php");

date_default_timezone_set("America/Bogota");//Zona horaria

require '../librerias/Excel/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$excel= new Spreadsheet();
$hojaActiva= $excel->getActiveSheet();
$hojaActiva->setTitle("Productos");

$hojaActiva->getColumnDimension('A')->setWidth(5);
$hojaActiva->setCellValue('A1', 'Nº');
$hojaActiva->getColumnDimension('B')->setWidth(8);
$hojaActiva->setCellValue('B1', 'ID');
$hojaActiva->getColumnDimension('C')->setWidth(18);
$hojaActiva->setCellValue('C1', 'Código');
$hojaActiva->getColumnDimension('D')->setWidth(20);
$hojaActiva->setCellValue('D1', 'Nombre');

$hojaActiva->getColumnDimension('E')->setWidth(8);
$hojaActiva->setCellValue('E1', 'Cod. G1');
$hojaActiva->getColumnDimension('F')->setWidth(20);
$hojaActiva->setCellValue('F1', 'Grupo 1');
$hojaActiva->getColumnDimension('G')->setWidth(8);
$hojaActiva->setCellValue('G1', 'Cod. G2');
$hojaActiva->getColumnDimension('H')->setWidth(20);
$hojaActiva->setCellValue('H1', 'Grupo 2');
$hojaActiva->getColumnDimension('I')->setWidth(15);
$hojaActiva->setCellValue('I1', 'Cod. Marca');
$hojaActiva->getColumnDimension('J')->setWidth(20);
$hojaActiva->setCellValue('J1', 'Marca');

$hojaActiva->getColumnDimension('K')->setWidth(20);
$hojaActiva->setCellValue('K1', 'Existencias');
$hojaActiva->getColumnDimension('L')->setWidth(20);
$hojaActiva->setCellValue('L1', 'Costo (COP)');

$hojaActiva->getColumnDimension('M')->setWidth(20);
$hojaActiva->setCellValue('M1', 'Precio Lista COP');
$hojaActiva->getColumnDimension('N')->setWidth(20);
$hojaActiva->setCellValue('N1', 'Precio Lista USD');

$hojaActiva->getColumnDimension('O')->setWidth(20);
$hojaActiva->setCellValue('O1', 'Materiales');
$hojaActiva->getColumnDimension('P')->setWidth(20);
$hojaActiva->setCellValue('P1', 'Facturas');

// Check_id 7 y 15
    $hojaActiva->getColumnDimension('Q')->setWidth(20);
    $hojaActiva->setCellValue('Q1', 'Dcto. Max. (%)');
    $hojaActiva->getColumnDimension('R')->setWidth(20);
    $hojaActiva->setCellValue('R1', 'Utilidad Dealer (%)');
    $hojaActiva->getColumnDimension('S')->setWidth(20);
    $hojaActiva->setCellValue('S1', 'Utilidad (%)');

    $hojaActiva->getColumnDimension('T')->setWidth(20);
    $hojaActiva->setCellValue('T1', 'P. Fábrica (USD)');
    $hojaActiva->getColumnDimension('U')->setWidth(20);
    $hojaActiva->setCellValue('U1', 'Fletes (USD)');
    $hojaActiva->getColumnDimension('V')->setWidth(20);
    $hojaActiva->setCellValue('V1', 'Aduana (USD)');
    $hojaActiva->getColumnDimension('W')->setWidth(20);
    $hojaActiva->setCellValue('W1', 'Costo (USD)');
    
    $hojaActiva->getColumnDimension('X')->setWidth(20);
    $hojaActiva->setCellValue('X1', 'Precio Web');
    
    $hojaActiva->getColumnDimension('Y')->setWidth(20);
    $hojaActiva->setCellValue('Y1', 'Precio predetermindo');
    // endif-check_id 

$i=2;
$pdt = array("NO","SI");

$filtro = "";
if(!empty($_REQUEST["grupo1"])){$filtro .=" AND prod_grupo1='".$_REQUEST["grupo1"]."'";}
if(!empty($_REQUEST["grupo2"])){$filtro .=" AND prod_categoria='".$_REQUEST["grupo2"]."'";}
if(!empty($_REQUEST["marca"])){$filtro .=" AND prod_marca='".$_REQUEST["marca"]."'";}
if(!empty($_REQUEST["tipoProductos"])){
	if($_REQUEST["tipoProductos"]==2){$filtro .=" AND prod_descuento_web>0";}
	if($_REQUEST["tipoProductos"]==3){$filtro .=" AND prod_precio_predeterminado=1";}
}

try{
    $consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos INNER JOIN productos_categorias ON catp_id=prod_categoria WHERE prod_id=prod_id $filtro");

} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    exit();
}

while($res=mysqli_fetch_array($consulta)){
	$grupo1 = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_categorias WHERE catp_id='".$res['prod_grupo1']."'"));
	
	$marca = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM marcas WHERE mar_id='".$res['prod_marca']."'"));
	
	$dctoWeb=0;
	if(!empty($res['prod_descuento_web'])){
		$dctoWeb = $res['prod_descuento_web']/100;
	}
	$precioWeb=0;
	if(!empty($res['prod_costo'])){
		$precioWeb = $res['prod_costo'] + ($res['prod_costo']*$dctoWeb);
	}

	$precioListaUSD=0;
	if(!empty($res['prod_utilidad']) && !empty($res['prod_costo_dolar'])){
		$precioListaUSD = productosPrecioListaUSD($res['prod_utilidad'], $res['prod_costo_dolar']);
	}
	
	$datosReg = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"
	SELECT
	(SELECT count(ppmt_id) FROM productos_materiales WHERE ppmt_producto='".$res['prod_id']."'),
	(SELECT count(fpp_id) FROM facturacion_productos WHERE fpp_producto='".$res['prod_id']."')
	"));

    $hojaActiva->setCellValue('A'.$i, ($i-1));
    $hojaActiva->setCellValue('B'.$i, $res['prod_id']);
    $hojaActiva->setCellValue('C'.$i, $res['prod_referencia']);
    $hojaActiva->setCellValue('D'.$i, $res['prod_nombre']);

    $hojaActiva->setCellValue('E'.$i, $grupo1['catp_id']);
    $hojaActiva->setCellValue('F'.$i, $grupo1['catp_nombre']);
    $hojaActiva->setCellValue('G'.$i, $res['catp_id']);
    $hojaActiva->setCellValue('H'.$i, $res['catp_nombre']);
    $hojaActiva->setCellValue('I'.$i, $marca['mar_id']);
    $hojaActiva->setCellValue('J'.$i, $marca['mar_nombre']);

    $hojaActiva->setCellValue('K'.$i, $res['prod_existencias']);
    $hojaActiva->setCellValue('L'.$i, $res['prod_costo']);

    $hojaActiva->setCellValue('M'.$i, $res['prod_precio']);
    $hojaActiva->setCellValue('N'.$i, number_format($precioListaUSD,0,",","."));

    $hojaActiva->setCellValue('O'.$i, $datosReg[0]);
    $hojaActiva->setCellValue('P'.$i, $datosReg[1]);

    // Check_id 7 y 15
        $hojaActiva->setCellValue('Q'.$i, $res['prod_descuento1']);
        $hojaActiva->setCellValue('R'.$i, $res['prod_descuento2']);
        $hojaActiva->setCellValue('S'.$i, $res['prod_utilidad']);
    
        $hojaActiva->setCellValue('T'.$i, $res['prod_precio_fabrica']);
        $hojaActiva->setCellValue('U'.$i, $res['prod_flete']);
        $hojaActiva->setCellValue('V'.$i, $res['prod_aduana']);
        $hojaActiva->setCellValue('W'.$i, $res['prod_costo_dolar']);
        
        $hojaActiva->setCellValue('X'.$i, number_format($precioWeb,0,",","."));
        
        $hojaActiva->setCellValue('Y'.$i, $pdt[$res['prod_precio_predeterminado']]);
    // endif-check_id 

    $i++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="productos_'.date("dmYHis").'.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit();