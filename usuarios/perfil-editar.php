<?php include("sesion.php"); ?>
<?php
$idPagina = 19;
$tituloPagina = "Editar Perfil";
?>
<?php include("verificar-paginas.php"); ?>
<?php include("head.php"); ?>
<?php

if(isset($_SERVER['HTTP_REFERER'])) {
	$conexionBdPrincipal->query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('" . $_SESSION["id"] . "', '" . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "', '" . $idPagina . "', now(),'" . $_SERVER['HTTP_REFERER'] . "')");
}

?>
<?php
$resultadoD = mysql_fetch_array(mysql_query("SELECT * FROM usuarios 
INNER JOIN usuarios_tipos ON utipo_id=usr_tipo
WHERE usr_id='" . $_SESSION["id"] . "'", $conexion));
?>
<!-- styles -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="css/font-awesome.css">
<!--[if IE 7]>
<link rel="stylesheet" href="css/font-awesome-ie7.min.css">
<![endif]-->
<link href="css/chosen.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
<link href="css/theme-blue.css" rel="stylesheet">

<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
<![endif]-->
<link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>
<!--fav and touch icons -->
<link rel="shortcut icon" href="ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
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
</head>

<body>
    <div class="layout">
        <?php include("encabezado.php"); ?>

        <?php include("barra-izq.php"); ?>

        <div class="main-wrapper">
            <div class="container-fluid">
                <?php include("notificaciones.php"); ?>
                <div class="row-fluid">
                    <div class="span8">
                        <div class="content-widgets gray">
                            <div class="widget-head bondi-blue">
                                <h3> <?= $tituloPagina; ?></h3>
                            </div>
                            <div class="widget-container">
                                <img src="files/fotos/<?=$resultadoD['usr_foto'];?>" width="100">
                                
                                <form class="form-horizontal" method="post" action="sql.php" enctype="multipart/form-data">
                                    <input type="hidden" name="idSql" value="67">

                                    <div class="control-group">
                                        <label class="control-label">Usuario</label>
                                        <div class="controls">
                                            <input type="text" class="span4" name="usuario" value="<?= $resultadoD['usr_login']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Nombre</label>
                                        <div class="controls">
                                            <input type="text" class="span4" name="nombre" value="<?= $resultadoD['usr_nombre']; ?>">
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Email</label>
                                        <div class="controls">
                                            <input type="email" class="span4" name="email" value="<?= $resultadoD['usr_email']; ?>">
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Tipo de usuario</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="tipoU">
                                                <option value=""></option>
                                                <?php
                                                $conOp = mysql_query("SELECT * FROM usuarios_tipos", $conexion);
                                                while ($resOp = mysql_fetch_array($conOp)) {
                                                ?>
                                                    <option value="<?= $resOp[0]; ?>" <?php if ($resultadoD['usr_tipo'] == $resOp[0]) echo "selected";
                                                                                    else echo "disabled"; ?>><?= $resOp[1]; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Foto de perfil</label>
                                        <div class="controls">
                                            <input type="file" class="span4" name="foto">
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

                    <div class="span4">
                        <div class="content-widgets gray">
                            <div class="widget-head bondi-blue">
                                <h3> Cambiar contraseña</h3>

                            </div>
                            <div class="widget-container">
                                <p>
                                    La nueva clave nueva debe cumplir con los siguiente criterios de aceptación:<br>
                                    <b>1.</b> Solo se admiten caracteres de la a-z, A-Z, números(0-9) y el simbolo punto(.).<br>
                                    <b>2.</b> El rango debe ser entre 4 y 20 caracteres de longitud.<br><br>

                                    Un ejemplo de una clave que cumpla con los anteriores criterios es: <b>M1cl4v3.8910</b> 
                                </p>

                                <form class="form-horizontal" method="post" action="bd_update/perfil-cambiar-clave.php">
                                    <input type="hidden" name="idSql" value="76">

                                    <div class="control-group">
                                        <label class="control-label">Contraseña Actual</label>
                                        <div class="controls">
                                            <input type="password" class="span12" name="claveActual" required>
                                        </div>
                                    </div>

                                    
                                    <div class="control-group">
                                        <label class="control-label">Contraseña Nueva</label>
                                        <div class="controls">
                                            <input type="password" class="span12" name="clave" required>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-info"><i class="icon-save"></i> Cambiar contraseña</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php include("pie.php"); ?>
    </div>
</body>

</html>