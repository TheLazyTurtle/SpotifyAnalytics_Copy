<?php
session_start();

// Require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object
require '../config/database.php';
require '../objects/songs.php';

//  Make db connection and new song object
$database = new Database();
$db = $database->getConnection();
$song = new Song($db);

// Get the keywords
$songName = isset($_POST["song"]) && !empty($_POST["song"]) ? $_POST["song"] : "%";
$artist = isset($_POST["artist"]) && !empty($_POST["artist"]) ? $_POST["artist"] : "%";

// Get songs
$stmt = $song->searchByArtist($songName, $artist);
$num = $stmt->rowCount();

// If there are results
if ($num > 0) {
	// Make results array
	$songsArr = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);
		array_push($songsArr, $songID);
	}

	// Set response to ok
	http_response_code(200);

	echo json_encode($songsArr);
} else {
	// Set response to bad request
	http_response_code(400);

	echo json_encode(array("message" => "No songs found"));
}
