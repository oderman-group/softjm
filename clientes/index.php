<?php
	include("../conexion.php");
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>JM EQUIPOS</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Admin Panel Template">
<meta name="author" content="Westilian: Kamrujaman Shohel">
<!-- styles -->
<link href="../usuarios/css/bootstrap.css" rel="stylesheet">
<link href="../usuarios/css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="../usuarios/css/font-awesome.css">
<!--[if IE 7]>
            <link rel="stylesheet" href="css/font-awesome-ie7.min.css">
        <![endif]-->
<link href="../usuarios/css/styles.css" rel="stylesheet">
<link href="../usuarios/css/theme-wooden.css" rel="stylesheet">

<!--[if IE 7]>
            <link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
        <![endif]-->
<!--[if IE 8]>
            <link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
        <![endif]-->
<!--[if IE 9]>
            <link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
        <![endif]-->
<link href="../usuarios/css/aristo-ui.css" rel="stylesheet">
<link href="../usuarios/css/elfinder.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>
<!--fav and touch icons -->
<link rel="shortcut icon" href="usuarios/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../usuarios/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../usuarios/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../usuarios/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="../usuarios/ico/apple-touch-icon-57-precomposed.png">
<!--============j avascript===========-->
<script src="../usuarios/js/jquery.js"></script>
<script src="../usuarios/js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="../usuarios/js/bootstrap.js"></script>
</head>
<body>
<div class="layout">
	<!-- Navbar================================================== -->
	<div class="navbar navbar-inverse top-nav">
		<div class="navbar-inner">
			<div class="container">
				<span class="home-link"><a href="index.php" class="icon-home"></a></span>
				<div class="btn-toolbar pull-right notification-nav">
					<div class="btn-group">
						<div class="dropdown">
							<a class="btn btn-notification"><i class="icon-reply"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<form class="form-signin" action="autentico.php" method="post">
			<input type="hidden" name="idseg" value="<?=$_GET["idseg"];?>">
			<input type="hidden" name="bd" value="<?=MAINBD;?>">
			
            <div align="center">
                <h3 class="form-signin-heading">Ingreso al Área de Clientes</h3>
                <img src="../usuarios/files/logojm.png" width="153" height="70" alt="Falgun">
            </div>
			<div class="controls input-icon">
				<i class=" icon-user-md"></i>
				<input type="text" class="input-block-level" placeholder="Usuario" name="Usuario">
			</div>
			<div class="controls input-icon">
				<i class=" icon-key"></i><input type="password" class="input-block-level" placeholder="Contraseña" name="Clave">
			</div>
			<label class="checkbox">
			<button class="btn btn-inverse btn-block" type="submit">Entrar</button>
		</form>
	</div>
</div>
</body>
</html>