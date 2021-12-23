<?php
session_start();

// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../system/validate_token.php';
require '../config/database.php';
require '../objects/user.php';

if (!($tokenUserID = validateToken())) {
	die(json_encode(array("message" => "Not a valid token")));
}

// Make database and user object
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Get data
$userID = isset($tokenUserID) ? $tokenUserID : die(json_encode(array("message" => "No user provided")));

if ($user->createFilterSettings($userID)) {
	http_response_code(201);

	echo json_encode(array("message" => "Filter settings have been made"));
} else {
	http_response_code(503);

	echo json_encode(array("message" => "Unable to create filter settings"));
}
