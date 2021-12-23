<?php
session_start();
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get db connection and get song object
require '../system/validate_token.php';
require '../config/database.php';
require '../objects/user.php';

if (!$tokenUserID = validateToken()) {
	die(json_encode(array("message" => "Not a valid token")));
}

// Make database and user object
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Get posted data
$user->id = $tokenUserID;
$userToFollow = $_POST["userToFollow"];

// Check if data is empty
if (!empty($userToFollow) &	!empty($user->id)) {
	if ($user->follow($userToFollow)) {
		// Set response to created
		http_response_code(201);

		echo json_encode(array("message" => "Now following user"));
	} else {
		// Set response to service unavailable
		http_response_code(503);

		echo json_encode(array("message" => "Unable to follow user"));
	}
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "Data incomplete"));
}
