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

$consulta = $conexionBdPrincipal->query("SELECT * FROM cliente_seguimiento
INNER JOIN clientes ON cli_id=cseg_cliente
INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
INNER JOIN clientes_tikets ON tik_id=cseg_tiket AND tik_id_cotizacion = ".$resultadoD['cotiz_id']);
$no = 1;

ob_start();
?>
<div class="row-fluid">
    <div class="span12">
        <?php
        $haySeguimientos = false;
        while ($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
            $haySeguimientos = true;
            $rutaFoto = "files/fotos/".$res['usr_foto'];

            if (!empty($res['usr_foto']) && file_exists($rutaFoto)) {
                $foto = $rutaFoto;
            } else {
                $rutaFoto = "images/item-pic.png";
            }
        ?>
            <div class="media">
                <a href="#" class="pull-left media-thumb">
                    <img src="<?=$rutaFoto;?>" width="34" height="34" alt="user">
                </a>
                <div class="media-body ">
                    <h4 class="media-heading"><?=$res['cseg_fecha_reporte'];?> - <?=$res['usr_nombre'];?></h4>
                    <p><?=$res['cseg_observacion'];?></p>
                </div>
            </div>
        <?php }?>
        
        <?php if (!$haySeguimientos) { ?>
            <div class="alert alert-info">
                <i class="icon-info-sign"></i> No hay seguimientos registrados para esta cotización.
            </div>
        <?php } ?>
    </div>
</div>
<?php
$html = ob_get_clean();
echo json_encode(['success' => true, 'html' => $html]);
?>

