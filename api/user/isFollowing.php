<?php
// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();

// Include db and object files
require '../config/database.php';
require '../objects/user.php';

// Make db and user object
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$following = isset($_POST["user"]) ? $_POST["user"] : die();
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : die();

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
