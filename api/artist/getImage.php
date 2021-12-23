<?php
// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require '../config/database.php';
require '../objects/artists.php';

// Make db and artist object
$database = new Database();
$db = $database->getConnection();
$artist = new Artist($db);

// Get input
$artistID = isset($_GET["artistID"]) ? $_GET["artistID"] : "";

// Query the results
$stmt = $artist->getImage($artistID);

if ($stmt) {
	// Set response to ok
	http_response_code(200);

	echo json_encode($stmt);
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "No results found"));
}
