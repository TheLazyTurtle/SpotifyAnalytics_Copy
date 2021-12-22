<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../system/validate_token.php';
include '../config/database.php';
include '../objects/songs.php';

if (!($userID = validateToken()) || $userID != "system") {
	die(json_encode(array("message" => "Not a valid token")));
}

// Make db and song object
$database = new Database();
$db = $database->getConnection();
$song = new Song($db);

// Get posted data
$data = $_POST;

// Check if data is not empty
if (
	!empty($data["songID"]) &&
	!empty($data["name"]) &&
	!empty($data["length"]) &&
	!empty($data["url"]) &&
	!empty($data["img"]) &&
	!empty($data["preview"]) &&
	!empty($data["albumID"]) &&
	!empty($data["trackNumber"]) &&
	!empty($data["explicit"])
) {
	$song->id = $data["songID"];
	$song->name = $data["name"];
	$song->length = $data["length"];
	$song->url = $data["url"];
	$song->img = $data["img"];
	$song->preview = $data["preview"];
	$song->albumID = $data["albumID"];
	$song->trackNumber = $data["trackNumber"];
	$song->explicit = $data["explicit"];

	if ($song->update()) {
		// Set response code to updated
		http_response_code(201);

		echo json_encode(array("message" => "updated the song"));
	} else {
		// Set response to service unavailable
		http_response_code(503);

		echo json_encode(array("message" => "Unable to update song"));
	}
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "Data incomplete"));
}
