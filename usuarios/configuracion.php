<?php 
include("sesion.php");
$idPagina = 42;

include("includes/verificar-paginas.php");
include("includes/head.php");

$consulta = $conexionBdPrincipal->query("SELECT * FROM configuracion WHERE conf_id_empresa = '".$_SESSION["dataAdicional"]["id_empresa"]."'");
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
                            <div class="widget-container">
                                <form class="form-horizontal" method="post" action="bd_update/configuracion-actualizar.php" enctype="multipart/form-data">

                                    <div class="control-group">
                                        <label class="control-label">Año de inicio</label>
                                        <div class="controls">
                                            <input type="text" class="span4" name="agnoInicio" value="<?= $resultadoD['conf_agno_inicio']; ?>" required>
                                            <span style="color:#609;">Desde cuando empezó a usar este CRM</span>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Dolar compra</label>
                                        <div class="controls">
                                            <input type="text" class="span4" name="dolarCompra" value="<?= $resultadoD['conf_trm_compra']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Dolar venta</label>
                                        <div class="controls">
                                            <input type="text" class="span4" name="dolarVenta" value="<?= $resultadoD['conf_trm_venta']; ?>" required>

                                            <span style="color:tomato; font-weight: bold;">Este es el valor por el cual se multiplicará el dolar para cambiar a pesos.</span>
                                        </div>

                                    </div>





                                    <fieldset class="default">
                                        <legend>Información de la empresa</legend>

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
                                            <img src="files/<?= $resultadoD['conf_logo']; ?>" width="100">
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Ancho del Logo en Login (PX)</label>
                                            <div class="controls">
                                                <input type="text" class="span2" name="anchoLogo" value="<?= $resultadoD['conf_ancho_logo']; ?>">
                                                <span style="color:#F00;">Coloque sólo el número.</span>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Alto del Logo en Login (PX)</label>
                                            <div class="controls">
                                                <input type="number" class="span2" name="altoLogo" value="<?= $resultadoD['conf_alto_logo']; ?>">
                                                <span style="color:#F00;">Coloque sólo el número.</span>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">NIT de la empresa</label>
                                            <div class="controls">
                                                <input type="number" class="span4" name="nit" value="<?= $resultadoD['conf_nit']; ?>">
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Nombre de la empresa</label>
                                            <div class="controls">
                                                <input type="text" class="span4" name="nombre" value="<?= $resultadoD['conf_empresa']; ?>" required>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Email de la empresa</label>
                                            <div class="controls">
                                                <input type="email" class="span4" name="email" value="<?= $resultadoD['conf_email']; ?>" required>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Clave del Email</label>
                                            <div class="controls">
                                                <input type="text" class="span4" name="claveEmail" value="<?= $resultadoD['conf_clave_correo']; ?>" required>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Teléfono de la empresa</label>
                                            <div class="controls">
                                                <input type="text" class="span4" name="telefono" value="<?= $resultadoD['conf_telefono']; ?>">
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Sitio Web de la empresa</label>
                                            <div class="controls">
                                                <input type="url" class="span4" name="web" value="<?= $resultadoD['conf_web']; ?>">
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">URL del CRM</label>
                                            <div class="controls">
                                                <input type="url" class="span4" name="urlEncuestas" value="<?= $resultadoD['conf_url_encuestas']; ?>" required>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <!-- oculto -->
                                    <fieldset class="default" style="display: none;">
                                        <legend>Configuración de los Email</legend>

                                        <div class="form-actions">
                                            <a href="reportes/formato-estilo-boletin.php" class="btn btn-primary" target="_blank"><i class="icon-picture"></i> Vista del diseño</a>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Fondo del Email</label>
                                            <div class="controls">
                                                <div class="input-append color pick-color" data-color="<?= $resultadoD['conf_fondo_boletin']; ?>" data-color-format="hex">
                                                    <input type="text" name="fondoBoletin" value="<?= $resultadoD['conf_fondo_boletin']; ?>">
                                                    <span class="add-on"><i style="background-color:<?= $resultadoD['conf_fondo_boletin']; ?>;"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Fondo del Mensaje</label>
                                            <div class="controls">
                                                <div class="input-append color pick-color" data-color="<?= $resultadoD['conf_fondo_mensaje']; ?>" data-color-format="hex">
                                                    <input type="text" name="fondoMensaje" value="<?= $resultadoD['conf_fondo_mensaje']; ?>">
                                                    <span class="add-on"><i style="background-color:<?= $resultadoD['conf_fondo_mensaje']; ?>;"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Color de la letra</label>
                                            <div class="controls">
                                                <div class="input-append color pick-color" data-color="<?= $resultadoD['conf_color_letra']; ?>" data-color-format="hex">
                                                    <input type="text" name="colorLetra" value="<?= $resultadoD['conf_color_letra']; ?>">
                                                    <span class="add-on"><i style="background-color:<?= $resultadoD['conf_color_letra']; ?>;"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Color de los links</label>
                                            <div class="controls">
                                                <div class="input-append color pick-color" data-color="<?= $resultadoD['conf_color_link']; ?>" data-color-format="hex">
                                                    <input type="text" name="colorLink" value="<?= $resultadoD['conf_color_link']; ?>">
                                                    <span class="add-on"><i style="background-color:<?= $resultadoD['conf_color_link']; ?>;"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Nombre del Botón (Llamado a la acción)</label>
                                            <div class="controls">
                                                <input type="text" class="span6" name="botonNombre" value="<?= $resultadoD['conf_nombre_boton']; ?>">
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">URL del Botón (URL donde irá)</label>
                                            <div class="controls">
                                                <input type="url" class="span8" name="botonUrl" value="<?= $resultadoD['conf_url_boton']; ?>">
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Mensaje final corto (Pie del email)</label>
                                            <div class="controls">
                                                <input type="text" class="span8" name="mensajePie" value="<?= $resultadoD['conf_mensaje_pie']; ?>">
                                            </div>
                                        </div>

                                    </fieldset>



                                    <fieldset class="default">
                                        <legend>Configuración general</legend>

                                        <div class="control-group">
                                            <label class="control-label">Asociar proveedores en cotización</label>
                                            <div class="controls">
                                                <select name="proveedorCotizacion">
                                                    <option value="1" <?php if($resultadoD['conf_proveedor_cotizacion'] == 1){echo "selected";}?>>SI</option>
                                                    <option value="0" <?php if($resultadoD['conf_proveedor_cotizacion'] == '0'){echo "selected";}?>>NO</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Encabezado de la cotización (1920 X 343)</label>
                                            <div class="controls">
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="input-append">
                                                        <div class="uneditable-input span3">
                                                            <i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
                                                        </div>
                                                        <span class="btn btn-file"><span class="fileupload-new">Seleccionar archivo</span><span class="fileupload-exists">Cambiar</span>
                                                            <input type="file" name="encabezadoCotizacion" />
                                                        </span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remover</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <img src="images/<?= $resultadoD['conf_encabezado_cotizacion']; ?>" width="200">
                                        </div>


                                        <div class="control-group">
                                            <label class="control-label">Encabezado 2 de la cotización (1920 X 224)</label>
                                            <div class="controls">
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="input-append">
                                                        <div class="uneditable-input span3">
                                                            <i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
                                                        </div>
                                                        <span class="btn btn-file"><span class="fileupload-new">Seleccionar archivo</span><span class="fileupload-exists">Cambiar</span>
                                                            <input type="file" name="encabezadoCotizacion2" />
                                                        </span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remover</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <img src="images/<?= $resultadoD['conf_encabezado2_cotizacion']; ?>" width="200">
                                        </div>


                                        <div class="control-group">
                                            <label class="control-label">Pie de la cotización (1920 X 533)</label>
                                            <div class="controls">
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="input-append">
                                                        <div class="uneditable-input span3">
                                                            <i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
                                                        </div>
                                                        <span class="btn btn-file"><span class="fileupload-new">Seleccionar archivo</span><span class="fileupload-exists">Cambiar</span>
                                                            <input type="file" name="pieCotizacion" />
                                                        </span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remover</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <img src="images/<?= $resultadoD['conf_pie_cotizacion']; ?>" width="200">
                                        </div>

                                        <hr>

                                        <div class="control-group">
                                            <label class="control-label">Encabezado del pedido (1920 X 343)</label>
                                            <div class="controls">
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="input-append">
                                                        <div class="uneditable-input span3">
                                                            <i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
                                                        </div>
                                                        <span class="btn btn-file"><span class="fileupload-new">Seleccionar archivo</span><span class="fileupload-exists">Cambiar</span>
                                                            <input type="file" name="encabezadoPedido" />
                                                        </span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remover</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <img src="images/<?= $resultadoD['conf_encabezado_pedido']; ?>" width="200">
                                        </div>


                                        <div class="control-group">
                                            <label class="control-label">Encabezado 2 del pedido (1920 X 224)</label>
                                            <div class="controls">
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="input-append">
                                                        <div class="uneditable-input span3">
                                                            <i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
                                                        </div>
                                                        <span class="btn btn-file"><span class="fileupload-new">Seleccionar archivo</span><span class="fileupload-exists">Cambiar</span>
                                                            <input type="file" name="encabezadoPedido2" />
                                                        </span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remover</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <img src="images/<?= $resultadoD['conf_encabezado2_pedido']; ?>" width="200">
                                        </div>


                                        <div class="control-group">
                                            <label class="control-label">Pie del pedido (1920 X 533)</label>
                                            <div class="controls">
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="input-append">
                                                        <div class="uneditable-input span3">
                                                            <i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
                                                        </div>
                                                        <span class="btn btn-file"><span class="fileupload-new">Seleccionar archivo</span><span class="fileupload-exists">Cambiar</span>
                                                            <input type="file" name="piePedido" />
                                                        </span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remover</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <img src="images/<?= $resultadoD['conf_pie_pedido']; ?>" width="200">
                                        </div>


                                        <div class="control-group">
                                            <label class="control-label">Paginación</label>
                                            <div class="controls">
                                                <input type="text" class="span2" name="paginacion" value="<?= $resultadoD['conf_paginacion']; ?>">
                                                <span style="color:#F00;">Son los registros que mostrará por cada página</span>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Porcentaje para clientes (Compras)</label>
                                            <div class="controls">
                                                <input type="text" class="span2" name="porcentajeClientes" value="<?= $resultadoD['conf_porcentaje_clientes']; ?>">
                                                <span style="color:#F00;">Lo que se le dará al cliente por cada compra</span>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Porcentaje comisión ventas</label>
                                            <div class="controls">
                                                <input type="text" class="span2" name="comisionVendedores" value="<?= $resultadoD['conf_comision_vendedores']; ?>">
                                                <span style="color:#F00;">Lo que se le dará al vendedor por cada venta</span>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Saldo para enviar correo</label>
                                            <div class="controls">
                                                <input type="text" class="span2" name="correoPuntos" value="<?= $resultadoD['conf_coreo_puntos']; ?>">
                                                <span style="color:#F00;">El sistema enviará un correo automático cuando los clientes acumulen este saldo.</span>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Próximo vencimiento</label>
                                            <div class="controls">
                                                <input type="date" class="span2" name="fechaVencimientoSaldo" value="<?= $resultadoD['conf_vencimiento_puntos']; ?>">
                                                <span style="color:#F00;">La fecha límite próxima para que los clientes rediman su saldo acumulado.</span>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="control-group">
                                            <label class="control-label">Permitir a clientes imprimir certificados</label>
                                            <div class="controls">
                                                <select name="clientesImprimir">
                                                    <option value="1" <?php if($resultadoD['conf_cliente_imprimir_certificado'] == 1){echo "selected";}?>>SI</option>
                                                    <option value="0" <?php if($resultadoD['conf_cliente_imprimir_certificado'] == '0'){echo "selected";}?>>NO</option>
                                                </select>
                                            </div>
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