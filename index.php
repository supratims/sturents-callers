<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/common.php';
require_once __DIR__ . '/reports/UsersByCountry.php';
require_once __DIR__ . '/reports/EventsByCountry.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/client_secrets.json');
$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);

// If the user has already authorized this app then get an access token
// else redirect to ask the user to authorize access to Google Analytics.
if (isset($_SESSION['access_token']) && $_SESSION['access_token']){
	// Set the access token on the client.
	$client->setAccessToken($_SESSION['access_token']);

	// Create an authorized analytics service object.
	$analytics = new Google_Service_AnalyticsReporting($client);

	// todo: catch exception here and call logout if no auth found

	// Call the Analytics Reporting API V4.
	$report = new EventsByCountry();
	$objects = $report->getReport($analytics);

	// Print the response.
	$report->printResults($objects, ["ga:eventLabel", "ga:city"], 'events');

}
else {
	$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . URL_OAUTH;
	header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
