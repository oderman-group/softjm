<?php include("sesion.php");?>
<?php
$idPagina = 29;
$paginaActual['pag_nombre'] = "Carpeta";
?>
<?php include("verificar-paginas.php");?>
<?php include("head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
$cliente = mysql_fetch_array(mysql_query("SELECT * FROM clientes WHERE cli_id='".$_GET["cte"]."'",$conexion));
$momento = mysql_fetch_array(mysql_query("SELECT * FROM momentos WHERE mom_id='".$_GET["mto"]."'",$conexion));
?>
<!-- styles -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/jquery.minicolors.css" rel="stylesheet">
<link href="css/bootstrap-editable.css" rel="stylesheet">
<link href="js/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/bootstrap-wysihtml5-0.0.2.css" rel="stylesheet" type="text/css">
<link href="css/jquery.gritter.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="css/font-awesome.css">
<!--[if IE 7]>
            <link rel="stylesheet" href="css/font-awesome-ie7.min.css">
        <![endif]-->


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
<!--============j avascript===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootbox.js"></script>
<script src="js/jquery.minicolors.js"></script>
<script src="js/bootstrap-editable.js"></script>
<script src="js/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/wysihtml5-0.3.0.min.js"></script>
<script src="js/inputs-ext/wysihtml5/bootstrap-wysihtml5-0.0.2/bootstrap-wysihtml5-0.0.2.min.js"></script>
<script src="js/inputs-ext/wysihtml5/wysihtml5.js"></script>
<script src="js/jquery.gritter.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script>
            $(document).on("click", ".alert-box", function (e) {
                bootbox.alert("Hello world!", function () {
                    //callback
                });
            });
            $(document).on("click", ".confirm", function (e) {
                bootbox.confirm("Are you sure?", function (result) {
                    //callback
                });
            });
            $(document).on("click", ".prompt", function (e) {
                bootbox.prompt("What is your name?", function (result) {
                    if (result === null) {
                        //callback
                    } else {
                        //callback
                    }
                });
            });
            $(document).on("click", ".dialog", function (e) {
                bootbox.dialog("I am a custom dialog", [{
                    "label": "Success!",
                    "class": "btn-success",
                    "callback": function () {
                    }
                }, {
                    "label": "Danger!",
                    "class": "btn-danger",
                    "callback": function () {
                    }
                }, {
                    "label": "Click ME!",
                    "class": "btn-primary",
                    "callback": function () {
                    }
                }, {
                    "label": "Just a button..."
                }]);
            });
            $(function () {
                var consoleTimeout;
                $('.minicolors').each(function () {
                    //
                    // Dear reader, it's actually much easier than this to initialize 
                    // miniColors. For example:
                    //
                    //  $(selector).minicolors();
                    //
                    // The way I've done it below is just to make it easier for me 
                    // when developing the plugin. It keeps me sane, but it may not 
                    // have the same effect on you!
                    //
                    $(this).minicolors({
                        control: $(this).attr('data-control') || 'hue',
                        defaultValue: $(this).attr('data-default-value') || '',
                        inline: $(this).hasClass('inline'),
                        letterCase: $(this).hasClass('uppercase') ? 'uppercase' : 'lowercase',
                        opacity: $(this).hasClass('opacity'),
                        position: $(this).attr('data-position') || 'default',
                        styles: $(this).attr('data-style') || '',
                        swatchPosition: $(this).attr('data-swatch-position') || 'left',
                        textfield: !$(this).hasClass('no-textfield'),
                        theme: $(this).attr('data-theme') || 'default',
                        change: function (hex, opacity) {
                            // Generate text to show in console
                            text = hex ? hex : 'transparent';
                            if (opacity) text += ', ' + opacity;
                            text += ' / ' + $(this).minicolors('rgbaString');
                            // Show text in console; disappear after a few seconds
                            $('#console').text(text).addClass('busy');
                            clearTimeout(consoleTimeout);
                            consoleTimeout = setTimeout(function () {
                                $('#console').removeClass('busy');
                            }, 3000);
                        }
                    });
                });
            });
            $(function () {
                $.fn.editable.defaults.mode = 'inline';
                $('#username').editable({
                    type: 'text',
                    pk: '1',
                    url: '/post',
                    title: 'Enter username'
                });
            });
            $(function () {
                $('#note').editable();
            });
            $(function () {
                $('.textarea').wysihtml5();
            });
        </script>
<script type="text/javascript">
            $(function () {
                // global setting override
                /*
		$.extend($.gritter.options, {
		    class_name: 'gritter-light', // for light notifications (can be added directly to $.gritter.add too)
		    position: 'bottom-left', // possibilities: bottom-left, bottom-right, top-left, top-right
			fade_in_speed: 100, // how fast notifications fade in (string or int)
			fade_out_speed: 100, // how fast the notices fade out
			time: 3000 // hang on the screen for...
		});
        */
                $('#add-sticky').click(function () {
                    var unique_id = $.gritter.add({
                        // (string | mandatory) the heading of the notification
                        title: 'This is a sticky notice!',
                        // (string | mandatory) the text inside the notification
                        text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
                        // (string | optional) the image to display on the left
                        image: 'images/notification-thumb.png',
                        // (bool | optional) if you want it to fade out on its own or just sit there
                        sticky: true,
                        // (int | optional) the time you want it to be alive for before fading out
                        time: '',
                        // (string | optional) the class name you want to apply to that specific message
                        class_name: 'my-sticky-class'
                    });
                    // You can have it return a unique id, this can be used to manually remove it later using
                    /*
			setTimeout(function(){
				$.gritter.remove(unique_id, {
					fade: true,
					speed: 'slow'
				});
			}, 6000)
			*/
                    return false;
                });
                $('#add-regular').click(function () {
                    $.gritter.add({
                        // (string | mandatory) the heading of the notification
                        title: 'This is a regular notice!',
                        // (string | mandatory) the text inside the notification
                        text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
                        // (string | optional) the image to display on the left
                        image: 'images/notification-thumb.png',
                        // (bool | optional) if you want it to fade out on its own or just sit there
                        sticky: false,
                        // (int | optional) the time you want it to be alive for before fading out
                        time: ''
                    });
                    return false;
                });
                $('#add-max').click(function () {
                    $.gritter.add({
                        // (string | mandatory) the heading of the notification
                        title: 'This is a notice with a max of 3 on screen at one time!',
                        // (string | mandatory) the text inside the notification
                        text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',
                        // (string | optional) the image to display on the left
                        image: 'images/notification-thumb.png',
                        // (bool | optional) if you want it to fade out on its own or just sit there
                        sticky: false,
                        // (function) before the gritter notice is opened
                        before_open: function () {
                            if ($('.gritter-item-wrapper').length == 3) {
                                // Returning false prevents a new gritter from opening
                                return false;
                            }
                        }
                    });
                    return false;
                });
                $('#add-without-image').click(function () {
                    $.gritter.add({
                        // (string | mandatory) the heading of the notification
                        title: 'This is a notice without an image!',
                        // (string | mandatory) the text inside the notification
                        text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.'
                    });
                    return false;
                });
                $('#add-gritter-light').click(function () {
                    $.gritter.add({
                        // (string | mandatory) the heading of the notification
                        title: 'This is a light notification',
                        // (string | mandatory) the text inside the notification
                        text: 'Just add a "gritter-light" class_name to your $.gritter.add or globally to $.gritter.options.class_name',
                        class_name: 'gritter-light'
                    });
                    return false;
                });
                $('#add-with-callbacks').click(function () {
                    $.gritter.add({
                        // (string | mandatory) the heading of the notification
                        title: 'This is a notice with callbacks!',
                        // (string | mandatory) the text inside the notification
                        text: 'The callback is...',
                        // (function | optional) function called before it opens
                        before_open: function () {
                            alert('I am called before it opens');
                        },
                        // (function | optional) function called after it opens
                        after_open: function (e) {
                            alert("I am called after it opens: \nI am passed the jQuery object for the created Gritter element...\n" + e);
                        },
                        // (function | optional) function called before it closes
                        before_close: function (e, manual_close) {
                            var manually = (manual_close) ? 'The "X" was clicked to close me!' : '';
                            alert("I am called before it closes: I am passed the jQuery object for the Gritter element... \n" + manually);
                        },
                        // (function | optional) function called after it closes
                        after_close: function (e, manual_close) {
                            var manually = (manual_close) ? 'The "X" was clicked to close me!' : '';
                            alert('I am called after it closes. ' + manually);
                        }
                    });
                    return false;
                });
                $('#add-sticky-with-callbacks').click(function () {
                    $.gritter.add({
                        // (string | mandatory) the heading of the notification
                        title: 'This is a sticky notice with callbacks!',
                        // (string | mandatory) the text inside the notification
                        text: 'Sticky sticky notice.. sticky sticky notice...',
                        // Stickeh!
                        sticky: true,
                        // (function | optional) function called before it opens
                        before_open: function () {
                            alert('I am a sticky called before it opens');
                        },
                        // (function | optional) function called after it opens
                        after_open: function (e) {
                            alert("I am a sticky called after it opens: \nI am passed the jQuery object for the created Gritter element...\n" + e);
                        },
                        // (function | optional) function called before it closes
                        before_close: function (e) {
                            alert("I am a sticky called before it closes: I am passed the jQuery object for the Gritter element... \n" + e);
                        },
                        // (function | optional) function called after it closes
                        after_close: function () {
                            alert('I am a sticky called after it closes');
                        }
                    });
                    return false;
                });
                $("#remove-all").click(function () {
                    $.gritter.removeAll();
                    return false;
                });
                $("#remove-all-with-callbacks").click(function () {
                    $.gritter.removeAll({
                        before_close: function (e) {
                            alert("I am called before all notifications are closed.  I am passed the jQuery object containing all  of Gritter notifications.\n" + e);
                        },
                        after_close: function () {
                            alert('I am called after everything has been closed.');
                        }
                    });
                    return false;
                });
            });
        </script>
<style>
                    .sample-noty {
                        margin:0px;
                    }
                    .sample-noty li {
                        list-style:none;
                    }
                    .widget-container {
                        padding-bottom:20px;
                    }
</style>
</head>
<body>
<div class="layout">
	<?php include("encabezado.php");?>
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?> de <?=$cliente[1];?></h3>
						<ul class="top-right-toolbar">
							<li><a data-toggle="dropdown" class="dropdown-toggle blue-violate" href="#" title="Users"><i class="icon-user"></i></a></li>
							<li><a href="#" class="green" title="Upload"><i class=" icon-upload-alt"></i></a></li>
							<li><a href="#" class="bondi-blue" title="Settings"><i class="icon-cogs"></i></a></li>
						</ul>
					</div>
					<ul class="breadcrumb">
						<li><a href="#" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="clientes.php">Clientes</a><span class="divider"><i class="icon-angle-right"></i></span></li>
                        <li><a href="clientes-momentos.php?cte=<?=$_GET["cte"];?>&mto=<?=$_GET["mto"];?>">Momentos <b><?=$momento[2];?></b></a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?> de <?=$cliente[1];?></li>
					</ul>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span12">
					<div class="tab-widget">
						<ul class="nav nav-tabs" id="myTab1">
							<li class="active"><a href="#user"><i class="icon-folder-close"></i> Carpetas</a></li>
						</ul>
						<div class="tab-content">
							
							<?php
							$consulta = mysql_query("SELECT * FROM carpetas WHERE carp_momento='".$_GET["mto"]."'",$conexion);
							$num = mysql_num_rows($consulta);
							if($num==0){
								$c=1;
								$carpetas = array("","Legales","IC&amp;T","Evidencias");
								while($c<=3){
									mysql_query("INSERT INTO carpetas(carp_nombre, carp_momento)VALUES('".$carpetas[$c]."','".$_GET["mto"]."')",$conexion);
									if(mysql_errno()!=0){echo mysql_error(); exit();}
									$c++;
								}
								echo '<script type="text/javascript">window.location.href="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'";</script>';
								exit();
							}
							while($res = mysql_fetch_array($consulta)){
								$nDocumentos = mysql_num_rows(mysql_query("SELECT * FROM documentos WHERE doc_carpeta='".$res[0]."'",$conexion));
								$usuariosUltimo = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usr_id='".$res[4]."'",$conexion));
							?>
                            <div class="tab-pane active" id="user">
								<div class="user_list">
									<div class="user_block">
										<div class="info_block">
											<div class="widget_thumb">
												<img width="46" height="46" alt="User" src="images/user-thumb1.png">
											</div>
											<ul class="list_info clearfix">
												<li><span>Nombre: <i><a href="documentos.php?carpeta=<?=$res[0];?>&cte=<?=$_GET["cte"];?>&mto=<?=$_GET["mto"];?>"><?=$res[1];?></a></i></span></li>
												<li><span>Ultimo ingreso: <b><?=$res[3]." - ".$usuariosUltimo['usr_nombre'];?></b></span></li>
												<li><span>Documentos: <b><?=$nDocumentos;?></b></span></li>
											</ul>
										</div>
										<div class="clearfix">
											<div class="btn-group pull-left">
												<a href="documentos.php?carpeta=<?=$res[0];?>&cte=<?=$_GET["cte"];?>&mto=<?=$_GET["mto"];?>" class="btn btn-mini"><i class="icon-arrow-right"></i> Entrar</a>
                                                <!--<a href="#" class="btn "><i class=" icon-remove-sign"></i> Eliminar</a>-->
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
                            
							</div>
                            </div>
                            </div>
                            </div>
						</div>
					</div>
				</div>
			
<?php include("pie.php");?>
</div>
</body>
</html>