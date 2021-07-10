<?php
session_start();

// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object file
require "../config/database.php";
require "../objects/user.php";
require "../config/core.php";

// Make db and user object
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$userID = isset($_GET["userID"]) && !empty($_GET["userID"]) ? $_GET["userID"] : die();

// Query the results
$stmt = $user->getActiveHours($userID);
$num = $stmt->rowCount();

// If results
if ($num > 0) {
	// Result array
	$resultArr = array();
	$resultArr["records"] = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);

		$resulItem = array(
			"percentage" => $percent,
			"time" => $time
		);

		array_push($resultArr["records"], $resulItem);
	}

	// Set response to ok
	http_response_code(200);

	echo json_encode($resultArr);
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "No results found"));
}
