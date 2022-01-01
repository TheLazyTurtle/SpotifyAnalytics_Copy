<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow_Methods: GET");
header("Acces-Control-Allow_credentials: true");
header("Content-Type: application/json");

// Include objects
include_once '../config/database.php';
include_once '../objects/artists.php';

// Make db connection
$database = new Database();
$db = $database->getConnection();

// Make new artist object
$artist = new Artist($db);
$artist->name = isset($_GET["artist"]) ? $_GET["artist"] : die(json_encode(array("message" => "No valid artist name")));

// Fetch the one artist
$artist->readOne();

// When data is found
if ($artist->name != null) {
	$artistArr = array(
		"artistID" => $artist->id,
		"name" => $artist->name,
		"url" => $artist->url,
		"img" => $artist->img,
	);

	// Set response code to ok
	http_response_code(200);

	echo json_encode($artistArr);
} else {
	// Set response code to not found
	http_response_code(404);

	echo json_encode(array("message" => "Artist does not exist"));
}
