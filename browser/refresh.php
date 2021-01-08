<?php
	require "vendor/autoload.php";
	session_start();

	$session = new SpotifyWebAPI\Session(
	    '***REMOVED***',
		'***REMOVED***',
	);

	$accessToken = $_SESSION["aToken"];
	$refreshToken = $_SESSION["rToken"];

	if ($accessToken){
		$session->setAccessToken($accessToken);
		$session->setRefreshToken($refreshToken);
		echo "Not refreshed <br>";
	} else {
		$session->refreshAccessToken($refreshToken);
		echo "Refreshed <br>";
	}

	die();
?>