<?php
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get db connection and get memory object
require '../config/database.php';
require '../objects/memory.php';

//Make db and memory object
$database = new Database();
$db = $database->getConnection();
$memory = new Memory($db);

// Get posted data
$data = $_POST;

// Check if data is complete
if (
	!empty($data["postID"]) &&
	!empty($data["userID"]) &&
	!empty($data["description"]) &&
	!empty($data["img"])
) {
	$memory->id = $data["postID"];
	$memory->userID = $data["userID"];
	$memory->description = $data["description"];
	$memory->img = $data["img"];

	if ($memory->create()) {
		// Set response to created
		http_response_code(201);

		echo json_encode(array("message" => "memory added"));
	} else {
		// Set response to service unavailable
		http_response_code(503);

		echo json_encode(array("message" => "Unable to add memory"));
	}
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "Data incomplete"));
}
