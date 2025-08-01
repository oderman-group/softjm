<?php
    require_once("../sesion.php");

	require RUTA_PROYECTO.'/librerias/correo_gmail/autoload.php';

	// Set up the Google Client
	$client = new Google_Client();
	$client->setAuthConfig('../../librerias/correo_gmail/credentials.json');
	$client->addScope(Google_Service_Calendar::CALENDAR);

	$idPagina = 118;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["get"])) {

		$consulta=mysqli_query($conexionBdPrincipal,"SELECT * FROM agenda WHERE age_id='".$_GET["id"]."' AND age_id_empresa={$_SESSION['dataAdicional']['id_empresa']}");
		$resultadoD = mysqli_fetch_array($consulta);
    
		mysqli_query($conexionBdPrincipal,"DELETE FROM agenda WHERE age_id='" . $_GET["id"] . "'");
		
		include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");


		$_SESSION['agendaCalendario']["evento"] = [
			'id_agenda' => $_GET["id"],
			'asunto' => $resultadoD["age_evento"],
			'fecha' => $resultadoD["age_fecha"],
			'inicio' => $resultadoD["age_inicio"],
			'fin' => $resultadoD["age_fin"],
			'lugar' => $resultadoD["age_lugar"],
			'notas' => $resultadoD["age_notas"],
			'cliente' => $resultadoD["age_cliente"],
			'evento_google_id' => $resultadoD["age_id_evento_google"],
			'evento_google_accion' => 'Eliminar'
		];

		if(!isset($_SESSION['agendaCalendario']["code"])){
			$authUrl = $client->createAuthUrl();
			header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
			exit();
		}else{
			header('Location: calendario-evento-eliminar.php?code=' . $_SESSION['agendaCalendario']["code"]);
		}

		echo '<script type="text/javascript">window.location.href="../calendario.php";</script>';
		exit();
	}

	include("../includes/calendario-callback-get.php");