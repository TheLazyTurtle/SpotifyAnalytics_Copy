<?php
// Do the auth pop-up
header('WWW-Authenticate: Basic realm="My Realm"');

// Get the tokens to check if your allowed to add
// TODO: Make a way to do this dynamic with like JWT keys??
require '../config/core.php';

$allowed = False;

// Check if the filled in data is correct and allow insertsion
if (isset($_SERVER["PHP_AUTH_USER"]) && isset($_SERVER["PHP_AUTH_PW"])) {
	if ($_SERVER["PHP_AUTH_USER"] == $backEndUser && $_SERVER["PHP_AUTH_PW"] == $backEndPass) {
		$allowed = True;
	}
} else {
	http_response_code(401);
	echo json_encode(array("message" => "Unathorized to access this end point."));
	die();
}
