<?php
// Required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow_Methods: GET");
header("Acces-Control-Allow_credentials: true");
header("Content-Type: application/json");

// Include db and object files
require '../system/validate_token.php';
require '../system/hasViewingRights.php';
require_once '../config/database.php';
require_once '../objects/user.php';

if (!$tokenUserId = validateToken()) {
	die(json_encode(array("message" => "Not a valid token")));
}

// Make db and user object
$database = new Database();
$db = $database->getConnection();
$user = new User($db);
if (!empty($_GET["username"])) {
	$user->username = $_GET["username"];
	$hasViewingRights = hasViewingRights($tokenUserId, $_GET["username"]);
} else {
	$user->id = isset($tokenUserId) ? $tokenUserId : null;
}

// Query user
$stmt = $user->read_one();
$user->getFollowersCount();
$user->getFollowingCount();

// Found data
if ($user->id != null) {
	$userArr = array(
		"id" => $user->id,
		"username" => $user->username,
		"following" => $user->following,
		"followers" => $user->followers,
		"img" => $user->img,
		"viewingRights" => $hasViewingRights
	);

	// Set response to ok
	http_response_code(200);

	echo json_encode($userArr);
} else {
	// Set response to not found
	http_response_code(404);

	echo json_encode(array("message" => "User does not exist"));
}
