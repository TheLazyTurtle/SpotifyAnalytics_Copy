<?php
// Required headers
header("Access-control-Allow_Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include db and object files
require '../config/database.php';
require '../objects/songs.php';

// Make db and song objects
$database = new Database();
$db = $database->getConnection();
$song = new Song($db);

// query songs
$stmt = $song->read();
$num = $stmt->rowcount();

if ($num > 0) {
	// Make results array
	$songArr = array();
	$songArr["records"] = array();

	while ($row = $stmt->FETCH(PDO::FETCH_ASSOC)) {
		extract($row);
		$songItem = array(
			"id" => $songID,
			"name" => $name,
			"img" => $img,
			"length" => $length,
			"url" => $url,
			"preview" => $preview
		);
		array_push($songArr["records"], $songItem);
	}

	// Set response to ok
	http_response_code(200);

	echo json_encode($songArr);
} else {
	// set not found response code
	http_response_code(404);

	// Tell the user no songs have been found
	echo json_encode(array("message" => "No songs found."));
}
