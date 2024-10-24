<?php 
include("sesion.php");
$idPagina = 157;
include("includes/verificar-paginas.php");
include("includes/head.php");

$validarGet = validarVariableGet($_GET['id']);

if($validarGet){
    $consulta = $conexionBdPrincipal->query("SELECT * FROM metricas WHERE met_id_empresa = '".$_SESSION["dataAdicional"]["id_empresa"]."'");
    $numRegistros = $consulta->num_rows;
    if($numRegistros == 0){
        echo "No hay información para mostrar.";
        exit();
    }
    $resultadoD = mysqli_fetch_array($consulta, MYSQLI_BOTH);
}else{
    echo "Usted no está accediendo de manera correcta";
    exit();
}
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
                                <form class="form-horizontal" method="post" action="bd_update/metricas-actualizar.php" enctype="multipart/form-data">
                                    
                                    <input type="hidden" name="id" value="<?=$_GET['id'];?>">

                                    <div class="control-group">
                                        <label class="control-label">Meta del mes
                                            <button class="tooltipp">Objetivos a cumplir por la empresa.</button>
                                            <i class="fa-solid fa-circle-question"></i>
                                        </label>
                                        <div class="controls">
                                            <input type="text" class="span4" name="metames" value="<?= $resultadoD['met_meta_venta_mes']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Días hábiles del mes</label>
                                        <div class="controls">
                                            <input type="text" class="span4" name="dhabiles" value="<?= $resultadoD['met_dias_habiles']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Bonificación (Mejor asesor)
                                                <button class="tooltipp">Incentivos al mejor asesor del mes.</button>
                                                <i class="fa-solid fa-circle-question"></i>
                                        </label>
                                        <div class="controls">
                                            <input type="text" class="span4" name="bonificacion" value="<?= $resultadoD['met_bonificacion_mes']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Punto de equilibrio de la empresa
                                        <button class="tooltipp">Nivel de actividad en el que los ingresos totales son iguales a los costos totales.</button>
                                                <i class="fa-solid fa-circle-question"></i>
                                        </label>
                                        <div class="controls">
                                            <input type="text" class="span4" name="pequilibrio" value="<?= $resultadoD['met_punto_equilibrio']; ?>" required>
                                            <span style="color:#609;">Cuánto necesita vender la empresa mensual para cubrir costos.</span>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Bonificación (Cumplimiento de meta)
                                            <button class="tooltipp">Compensación adicional que se otorga a los empleados cuando alcanzan o superan objetivos específicos establecidos por la empresa..</button>
                                            <i class="fa-solid fa-circle-question"></i>
                                        </label>
                                        <div class="controls">
                                            <input type="text" class="span4" name="bonoMeta" value="<?= $resultadoD['met_bono_meta']; ?>" required>
                                        </div>
                                    </div>





                                   

                                    <div class="form-actions">
                                    <?php if(Modulos::validarRol([266], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){
                                    ?>
                                        <button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
                                        <?php } ?>
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