<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow_Methods: GET");
header("Acces-Control-Allow_credentials: true");
header("Content-Type: application/json");

// Include objects
include_once '../config/database.php';
include_once '../objects/songs.php';
include "../objects/artists.php";

// Make db connection
$database = new Database();
$db = $database->getConnection();
$song = new Song($db);
$artist = new Artist($db);

// Get posted data
$song->id = isset($_POST['songID']) ? $_POST["songID"] : die();

// fetch that one song
$song->readOne();

// Found data
if ($song->name != null) {
	$songArr = array(
		"songID" => $song->id,
		"name" => $song->name,
		"length" => $song->length,
		"url" => $song->url,
		"img" => $song->img,
		"preview" => $song->preview,
		"artists" => $artist->searchBySongID($song->id)
	);

	// Set response code to ok
	http_response_code(200);

	echo json_encode($songArr);
} else {
	// Set resposne code to not found
	http_response_code(404);

	echo json_encode(array("message" => "Song does not exist"));
}
