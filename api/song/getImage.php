<?php
// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require '../config/database.php';
require '../objects/songs.php';

// Make db and song object
$database = new Database();
$db = $database->getConnection();
$song = new Song($db);

// Get input
$songID = isset($_GET["songID"]) ? $_GET["songID"] : "";

// Query the songs
$stmt = $song->getImage($songID);
$num = $stmt->rowCount();

// If results
if ($num > 0) {
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$img = $row["img"];
	}
	// Set response to ok
	http_response_code(200);

	echo json_encode($img);
} else {
	// Set bad request
	http_response_code(400);

	echo json_encode(array("message" => "No results found"));
}
