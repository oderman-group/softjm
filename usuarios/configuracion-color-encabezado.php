<?php 
include("sesion.php");
$idPagina = 190;

include("includes/verificar-paginas.php");
include("includes/head.php");

$consulta = $conexionBdAdmin->query("SELECT * FROM encabezado_color_empresa WHERE cxe_id_empresa='".$configuracion['conf_id_empresa']."'");
$resultadoD = mysqli_fetch_array($consulta, MYSQLI_BOTH);
?>
<!-- styles -->

<link href="css/chosen.css" rel="stylesheet">

<!--============ javascript ===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-fileupload.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.tagsinput.js"></script>
<script src="js/chosen.jquery.js"></script>
<script src="js/bootstrap-colorpicker.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script src="js/date.js"></script>
<script src="js/daterangepicker.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>

<?php 
//Son todas las funciones javascript para que los campos del formulario funcionen bien.
include("includes/js-formularios.php");
?>

</head>

<body>
    <div class="layout">
        <?php include("includes/encabezado.php"); ?>

        

        <div class="main-wrapper">
            <div class="container-fluid">
                <?php include("includes/notificaciones.php"); ?>
                <div class="row-fluid">
                    <div class="span8" >
                        <div class="content-widgets gray">
                            <div class="widget-head bondi-blue">
                                <h3> <?= $paginaActual['pag_nombre']; ?></h3>
                            </div>
                            <div class="widget-container">
                                <form class="form-horizontal" method="post" action="bd_update/actualizar-color-encabezado.php" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?=$configuracion['conf_id_empresa'];?>">

                                    <div class="control-group">
                                        <label class="control-label">Color Fondo</label>
                                        <div class="controls">
                                            <input type="color" class="span4" name="colFondo" value="<?= $resultadoD['cxe_fondo'];?>">
                                            <span style="color:tomato;">Este campo cambia el color del fondo de todo el menu.</span>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Color Texto Items</label>
                                        <div class="controls">
                                            <input type="color" class="span4" name="colText" value="<?= $resultadoD['cxe_text_items'];?>">
                                            <span style="color:tomato;">Este campo cambia el color de los titulo de todo el menu.</span>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Color Texto Items Hover</label>
                                        <div class="controls">
                                            <input type="color" class="span4" name="colTextHover" value="<?= $resultadoD['cxe_text_items_hover'];?>">
                                            <span style="color:tomato;">Este campo cambia el color del titulo del menu cuando se pasa el mause por encima.</span>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Color Fondo Items Activo</label>
                                        <div class="controls">
                                            <input type="color" class="span4" name="colFonActivo" value="<?= $resultadoD['cxe_fondo_items_activo'];?>">
                                            <span style="color:tomato;">Este campo cambia el fondo del item seleccionado en el menu.</span>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Color Texto Items Activo</label>
                                        <div class="controls">
                                            <input type="color" class="span4" name="colTextActivo" value="<?= $resultadoD['cxe_text_items_activo'];?>">
                                            <span style="color:tomato;">Este campo cambia el color del titulo del item seleccionado en el menu.</span>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Color Borde del Sub-Menu</label>
                                        <div class="controls">
                                            <input type="color" class="span4" name="colBorSubmenu" value="<?= $resultadoD['cxe_border_submenu'];?>">
                                            <span style="color:tomato;">Este campo cambia el color del borde del sub-menu.</span>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Color Fondo Sub-Menu</label>
                                        <div class="controls">
                                            <input type="color" class="span4" name="colFonSubmenu" value="<?= $resultadoD['cxe_fondo_submenu'];?>">
                                            <span style="color:tomato;">Este campo cambia el color del fondo del sub-menu.</span>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Color Fondo Sub-Menu Hover</label>
                                        <div class="controls">
                                            <input type="color" class="span4" name="colFonSubmenuHover" value="<?= $resultadoD['cxe_fondo_submenu_hover'];?>">
                                            <span style="color:tomato;">Este campo cambia el color de fondo del item del sub-menu cuando se pasa el mause por encima.</span>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
                                        <button type="button" class="btn btn-danger">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php include("includes/pie.php"); ?>
    </div>
</body>

</html>