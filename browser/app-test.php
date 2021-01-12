
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
$code = $_SESSION["URL"]; 

print($accessToken);
print("<br>");
print($refreshToken);
print("<br>");
print($expirationTime);
print("<br>");
print("URL ". $code );

mysqli_query(getConnection(), "UPDATE users SET spotifyAuth = '$accessToken' WHERE spotifyID = 11182819693");

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

?>
