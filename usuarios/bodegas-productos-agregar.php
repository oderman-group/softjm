<?php
include("sesion.php");

$idPagina = 146;
include("includes/verificar-paginas.php");
include("includes/head.php");
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
<script type="text/javascript">
    /*====TAGS INPUT====*/
    $(function() {
        $('#tags_1').tagsInput({
            width: 'auto'
        });
        $('#tags_2').tagsInput({
            width: 'auto',
            onChange: function(elem, elem_tags) {
                var languages = ['php', 'ruby', 'javascript'];
                $('.tag', elem_tags).each(function() {
                    if ($(this).text().search(new RegExp('\\b(' + languages.join('|') + ')\\b')) >= 0) $(this).css('background-color', 'yellow');
                });
            }
        });
    });
    /*====Select Box====*/
    $(function() {
        $(".chzn-select").chosen();
        $(".chzn-select-deselect").chosen({
            allow_single_deselect: true
        });
    });
    /*====Color Picker====*/
    $(function() {
        $('.colorpicker').colorpicker({
            format: 'hex'
        });
        $('.pick-color').colorpicker();
    });
    /*====DATE Time Picker====*/
    $(function() {
        $('#datetimepicker1').datetimepicker({
            language: 'pt-BR'
        });
    });
    $(function() {
        $('#datetimepicker3').datetimepicker({
            pickDate: false
        });
    });
    $(function() {
        $('#datetimepicker4').datetimepicker({
            pickTime: false
        });
    });
    /*DATE RANGE PICKER*/
    $(function() {
        $('#reportrange').daterangepicker({
                ranges: {
                    'Today': ['today', 'today'],
                    'Yesterday': ['yesterday', 'yesterday'],
                    'Last 7 Days': [Date.today().add({
                        days: -6
                    }), 'today'],
                    'Last 30 Days': [Date.today().add({
                        days: -29
                    }), 'today'],
                    'This Month': [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
                    'Last Month': [Date.today().moveToFirstDayOfMonth().add({
                        months: -1
                    }), Date.today().moveToFirstDayOfMonth().add({
                        days: -1
                    })]
                },
                opens: 'left',
                format: 'MM/dd/yyyy',
                separator: ' to ',
                startDate: Date.today().add({
                    days: -29
                }),
                endDate: Date.today(),
                minDate: '01/01/2012',
                maxDate: '12/31/2013',
                locale: {
                    applyLabel: 'Submit',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                },
                showWeekNumbers: true,
                buttonClasses: ['btn-danger']
            },
            function(start, end) {
                $('#reportrange span').html(start.toString('MMMM d, yyyy') + ' - ' + end.toString('MMMM d, yyyy'));
            });
        //Set the initial state of the picker label
        $('#reportrange span').html(Date.today().add({
            days: -29
        }).toString('MMMM d, yyyy') + ' - ' + Date.today().toString('MMMM d, yyyy'));
    });
    $(function() {
        $('#reservation').daterangepicker();
    });
</script>

<script type="text/javascript">
    function repetidosVerificar(enviada) {
        var idUnico = enviada.value;
        var opcion = 3;
        $('#resp').empty().hide().html("esperando...").show(1);
        datos = "idUnico=" + (idUnico) +
            "&opcion=" + (opcion);
        $.ajax({
            type: "POST",
            url: "ajax-clientes-verificar.php",
            data: datos,
            success: function(data) {
                $('#resp').empty().hide().html(data).show(1);
            }
        });

    }
</script>
<?php include("includes/funciones-js.php"); ?>

<?php include("includes/texto-editor.php"); ?>
</head>

<body>
    <div class="layout">
        <?php include("includes/encabezado.php"); ?>

        

        <div class="main-wrapper">
            <div class="container-fluid">
                <div class="row-fluid ">
                    <div class="span12">
                        <div class="primary-head">
                            <h3 class="page-header"><?= $paginaActual['pag_nombre']; ?></h3>
                        </div>
                        <ul class="breadcrumb">
                            <li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
                            <li><a href="bodegas-productos.php">Productos por bodegas</a><span class="divider"><i class="icon-angle-right"></i></span></li>
                            <li class="active"><?= $paginaActual['pag_nombre']; ?></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="content-widgets gray">
                            <div class="widget-head bondi-blue">
                                <h3> <?= $paginaActual['pag_nombre']; ?></h3>
                            </div>
                            <div class="widget-container">
                                <form class="form-horizontal" method="post" action="bd_create/productos-bodegas-guardar.php">

                                    <div class="control-group">
                                        <label class="control-label">Producto (*)</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="producto" required>
                                                <option value=""></option>
                                                <?php
                                                $conOp = $conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_id_empresa='".$idEmpresa."'");
                                                while ($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)) {
                                                ?>
                                                    <option value="<?= $resOp[0]; ?>" <?php if(isset($_GET["prod"])){if($resOp[0] == $_GET["prod"]){echo "selected";}} ?> ><?= $resOp[1]; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Bodega (*)</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="bodega" required>
                                                <option value=""></option>
                                                <?php
                                                $conOp = $conexionBdPrincipal->query("SELECT * FROM bodegas WHERE bod_id_empresa='".$idEmpresa."'", $conexion);
                                                while ($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)) {
                                                ?>
                                                    <option value="<?= $resOp[0]; ?>" <?php if(isset($_GET["bod"])){if($resOp[0] == $_GET["bod"]){echo "selected";}} ?> ><?= $resOp[1]; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Existencia (*)</label>
                                        <div class="controls">
                                            <input type="text" class="span2" name="existencia" value="<?php if(isset($_GET["ex"])){$_GET["ex"];}?>" required>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
                                        <button type="button" class="btn btn-danger">Cancelar</button>
                                    </div>



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