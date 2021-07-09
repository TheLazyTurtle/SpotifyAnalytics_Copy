<?php
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get db connection and get song object
require '../config/database.php';
require '../objects/songs.php';

// Make new database connection and make new song object
$database = new Database();
$db = $database->getConnection();
$song = new Song($db);

// Get posted data
$data = $_POST;

// Check for empty data
if (
	!empty($data["songID"]) &&
	!empty($data["artistID"])
) {
	if ($song->linkArtistToSong($data["songID"], $data["artistID"])) {
		// Set response to created
		http_response_code(201);

		echo json_encode(array("message" => "linked artist to song"));
	} else {
		// Set response to unavailable
		http_response_code(503);

		echo json_encode(array("message" => "Unable to link artist to song"));
	}
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "Data incomplete"));
}
