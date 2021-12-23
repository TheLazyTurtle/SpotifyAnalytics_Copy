<?php
session_start();
$accessToken = $_SESSION["accessToken"];
$refreshToken = $_SESSION["refreshToken"];
$expireTime = $_SESSION["expireTime"];
$userID = $_SESSION["userID"];

$url = "http://localhost/api/user/setAuthTokens.php";
$data = array(
	"userID" => 1,
	"accessToken" => $accessToken,
	"refreshToken" => $refreshToken,
	"expireTime" => $expireTime,
);

$options = array(
	'http' => array(
		'header' => "Content-type: application/x-www-form-urlencoded\r\n",
		'method' => 'POST',
		'content' => http_build_query($data)
	)
);

$context = stream_context_create($options);
$result = fopen($url, 'r', false, $context);

print_r($result);
if ($result === False) {
} else {
	header("Location: login.php");
}
