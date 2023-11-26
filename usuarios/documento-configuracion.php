<?php 
include("sesion.php");
$idPagina = 323;

include("includes/verificar-paginas.php");
include("includes/head.php");

$consulta = $conexionBdAdmin->query("SELECT * FROM documentos_configuracion WHERE  dconf_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."' AND dconf_id_documento='".$_GET["id"]."'");
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
        <?php include("includes/encabezado.php");?>

        

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
                                <form class="form-horizontal" method="post" action="bd_update/configuracion-documento-actualizar.php" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $_GET['id'] ?>">

                                    <div class="control-group">
                                        <label class="control-label">Color Documento</label>
                                        <div class="controls">
                                            <input type="color" class="span4" name="estilo" value="<?= $resultadoD['dconf_estilo'];?>">
                                            
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Estilo de letra</label>
                                        <div class="controls">
                                        <select style="width: 200px;" id="estiloLetra" name="estilo_letra" require>
                                        <?php if($resultadoD['dconf_estilo_letra']!="") { ?>
                                        <option value="<?= $resultadoD['dconf_estilo_letra']; ?>"><?= $resultadoD['dconf_estilo_letra']; ?></option>
                                        <?php }else{ ?>
                                        <option value="">Seleccione un estilo de letra</option>
                                        <?php } ?>
                                        <option value="Arial">Arial</option>
                                        <option value="Bahnschrift">Bahnschrift</option>
                                        <option value="Calibri">Calibri</option>
                                        <option value="Cambria">Cambria</option>
                                        <option value="Comic">Comic Sans MS</option>
                                        <option value="Courier_New">Courier New</option>
                                        <option value="Daytona">Daytona</option>
                                        <option value="Euphemia">Euphemia</option>
                                        <option value="Georgia">Georgia</option>
                                        <option value="Grandview">Grandview</option>
                                        <option value="Kalinga">Kalinga</option>
                                        <option value="Mangal">Mangal</option>
                                        <option value="Wingdings">Wingdings</option>
                                        <option value="Vrinda">Vrinda</option>
                                        </select> 
                                           
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Tamaño de letra</label>
                                        <div class="controls">
                                        <select style="width: 200px;" id="tamanLetra" name="tamanLetra">
                                        <?php if($resultadoD['dconf_estilo_letra']!="") { ?>
                                            <option value="<?= $resultadoD['dconf_tamano_letra']; ?>"><?= $resultadoD['dconf_tamano_letra']; ?></option>
                                        <?php }else{ ?>
                                        <option value="">Seleccione un tamaño de letra</option>
                                        <?php } ?>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="14">14</option>
                                        <option value="16">16</option>
                                        <option value="18">18</option>
                                        <option value="22">22</option>
                                        <option value="24">24</option>
                                        <option value="26">26</option>
                                        <option value="28">28</option>
                                        <option value="36">36</option>
                                        <option value="48">48</option>
                                        <option value="72">72</option>
                                        </select>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Ubicación del logo</label>
                                        <div class="controls">
                                        <select style="width: 200px;" id="ubicacion" name="ubicacion">
                                        <?php if($resultadoD['dconf_estilo_letra']!="") { ?>
                                            <option value="<?= $resultadoD['dconf_ubicacion_logo']; ?>"><?= $resultadoD['dconf_ubicacion_logo']; ?></option>
                                        <?php }else{ ?>
                                            <option value="">Seleccione una Ubicación</option>
                                        <?php } ?>
                                        <option value="Derecha">Derecha</option>
                                        <option value="Izquierda">Izquierda</option>
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
                                    </div>
                                    <?php if ($resultadoD['dconf_logo']!="") { ?>
                                    <img src="files/<?= $resultadoD['dconf_logo']; ?>" width="100">
                                    <?php } else{

                                    } ?>
                                   

                                        <div class="form-actions">
                                        <button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
                                        <a href="documentos.php" class="btn btn-danger">Cancelar</a>
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