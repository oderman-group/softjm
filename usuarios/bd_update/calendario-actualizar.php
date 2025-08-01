<?php   
    require_once("../sesion.php");

    require RUTA_PROYECTO.'/librerias/correo_gmail/autoload.php';

    // Set up the Google Client
    $client = new Google_Client();
    $client->setAuthConfig('../../librerias/correo_gmail/credentials.json');
    $client->addScope(Google_Service_Calendar::CALENDAR);

    $idPagina = 284;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $correoCliente = null;
        $inicioHora = $_POST["inicio"];
        $finHora = $_POST["fin"];

        if ($_POST["cliente"] != '0' && $_POST["enviarCorreo"] == 1 ) {

			$resultado = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='" . $_POST["cliente"] . "'"));

			$correoCliente = $resultado['cli_email'];
		}

        mysqli_query($conexionBdPrincipal,"UPDATE agenda SET age_evento='" . $_POST["evento"] . "', age_fecha='" . $_POST["fecha"] . "', age_lugar='" . $_POST["lugar"] . "', age_notas='" . $_POST["notas"] . "', age_inicio='" . $inicioHora . "', age_fin='" . $finHora . "' WHERE age_id='" . $_POST["id"] . "'");


        $_SESSION['agendaCalendario']["evento"] = [
            'id_agenda' => $_POST["id"],
            'asunto' => $_POST["evento"],
            'fecha' => $_POST["fecha"],
            'inicio' => $inicioHora,
            'fin' => $finHora,
            'lugar' => $_POST["lugar"],
            'notas' => $_POST["notas"],
            'cliente' => $correoCliente,
            'evento_google_id' => $_POST["id_evento_google"],
            'evento_google_accion' => 'Actualizar'
        ];

        if(!isset($_SESSION['agendaCalendario']["code"])){
            $authUrl = $client->createAuthUrl();
            header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
            exit();
        }else{
            header('Location: calendario-actualizar.php?code=' . $_SESSION['agendaCalendario']["code"]);
        }

        echo '<script type="text/javascript">window.location.href="../calendario.php";</script>';
        exit();
    }

	include("../includes/calendario-callback-get.php");