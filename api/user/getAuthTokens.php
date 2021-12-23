<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require "../system/validate_token.php";
require "../config/database.php";
require "../objects/user.php";

if (!$tokenUserID = validateToken()) {
	die(json_encode(array("message" => "Not a valid token")));
}

// Make db and user object
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Get input
$userID = !empty($_GET["userID"]) ? $_GET["userID"] : $tokenUserID;

// Query the auth tokens
$stmt = $user->getAuthTokens($userID);
$num = $stmt->rowCount();

// If results
if ($num > 0) {
	$tokens = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$token = array(
			"auth" => $authToken,
			"refresh" => $refreshToken,
			"expire" => $ExpireDate
		);
		array_push($tokens, $token);
	}

	// Set response to ok
	http_response_code(200);

	echo json_encode($tokens);
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "No results found"));
}
