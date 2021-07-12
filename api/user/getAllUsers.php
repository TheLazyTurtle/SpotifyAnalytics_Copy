<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require "../config/database.php";
require "../objects/user.php";

// Make db and user object
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Query the users
$stmt = $user->getAllUsers();
$num = $stmt->rowCount();

// If results
if ($num > 0) {
	$users = array();
	$users["records"] = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$user = array(
			"userID" => $userID,
		);

		array_push($users["records"], $user);
	}

	// Set response to ok
	http_response_code(200);

	echo json_encode($users);
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "No results found"));
}