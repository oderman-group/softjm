<?php 
include("sesion.php");
$idPagina = 323;

include("includes/verificar-paginas.php");
include("includes/head.php");


$consulta = $conexionBdAdmin->query("SELECT * FROM documentos_configuracion  
WHERE dconf_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."' AND dconf_id_documento='".$_GET['id']."'");

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
                    <div class="span12">
                        <div class="content-widgets gray">
                            <div class="widget-head bondi-blue">
                                <h3> <?= $paginaActual['pag_nombre']; ?></h3>
                            </div>
                            <fieldset class="default">
                                        <legend>Información del documento</legend>

                            <div class="widget-container">
                                <form class="form-horizontal" method="post" action="bd_update/configuracion-documento-actualizar.php" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
                                
                                    <div class="control-group">
                                        <label class="control-label">Estilo del documento</label>
                                        <div class="controls">
                                            <input type="text" class="span4" name="estilo" value="<?= $resultadoD['dconf_estilo']; ?>" required>

                                            
                                        </div>
                                        

                                    </div><div class="control-group">
                                        <label class="control-label">Estilo de letra</label>
                                        <div class="controls">
                                            <input type="text" class="span4" name="estiloLetra" value="<?= $resultadoD['dconf_estilo_letra']; ?>" required>

                                            
                                        </div>
                                        

                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">tamaño de letra</label>
                                        <div class="controls">
                                            <input type="text" class="span4" name="tamanoLetra" value="<?= $resultadoD['dconf_tamano_letra']; ?>" required>

                                            
                                        </div>
                                        

                                    </div>
                                    
                                    <div class="control-group">
                                        <label class="control-label">Ubicacion del logo</label>
                                        <div class="controls">
                                        <select id="ubicacion" name="ubicacion">
                                        <option value="ubicacionLogo"><?= $resultadoD['dconf_ubicacion_logo']; ?></option>
                                        <option value="derecha">Derecha</option>
                                        <option value="izquierda">Izquierda</option>
                                        </select>  
                                     
                                            
                                        </div>
                                        

                                    </div>

                                    <div class="control-group">
                                            <label class="control-label">Logo de la empresa</label>
                                            <div class="controls">
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="input-append">
                                                        <div class="uneditable-input span3">
                                                            <i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
                                                        </div>
                                                        <span class="btn btn-file"><span class="fileupload-new">Seleccionar archivo</span><span class="fileupload-exists">Cambiar</span>
                                                            <input type="file" name="logo" />
                                                        </span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remover</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <img src="files/<?=$resultadoD['dconf_logo'];?>" width="100">
                                            
                                          
                                                                                     
                                        </div>

                                      



                                        </fieldset>

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