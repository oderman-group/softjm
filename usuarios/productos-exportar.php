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
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

$idEmpresaExport = (int) ($_SESSION['dataAdicional']['id_empresa'] ?? 0);
include RUTA_PROYECTO . '/usuarios/includes/productos-export-filtros.php';
$filtro = $filtroProductosExport;

$excel= new Spreadsheet();
$hojaActiva= $excel->getActiveSheet();
$hojaActiva->setTitle("Productos");

$hojaActiva->getStyle('A1:AB1')->getFont()->setBold(true)->getColor()->setARGB(Color::COLOR_DARKBLUE);
$hojaActiva->getStyle('A1:AB1')->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_CENTER);

// Sin columna ID: la clave de negocio es el Código (prod_referencia).
$hojaActiva->getColumnDimension('A')->setWidth(5);
$hojaActiva->setCellValue('A1', 'Nº');
$hojaActiva->getColumnDimension('B')->setWidth(18);
$hojaActiva->setCellValue('B1', 'Código *');
$hojaActiva->getColumnDimension('C')->setWidth(24);
$hojaActiva->setCellValue('C1', 'Nombre *');

$hojaActiva->getColumnDimension('D')->setWidth(12);
$hojaActiva->setCellValue('D1', 'Cod. Línea');
$hojaActiva->getColumnDimension('E')->setWidth(20);
$hojaActiva->setCellValue('E1', 'Línea');
$hojaActiva->getColumnDimension('F')->setWidth(14);
$hojaActiva->setCellValue('F1', 'Cod. Sublínea');
$hojaActiva->getColumnDimension('G')->setWidth(20);
$hojaActiva->setCellValue('G1', 'Sublínea');
$hojaActiva->getColumnDimension('H')->setWidth(12);
$hojaActiva->setCellValue('H1', 'Cod. Grupo');
$hojaActiva->getColumnDimension('I')->setWidth(20);
$hojaActiva->setCellValue('I1', 'Grupo');

$hojaActiva->getColumnDimension('J')->setWidth(20);
$hojaActiva->setCellValue('J1', 'Existencias'.PHP_EOL.'(solo lectura)');

$hojaActiva->getColumnDimension('K')->setWidth(20);
$hojaActiva->setCellValue('K1', 'Costo (USD)');

$hojaActiva->getColumnDimension('L')->setWidth(20);
$hojaActiva->setCellValue('L1', 'Precio Lista USD '.PHP_EOL.'(solo lectura)');

$hojaActiva->getColumnDimension('M')->setWidth(20);
$hojaActiva->setCellValue('M1', 'Costo (COP)');

$hojaActiva->getColumnDimension('N')->setWidth(20);
$hojaActiva->setCellValue('N1', 'Utilidad (%)');

$hojaActiva->getColumnDimension('O')->setWidth(20);
$hojaActiva->setCellValue('O1', 'Dcto. Max. (%)');

$hojaActiva->getColumnDimension('P')->setWidth(20);
$hojaActiva->setCellValue('P1', 'Precio Lista COP *');

$hojaActiva->getColumnDimension('Q')->setWidth(20);
$hojaActiva->setCellValue('Q1', 'Utilidad Dealer (%)');

$hojaActiva->getColumnDimension('R')->setWidth(20);
$hojaActiva->setCellValue('R1', 'Precio Dealer '.PHP_EOL.'(solo lectura)');

$hojaActiva->getColumnDimension('S')->setWidth(20);
$hojaActiva->setCellValue('S1', 'Descuento Web (%)');

$hojaActiva->getColumnDimension('T')->setWidth(20);
$hojaActiva->setCellValue('T1', 'Precio Web '.PHP_EOL.'(solo lectura)');

$hojaActiva->getColumnDimension('U')->setWidth(20);
$hojaActiva->setCellValue('U1', 'Materiales');

$hojaActiva->getColumnDimension('V')->setWidth(20);
$hojaActiva->setCellValue('V1', 'Facturas');

$hojaActiva->getColumnDimension('W')->setWidth(20);
$hojaActiva->setCellValue('W1', 'P. Fábrica (USD)');

$hojaActiva->getColumnDimension('X')->setWidth(20);
$hojaActiva->setCellValue('X1', 'Fletes (USD)');

$hojaActiva->getColumnDimension('Y')->setWidth(20);
$hojaActiva->setCellValue('Y1', 'Aduana (USD)');

$hojaActiva->getColumnDimension('Z')->setWidth(20);
$hojaActiva->setCellValue('Z1', 'Precio predetermindo');

$hojaActiva->getColumnDimension('AA')->setWidth(16);
$hojaActiva->setCellValue('AA1', 'Integrar Ofima *'.PHP_EOL.'(SI/NO)');

$hojaActiva->getColumnDimension('AB')->setWidth(14);
$hojaActiva->setCellValue('AB1', 'Habilitado *'.PHP_EOL.'(SI/NO)');

// Encabezados obligatorios en rojo
foreach (['B1', 'C1', 'P1', 'AA1', 'AB1'] as $celdaObligatoria) {
	$hojaActiva->getStyle($celdaObligatoria)->getFont()->setBold(true)->getColor()->setARGB(Color::COLOR_RED);
}

$hojaActiva->getRowDimension(1)->setRowHeight(36);
$hojaActiva->freezePane('A2');

$i=2;
$pdt = array("NO","SI");

try {
    $consulta = mysqli_query($conexionBdPrincipal,"SELECT *, " . Producto::sqlSumaExistenciasHabilitadas('productos.prod_id') . " AS prod_existencias FROM productos 
    LEFT JOIN productos_categorias ON catp_id=prod_categoria 
    LEFT JOIN (
                SELECT catp_id AS G2ID, catp_nombre AS G2NAME FROM productos_categorias
    ) grupo1 ON G2ID=prod_grupo1
    LEFT JOIN marcas ON mar_id=prod_marca
    WHERE prod_id=prod_id $filtro
    ");

} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    exit();
}

while ($res = mysqli_fetch_array($consulta)) {
	
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

    $dctoDealer = 0;
	if(!empty($res['prod_descuento2'])){
		$dctoDealer = $res['prod_descuento2']/100;
	}

    $precioDealer = 0;
	if(!empty($res['prod_costo'])){
		$precioDealer = $res['prod_costo'] + ($res['prod_costo'] * $dctoDealer);
	}

	$datosReg = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"
	SELECT
	(SELECT count(ppmt_id) FROM productos_materiales WHERE ppmt_producto='".$res['prod_id']."'),
	(SELECT count(fpp_id) FROM facturacion_productos WHERE fpp_producto='".$res['prod_id']."')
	"));

	$habilitado = ((int) ($res['prod_habilitado'] ?? 1) === 1) ? 1 : 0;
	$integrarOfima = ((int) ($res['prod_integrado_ofima'] ?? 0) === 1) ? 1 : 0;

    $hojaActiva->setCellValue('A'.$i, ($i-1));
    $hojaActiva->setCellValue('B'.$i, $res['prod_referencia']);
    $hojaActiva->setCellValue('C'.$i, $res['prod_nombre']);

    $hojaActiva->setCellValue('D'.$i, $res['G2ID']);
    $hojaActiva->setCellValue('E'.$i, $res['G2NAME']);
    $hojaActiva->setCellValue('F'.$i, $res['catp_id']);
    $hojaActiva->setCellValue('G'.$i, $res['catp_nombre']);
    $hojaActiva->setCellValue('H'.$i, $res['mar_id']);
    $hojaActiva->setCellValue('I'.$i, $res['mar_nombre']);
    $hojaActiva->setCellValue('J'.$i, $res['prod_existencias']);

    $hojaActiva->setCellValue('K'.$i, $res['prod_costo_dolar']);
    $hojaActiva->setCellValue('L'.$i, number_format($precioListaUSD,0,",","."));
    $hojaActiva->setCellValue('M'.$i, $res['prod_costo']);
    $hojaActiva->setCellValue('N'.$i, $res['prod_utilidad']);
    $hojaActiva->setCellValue('O'.$i, $res['prod_descuento1']);
    $hojaActiva->setCellValue('P'.$i, $res['prod_precio']);
    $hojaActiva->setCellValue('Q'.$i, $res['prod_descuento2']);
    $hojaActiva->setCellValue('R'.$i, $precioDealer);
    $hojaActiva->setCellValue('S'.$i, $res['prod_descuento_web']);
    $hojaActiva->setCellValue('T'.$i, $precioWeb);

    $hojaActiva->setCellValue('U'.$i, $datosReg[0]);
    $hojaActiva->setCellValue('V'.$i, $datosReg[1]);
    $hojaActiva->setCellValue('W'.$i, $res['prod_precio_fabrica']);
    $hojaActiva->setCellValue('X'.$i, $res['prod_flete']);
    $hojaActiva->setCellValue('Y'.$i, $res['prod_aduana']);

    $hojaActiva->setCellValue('Z'.$i, $pdt[(int) ($res['prod_precio_predeterminado'] ?? 0)]);
    $hojaActiva->setCellValue('AA'.$i, $pdt[$integrarOfima]);
    $hojaActiva->setCellValue('AB'.$i, $pdt[$habilitado]);

	if ($habilitado !== 1) {
		$hojaActiva->getStyle('A' . $i . ':AB' . $i)->getFill()
			->setFillType(Fill::FILL_SOLID)
			->getStartColor()->setARGB('FFD1D5DB');
		$hojaActiva->getStyle('A' . $i . ':AB' . $i)->getFont()->getColor()->setARGB('FF6B7280');
	}

    $i++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="productos_'.date("dmYHis").'.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit();
