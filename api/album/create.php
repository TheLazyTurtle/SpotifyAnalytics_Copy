<?php
// Require hearders
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset-UTF-8");
header("Access-Control-Allow_Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Heades: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get db connection and get song object
require '../config/database.php';
require '../config/authBackEnd.php';
require '../objects/album.php';

// Make db connection and make new album object
$database = new Database();
$db = $database->getConnection();
$album = new Album($db);

// Get posted data
$data = $_POST;

// Chekc if data is complete
if (
	!empty($data["albumID"]) &&
	!empty($data["name"]) &&
	!empty($data["url"]) &&
	!empty($data["img"]) &&
	!empty($data["primaryArtistID"]) &&
	!empty($data["releaseDate"])
) {
	$album->id = $data["albumID"];
	$album->name = $data["name"];
	$album->url = $data["url"];
	$album->img = $data["img"];
	$album->primaryArtistID = $data["primaryArtistID"];
	$album->releaseDate = $data["releaseDate"];

	if ($album->create()) {
		// Set response to created
		http_response_code(201);

		echo json_encode(array("message" => "Album has been added"));
	} else {
		// Set response to service unavailable
		http_response_code(503);

		echo json_encode(array("message" => "Unable to add album"));
	}
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "Data incomplete"));
}
