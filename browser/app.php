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
$expirationTime = $_SESSION["xTime"]; 
$spID = $_SESSION["spID"];
$code = $_SESSION["URL"]; 

$connection = getConnection();

mysqli_query($connection, "UPDATE users SET spotifyAuth = '$accessToken', spotifyRefresh = '$refreshToken', spotifyExpire = '$expirationTime' WHERE spotifyID = '$spID'");
mysqli_close($connection);

if ($accessToken){
	$session->setAccessToken($accessToken);
	$session->setRefreshToken($refreshToken);
} else {
	$session->refreshAccessToken($refreshToken);
}

$options = [
];

$api = new SpotifyWebAPI\SpotifyWebAPI($options, $session);

$api->setAccessToken($accessToken);
header("Location: ../main/index.php");

?>
