<?php
session_start();
require 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
	"***REMOVED***",
	"***REMOVED***",
	"http://localhost/callback.php"
);

$state = $_GET['state'];

$session->requestAccessToken($_GET['code']);
$_SESSION['accessToken'] = $session->getAccessToken();
$_SESSION['refreshToken'] = $session->getRefreshToken();
$_SESSION['expireTime'] = $session->getTokenExpiration();

header("Location: register.php");
