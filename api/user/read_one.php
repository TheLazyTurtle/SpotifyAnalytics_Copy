<?php
// Required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow_Methods: GET");
header("Acces-Control-Allow_credentials: true");
header("Content-Type: application/json");

// Include db and object files
require '../config/database.php';
require '../objects/user.php';

// Make db and user object
$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$user->username = isset($_POST["username"]) ? $_POST["username"] : die();

// Query user
$stmt = $user->read_one();
$user->getFollowersCount();
$user->getFollowingCount();

// Found data
if ($user->id != null) {
	$userArr = array(
		"id" => $user->id,
		"username" => $user->username,
		"firstname" => $user->firstname,
		"lastname" => $user->lastname,
		"following" => $user->following,
		"followers" => $user->followers,
		"email" => $user->email,
		"img" => $user->img,
		"isAdmin" => $user->isAdmin
	);

	// Set response to ok
	http_response_code(200);

	echo json_encode($userArr);
} else {
	// Set response to not found
	http_response_code(404);

	echo json_encode(array("message" => "User does not exist"));
}
