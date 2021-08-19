<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../config/database.php';
include '../objects/album.php';

// Make db and album object
$database = new Database();
$db = $database->getConnection();
$album = new Album($db);

// Get posted data
$data = $_POST;

// Check if data is complete
if (
	!empty($data["albumID"]) &&
	!empty($data["name"]) &&
	!empty($data["url"]) &&
	!empty($data["releaseDate"]) &&
	!empty($data["img"]) &&
	!empty($data["primaryArtistID"])
) {
	$album->id = $data["albumID"];
	$album->name = $data["name"];
	$album->releaseDate = $data["releaseDate"];
	$album->primaryArtist = $data["primaryArtistID"];
	$album->url = $data["url"];
	$album->img = $data["img"];

	if ($album->update()) {
		// Set response to updated
		http_response_code(201);

		echo json_encode(array("message" => "Updated album"));
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
