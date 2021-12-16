<?php
// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();

// Include db and object files
require '../system/validate_token.php';
require '../config/database.php';
require '../objects/user.php';

if (!$tokenUserId = validateToken()) {
	die(json_encode(array("message" => "Not a valid token")));
}

// Make db and user object
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$following = isset($_GET["user"]) ? $_GET["user"] : die(json_encode(array("message" => "No user provided")));
$userID = isset($tokenUserId) ? $tokenUserId : die(json_encode(array("message" => "No user provided")));

$user->id = $userID;

$result = $user->isFollowing($following);

if ($result) {
	// Set response to ok
	http_response_code(200);

	echo json_encode(array("message" => "User is following this person"));
} else {
	// Set response to not found
	http_response_code(404);

	echo json_encode(array("message" => "User is not following this person"));
}
