<html>
<head>
	<script src="jquery.min.js"></script>
	<script type="text/javascript">
		// Load in the song data
		setInterval(function(){ 
			$("#test").load('data.php'); 
			}, 1000);

		setInterval(function () {
			$("#auth").load('refresh.php');
		}, 300000);
	</script>
</head>

<body>
	<span id="auth"></span>
	<span id="test">Loading...</span>

<?php
session_start();
require "vendor/autoload.php";
require "connect.php";

$session = new SpotifyWebAPI\Session(
    '***REMOVED***',
    '***REMOVED***',
);
$accessToken = $_SESSION["aToken"];
$refreshToken = $_SESSION["rToken"];

if ($accessToken){
	$session->setAccessToken($accessToken);
	$session->setRefreshToken($refreshToken);
} else {
	$session->refreshAccessToken($refreshToken);
}

$options = [
	'auto_refresh' => true,
];

$api = new SpotifyWebAPI\SpotifyWebAPI($options, $session);

$api->setAccessToken($accessToken);
$api->me();

print_r($api->current_playback());
// $cps = $api->getMyCurrentTrack();
// $cps = json_decode(json_encode($cps), True);
$_SESSION["api"] = $api;
?>
