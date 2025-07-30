<?php
require 'vendor/autoload.php';

session_start();
session_destroy();

// Set up the Google Client
$client = new Google_Client();
$client->setAuthConfig('credentials.json');
$client->addScope(Google_Service_Calendar::CALENDAR);

// Redirect to Google's OAuth 2.0 server
if (!isset($_SESSION['access_token']) && !isset($_GET['code'])) {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit();
}

// Exchange authorization code for an access token
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token;
}

// Set the access token
$client->setAccessToken($_SESSION['access_token']);

// Create a new Google Calendar service
$service = new Google_Service_Calendar($client);

// Define the event details
$event = new Google_Service_Calendar_Event(array(
    'summary' => 'Meeting with Client',
    'location' => '123 Business St, Business City, BC',
    'description' => 'Discuss project requirements and timelines.',
    'start' => array(
        'dateTime' => '2025-07-21T10:00:00-05:00',
        'timeZone' => 'America/Bogota',
    ),
    'end' => array(
        'dateTime' => '2025-07-21T11:00:00-05:00',
        'timeZone' => 'America/Bogota',
    ),
    'attendees' => array(
        array('email' => 'ing.jorgediaz@outlook.com'),
    ),
    'reminders' => array(
        'useDefault' => FALSE,
        'overrides' => array(
            array('method' => 'email', 'minutes' => 24 * 60),
            array('method' => 'popup', 'minutes' => 10),
        ),
    ),
));

try {
    // Insert the event into the calendar
    $calendarId = 'primary';
    $event = $service->events->insert($calendarId, $event);
    printf('Event created: %s\n', $event->htmlLink);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}