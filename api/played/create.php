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
require '../objects/played.php';

if (!($tokenUserID = validateToken()) || $tokenUserID != "system"){
	die(json_encode(array("message" => "Not a valid token")));
}

// Make db connection and make new played object
$database = new Database();
$db = $database->getConnection();
$played = new Played($db);

// Get posted data
$data = $_POST;

// Check if data is not empty
if (
	!empty($data["songID"]) &&
	!empty($data["datePlayed"]) &&
	!empty($data["playedBy"]) &&
	!empty($data["songName"])
) {
	$played->songID = $data["songID"];
	$played->datePlayed = $data["datePlayed"];
	$played->playedBy = $data["playedBy"];
	$played->songName = $data["songName"];

	if ($played->create()) {
		// Set response to created
		http_response_code(201);

		echo json_encode(array("message" => "entry added"));
	} else {
		http_response_code(503);

		echo json_encode(array("message" => "Unable to add entry"));
	}
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "Data incomplete"));
}
