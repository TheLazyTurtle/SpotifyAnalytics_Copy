<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../config/database.php';
include '../objects/memory.php';

// Make db and memory object
$database = new Database();
$db = $database->getConnection();
$memory = new Memory($db);

// Get posted data
$data = $_POST;

// Check if data is complete
if (
	!empty($data["postID"]) &&
	!empty($data["userID"]) &&
	!empty($data["status"])
) {
	if ($memory->updateLikeCount($data["postID"], $data["userID"], $data["status"])) {
		// Set response to ok
		http_response_code(200);

		echo json_encode(array("message" => "Update like status"));
	} else {
		// Set response to service unavailable
		http_response_code(503);

		echo json_encode(array("message" => "Couldn't update like status"));
	}
} else {
	// Set respons to bad request
	http_response_code(400);

	echo json_encode(array("message" => "Data incomplete"));
}
