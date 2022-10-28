<?php 
include("sesion.php");
$idPagina = 159;
include("includes/verificar-paginas.php");
include("includes/head.php");

$consulta = $conexionBdPrincipal->query("SELECT * FROM configuracion WHERE conf_id=1");
$resultadoD = mysqli_fetch_array($consulta, MYSQLI_BOTH);
?>
<!-- styles -->

<!--[if IE 7]>
<link rel="stylesheet" href="css/font-awesome-ie7.min.css">
<![endif]-->
<link href="css/chosen.css" rel="stylesheet">


<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
<![endif]-->

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

<?php include("includes/texto-editor.php");?>
</head>

<body>
    <div class="layout">
        <?php include("includes/encabezado.php"); ?>

        

        <div class="main-wrapper">
            <div class="container-fluid">
                <?php include("includes/notificaciones.php"); ?>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="content-widgets gray">
                            <div class="widget-head bondi-blue">
                                <h3> <?= $paginaActual['pag_nombre']; ?></h3>
                            </div>
                            <div class="widget-container">
                                <form class="form-horizontal" method="post" action="bd_update/estructura-mensajes-actualizar.php" enctype="multipart/form-data">
                                    


                                    <div class="control-group">
                                        <label class="control-label">Mensaje al enviar cotización</label>
                                        <div class="controls">
                                            <textarea rows="7" cols="80" style="width: 80%" class="tinymce-simple" name="eCotizacion"><?= $resultadoD['conf_emsj_cotizacion']; ?></textarea>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Mensaje al enviar portafolio</label>
                                        <div class="controls">
                                            <textarea rows="7" cols="80" style="width: 80%" class="tinymce-simple" name="ePortafolio"><?= $resultadoD['conf_emsj_portafolios']; ?></textarea>
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