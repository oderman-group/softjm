<?php

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["code"])) {
        // Exchange authorization code for an access token
        if(!isset($_SESSION['agendaCalendario']["code"])){
            if (isset($_GET['code'])) {
                $_SESSION['agendaCalendario']["code"] = $_GET['code'];
                $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
                $_SESSION['agendaCalendario']["token"] = $token;
            }
        }

        // Set the access token
        $client->setAccessToken($_SESSION['agendaCalendario']["token"]);

        // Create a new Google Calendar service
        $service = new Google_Service_Calendar($client);

        $inicioH = $_SESSION['agendaCalendario']["evento"]["fecha"].'T'.$_SESSION['agendaCalendario']["evento"]["inicio"].':00-05:00';
        $finH = $_SESSION['agendaCalendario']["evento"]["fecha"].'T'.$_SESSION['agendaCalendario']["evento"]["fin"].':00-05:00';

        $attendees = [];
        if($_SESSION['agendaCalendario']["evento"]['cliente'] != ""){
            $attendees[] = ['email' => $_SESSION['agendaCalendario']["evento"]['cliente']];            
        }
        if(isset($_SESSION['dataAdicional']["datos_usuario_actual"]['usr_email']) && $_SESSION['dataAdicional']["datos_usuario_actual"]['usr_email'] != $_SESSION['agendaCalendario']["evento"]['cliente']){
            $attendees[] = ['email' => $_SESSION['dataAdicional']["datos_usuario_actual"]['usr_email']];
        }

        $eventId = $_SESSION['agendaCalendario']["evento"]['evento_google_id'];

        try {

            if ($_SESSION['agendaCalendario']["evento"]['evento_google_accion'] == 'Actualizar') {
                // Get the existing event
                $event = $service->events->get('primary', $eventId);
                if (!$event) {
                    throw new Exception('Event not found.');
                }

                $event->setSummary($_SESSION['agendaCalendario']["evento"]['asunto']);
                $event->setLocation($_SESSION['agendaCalendario']["evento"]['lugar']);
                $event->setDescription($_SESSION['agendaCalendario']["evento"]['notas']);
            } elseif ($_SESSION['agendaCalendario']["evento"]['evento_google_accion'] == 'Agregar') {
                // Create a new event
                // Define the event details
                $event = new Google_Service_Calendar_Event(array(
                    'summary' => $_SESSION['agendaCalendario']["evento"]['asunto'],
                    'location' => $_SESSION['agendaCalendario']["evento"]['lugar'],
                    'description' => $_SESSION['agendaCalendario']["evento"]['notas'],
                    'reminders' => array(
                        'useDefault' => FALSE,
                        'overrides' => array(
                            array('method' => 'email', 'minutes' => 24 * 60),
                            array('method' => 'popup', 'minutes' => 10),
                        ),
                    ),
                ));
            }

            if ($_SESSION['agendaCalendario']["evento"]['evento_google_accion'] != 'Eliminar') {
                $event->setStart(new Google_Service_Calendar_EventDateTime([
                    'dateTime' => $inicioH,
                    'timeZone' => 'America/Bogota',
                ]));
                $event->setEnd(new Google_Service_Calendar_EventDateTime([
                    'dateTime' => $finH,
                    'timeZone' => 'America/Bogota',
                ]));

                $event->setAttendees($attendees);
            }

            $calendarId = 'primary';
            if ($_SESSION['agendaCalendario']["evento"]['evento_google_accion'] == 'Actualizar') {
                $event = $service->events->update($calendarId, $eventId, $event);
            } elseif ($_SESSION['agendaCalendario']["evento"]['evento_google_accion'] == 'Agregar') {
                $event = $service->events->insert($calendarId, $event);
                $_SESSION['agendaCalendario']["evento"]['evento_google_id'] = $event->getId();
                mysqli_query($conexionBdPrincipal,"UPDATE agenda SET age_id_evento_google='" . $event->getId() . "' WHERE age_id='" . $_SESSION['agendaCalendario']["evento"]['id_agenda'] . "'");
            }elseif ($_SESSION['agendaCalendario']["evento"]['evento_google_accion'] == 'Eliminar') {
                $service->events->delete($calendarId, $eventId);
                mysqli_query($conexionBdPrincipal,"DELETE FROM agenda WHERE age_id='" . $_SESSION['agendaCalendario']["evento"]['id_agenda'] . "'");
            }

            echo '<script type="text/javascript">window.location.href="../calendario.php";</script>';
            exit();

        } catch (Google\Service\Exception $e) {
            $responseBody = $e->getMessage();

            if ($e->getCode() === 401) {
                $_SESSION['agendaCalendario']["code"] = null;
                $_SESSION['agendaCalendario']["token"] = null;
                $authUrl = $client->createAuthUrl();
                header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
                exit();
            }
        }
    }