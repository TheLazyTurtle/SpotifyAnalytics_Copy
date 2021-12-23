<?php
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get db connection and get song object
require '../config/database.php';
require '../objects/memory.php';

// Make db connection and memory object
$database = new Database();
$db = $database->getConnection();
$memory = new Memory($db);

// Get posted data
$data = $_POST;

// Check empty data
if (
	!empty($data["postID"]) &&
	!empty($data["songID"])
) {
	$memory->id = $data["postID"];

	if ($memory->linkSongToPost($data["songID"])) {
		// Set response to created
		http_response_code(201);

		echo json_encode(array("message" => "Linked song to post"));
	} else {
		// Set response to unabailable
		http_response_code(503);

		echo json_encode(array("message" => "Unable to link song to post"));
	}
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "Data incomplete"));
}
