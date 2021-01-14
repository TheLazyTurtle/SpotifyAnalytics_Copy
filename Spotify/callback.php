<?php
require "../browser/vendor/autoload.php";
session_start();

$session = new SpotifyWebAPI\Session(
	'***REMOVED***',
	'***REMOVED***',
	'http://192.168.2.7/Spotify/callback.php'
);

// Requests a access token using the code from spotify
$session -> requestAccessToken($_GET['code']);
$accessToken = $session->getAccessToken();
$refreshToken = $session->getRefreshToken();
$expirationTime = $session->getTokenExpiration();

$_SESSION["aToken"] = $accessToken;
$_SESSION["rToken"] = $refreshToken;
$_SESSION["xTime"] = $expirationTime;
$_SESSION["URL"] = $_GET['code'];

// Send the user along and fetch some data!
//header('Location: ../main/index.php');
header("Location: ../browser/app.php");
die();

?>
