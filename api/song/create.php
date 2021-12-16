<?php
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get db connection and get song object
require '../system/validate_token.php';
require '../config/database.php';
require '../config/authBackEnd.php';
require '../objects/songs.php';

if (!validate_token()) {
	die(json_encode(array("message" => "Not a valid token")));
}

// Make db connection and make new song object
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

	if ($stmt = $song->createOne()) {
		// Set response code created
		http_response_code(201);

		// Tell the user
		echo json_encode(array("message" => "song added"));
	} else {
		// Set response code service unavailable
		http_response_code(503);

		echo json_encode(array("message" => "Unable to add song"));
	}
} else {
	// set response code bad request
	http_response_code(400);

	echo json_encode(array("message" => "Data incomplete"));
}
