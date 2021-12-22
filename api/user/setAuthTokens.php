<?php
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get db connection and get played object
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

// Get posted data
$data = $_POST;

if (
	!empty($data["userID"]) &&
	!empty($data["accessToken"]) &&
	!empty($data["refreshToken"]) &&
	!empty($data["expireTime"])
) {
	if ($user->setAuthTokens(
		$data["userID"],
		$data["accessToken"],
		$data["refreshToken"],
		$data["expireTime"]
	)) {
		// Set response to created
		http_response_code(201);

		echo json_encode(array("message" => "Saved tokens"));
	} else {
		http_response_code(503);

		echo json_encode(array("message" => "Tokens already existed"));
	}
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "Data incomplete"));
}
