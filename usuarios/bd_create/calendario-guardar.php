<?php
	require_once("../sesion.php");

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require RUTA_PROYECTO.'/librerias/phpmailer/Exception.php';
	require RUTA_PROYECTO.'/librerias/phpmailer/PHPMailer.php';
	require RUTA_PROYECTO.'/librerias/phpmailer/SMTP.php';
	require RUTA_PROYECTO.'/librerias/correo_gmail/autoload.php';

	// Set up the Google Client
	$client = new Google_Client();
	$client->setAuthConfig('../../librerias/correo_gmail/credentials.json');
	$client->addScope(Google_Service_Calendar::CALENDAR);

	$idPagina = 288;

	include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");


	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		$inicioHora = $_POST["inicio"];
		$finHora = $_POST["fin"];
		$correoCliente = null;

		mysqli_query($conexionBdPrincipal,"INSERT INTO agenda(age_evento, age_fecha, age_usuario, age_inicio, age_fin, age_lugar, age_notas, age_cliente, age_id_empresa)VALUES('" . $_POST["evento"] . "','" . $_POST["fecha"] . "','" . $_SESSION["id"] . "','" . $inicioHora . "','" . $finHora . "','" . $_POST["lugar"] . "','" . $_POST["notas"] . "','" . $_POST["cliente"] . "', '".$_SESSION['dataAdicional']['id_empresa']."')");
		
		$idInsertU = mysqli_insert_id($conexionBdPrincipal);

		if ($_POST["cliente"] != '0' && $_POST["enviarCorreo"] == 1 ) {

			$resultado = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='" . $_POST["cliente"] . "'"));

			$correoCliente = $resultado['cli_email'];
		}

		$_SESSION['agendaCalendario']["evento"] = [
			'id_agenda' => $idInsertU,
			'asunto' => $_POST["evento"],
			'fecha' => $_POST["fecha"],
			'inicio' => $inicioHora,
			'fin' => $finHora,
			'lugar' => $_POST["lugar"],
			'notas' => $_POST["notas"],
			'cliente' => $correoCliente,
			'evento_google_id' => null,
			'evento_google_accion' => 'Agregar'
		];

		if(!isset($_SESSION['agendaCalendario']["code"])){
			$authUrl = $client->createAuthUrl();
			header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
			exit();
		}else{
			header('Location: calendario-guardar.php?code=' . $_SESSION['agendaCalendario']["code"]);
		}

	}

	include("../includes/calendario-callback-get.php");
