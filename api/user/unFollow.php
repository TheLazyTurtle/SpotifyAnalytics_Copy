<?php
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

session_start();

// Get db connection and get song object
require '../config/database.php';
require '../objects/user.php';

// Make database and user object
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Get posted data
$userToUnFollow = $_POST["userToUnFollow"];
$user->id = $_SESSION["userID"];

// Check if data is empty
if (!empty($userToUnFollow) &	!empty($user->id)) {
	if ($user->unFollow($userToUnFollow)) {
		// Set response to created
		http_response_code(201);

		echo json_encode(array("message" => "Unfollowed user"));
	} else {
		// Set response to service unavailable
		http_response_code(503);

		echo json_encode(array("message" => "Unable to unfollow user"));
	}
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "Data incomplete"));
}
