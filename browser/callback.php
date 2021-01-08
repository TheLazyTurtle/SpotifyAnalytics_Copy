<?php
require "vendor/autoload.php";
session_start();

$session = new SpotifyWebAPI\Session(
	'***REMOVED***',
    '***REMOVED***',
    'http://localhost/Spotify/callback.php'
);

// Requests a access token using the code from spotify
$session -> requestAccessToken($_GET['code']);
$accessToken = $session->getAccessToken();
$refreshToken = $session->getRefreshToken();

$_SESSION["aToken"] = $accessToken;
$_SESSION["rToken"] = $refrssToken;

// Send the user along and fetch some data!
header('location: app.php');
die();