<?php
include("../sesion.php");

$idPagina = 79;

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    echo json_encode(['success' => false, 'message' => 'ID de cotización no proporcionado']);
    exit;
}

$consultaCliente = $conexionBdPrincipal->query("SELECT * FROM cotizacion 
INNER JOIN clientes ON cli_id=cotiz_cliente
INNER JOIN contactos ON cont_id=cotiz_contacto
WHERE cotiz_id='" . $_GET["id"] . "' AND cotiz_id_empresa='" . $idEmpresa . "'");
$resultadoD = mysqli_fetch_array($consultaCliente, MYSQLI_BOTH);

if (!$resultadoD) {
    echo json_encode(['success' => false, 'message' => 'Cotización no encontrada']);
    exit;
}

require_once RUTA_PROYECTO . '/usuarios/class/Cotizacion.php';

$envio = $resultadoD['cotiz_envio'];
$camposCotizacionDisabled = '';

if ($resultadoD['cotiz_vendida'] == Cotizacion::COTIZACION_VENDIDA) {
    $camposCotizacionDisabled = 'disabled';
}

ob_start();
?>
<div class="row-fluid">
    <div class="span12">
        
        <span id="resp"></span>
        
        <div class="content-widgets light-gray" id="productos">
            <div class="widget-head green">
                <h3>PRODUCTOS</h3>
            </div>
            <div class="widget-container">
                <p></p>
                <table class="table table-striped table-bordered" id="data-table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Orden</th>
                    <th>Producto/Servicio</th>
                    <th>Cant.</th> 
                    <th>Valor Base</th>
                    <th>IVA</th>
                    <th>Dcto.</th>
                    <?php 
                    $colspan = 7;
                    if($resultadoD['cotiz_descuentos_especiales'] == 1){
                        $colspan = 8;
                    ?>
                    <th>Dcto. Especial</th>
                    <?php }?>

                    <th>SUBTOTAL</th>
                </tr>
                </thead>
                <tbody id="tableBody"></tbody>
                <tfoot>
                    <tr style="font-weight: bold; font-size: 16px;">
                        <td style="text-align: right;" colspan="<?=$colspan;?>">SUBTOTAL</td>
                        <td id="subtotal">
                        <span class="moneda-simbolo">
                            <?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?> </span>
                            <span class="valor-numerico">0
                            </span>
                        </td>
                    </tr>
                    <tr style="font-weight: bold; font-size: 16px;">
                        <td style="text-align: right;" colspan="<?=$colspan;?>">DESCUENTO</td>
                        <td id="totalDiscount"><span class="moneda-simbolo">
                            <?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?> </span>
                            <span class="valor-numerico">0
                            </span></td>
                    </tr>
                    <tr style="font-weight: bold; font-size: 16px;">
                        <td style="text-align: right;" colspan="<?=$colspan;?>">IVA</td>
                        <td id="totalIva"><span class="moneda-simbolo">
                            <?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?> </span>
                            <span class="valor-numerico">0
                            </span></td>
                    </tr>
                    <tr style="font-weight: bold; font-size: 16px;">
                        <td style="text-align: right;" colspan="<?=$colspan;?>">ENVÍO</td>
                        <td><?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?><?php if(!empty($envio)) echo number_format($envio,0,",","."); else echo 0;?>
                            </td>
                    </tr>
                    <tr style="font-weight: bold; font-size: 16px;">
                        <td style="text-align: right;" colspan="<?=$colspan;?>">TOTAL NETO</td>
                        <td id="total"><span class="moneda-simbolo">
                            <?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?> </span>
                            <span class="valor-numerico">0
                            </span></td>
                    </tr>
                </tfoot>	
                    
                </table>

                <?php
                if(Modulos::validarRol([394], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){?>

                    <p style="color: black; background-color: #d8ff0038; padding: 15px; font-weight: bold; font-size: 16px;">Esta cotización deja una utilidad aproximada de $<span id="utilidadTotal">0</span>
                <?php }?>
                
                
                    <div class="form-actions">
                        
                        <a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                        <?php
                        if($resultadoD['cotiz_vendida'] != Cotizacion::COTIZACION_VENDIDA){
                        ?>
                        <button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
                        <?php }?>
                        
                            
                        <?php if (Modulos::validarRol([50], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
                        <div class="btn-group">
                            <a href="reportes/formato-cotizacion-1_pdf.php?id=<?=$_GET["id"];?>" class="btn btn-success" target="_blank"><i class="icon-print"></i> Imprimir (Formato 1)</a>
                            <a href="reportes/formato-cotizacion-3_pdf.php?id=<?=$_GET["id"];?>" class="btn btn-warning" target="_blank"><i class="icon-print"></i> Imprimir (Formato 2)</a>
                        </div>
                        <?php } ?>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
<?php
$html = ob_get_clean();
echo json_encode(['success' => true, 'html' => $html]);
?>

